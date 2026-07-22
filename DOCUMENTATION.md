# Technical Documentation

This is a deep, file-by-file walkthrough of how the codebase actually works internally. The `README.md` is the portfolio-facing overview (highlights, security table, quick install); this document is for understanding or modifying the code itself — it traces the exact path a request takes, explains why each file exists, and documents the non-obvious decisions and trade-offs behind them.

---

## 1. Request lifecycle, end to end

Every request to the app follows the same path, regardless of which page it's for:

1. **`.htaccess`** (inside `public/`) rewrites every URL that isn't a real file or directory to `public/index.php`. This is what makes `/appointment-system/public/login` work even though there's no literal `login` file at that path.
2. **`public/index.php`** is the single entry point (front controller). It:
   - Loads the Composer autoloader (`vendor/autoload.php`) — this is what makes `use App\Controllers\...` work without manual `require` calls, via the PSR-4 mapping in `composer.json`.
   - Starts the session, with `httponly` and `samesite=Lax` cookie flags.
   - Computes `$basePath` — the URL prefix the app is being served under (e.g. `/appointment-system/public` on MAMP, or empty string if served from a domain root). This becomes the `BASE_URL` constant (see §5).
   - Computes `$route` by stripping `$basePath` off the actual request path, then looks it up in the `$routing` array (a plain `route string => file path` map).
   - `require`s the matched handler file. If there's no match, responds with a 404.
3. **The handler file** (e.g. `auth/login.php`, `appointments/create.php`) is a plain procedural script, not a class. It reads `$_SERVER["REQUEST_METHOD"]` and branches:
   - **GET**: usually renders a view (`require __DIR__ . "/../views/..."`), sometimes after a redirect-if-already-logged-in guard.
   - **POST**: reads `$_POST`, checks the CSRF token, instantiates the relevant Controller, calls a method on it inside a `try`/`catch`, and is the **only** place that calls `header()`, `http_response_code()`, and `echo json_encode(...)`. This is a deliberate architectural rule — see §6.
4. **The Controller** (`controllers/AppointmentController.php`, `controllers/AuthController.php`, `controllers/DashboardController.php`) holds validation and business logic. It never touches HTTP directly — every public method either `return`s a plain associative array (`['status' => ..., 'message' => ..., 'code' => ...]`) or lets a `DatabaseException` propagate up to the handler's `catch` block.
5. **The Model** (`models/Appointment.php`, `models/User.php`) is the only layer that touches the database, always through `mysqli` prepared statements. On any DB failure it throws `App\Core\DatabaseException` rather than returning `false` silently.
6. **The View** (`views/...`) is a plain PHP template — HTML with embedded `<?= ... ?>` for dynamic values, `htmlspecialchars()` around anything that could contain user input. Views never contain business logic or direct DB calls.

For **write actions** (create/edit/delete/login/register), the browser side of this is real AJAX, not a form submit: JavaScript intercepts the form's `submit` event, sends a `fetch()` POST, reads the JSON `{status, message, code}` response, and does `window.location = ...` on success or `alert(result.message)` on failure. No full-page POST-and-reload happens for these actions — see §7.

---

## 2. `app/core/` — the shared foundation

| File | What it does |
|---|---|
| `Database.php` | Reads `config/config.php` (which itself loads `.env` via Dotenv) and opens a `mysqli` connection in `connect()`. Throws `DatabaseException` if the connection fails. |
| `Model.php` | Base class for `Appointment`/`User`. Holds the shared `mysqli $conn` property and a constructor that takes an already-open connection — the connection is created once by the Controller and *injected* into the Model, rather than each Model opening its own. |
| `Controller.php` | Base class for the Controllers. Just one shared method: `redirect($path)`, wrapping `header("Location: " . $path); exit();`. |
| `Auth.php` | One static method, `Auth::isLoggedIn()` — `isset($_SESSION["User_id"])`. Every "is the user logged in" check in the app goes through this one place instead of repeating the raw `isset()` check. |
| `DatabaseException.php` | `class DatabaseException extends Exception {}` — a plain marker subclass. Its only purpose is to let `catch (DatabaseException $e)` distinguish "the database failed" from any other kind of exception, without changing any behavior of `Exception` itself. |

---

## 3. `controllers/` — business logic

### `AppointmentController`
Constructed with the appointment's field values (`$appointment_date`, `$appointment_time`, `$appointment_notes`, `$appointment_status` — all optional, `NULL` by default, since e.g. `updateStatus()` doesn't need any of them). Every public method starts with `$this->sessionCheck()`, which redirects to `/login` if `Auth::isLoggedIn()` is false.

- `create()` / `update($id)` — validate required fields aren't empty, validate the date/time isn't in the past, then call the Model.
- `index($page)` — computes `LIMIT`/`OFFSET` from the page number, calls `viewAppointments()` and `countAppointments()`, returns everything the view needs (`appointments`, `currentPage`, `totalPages`).
- `delete($id)` / `findById($id)` / `updateStatus($id, $status)` — thin wrappers around the Model, each passing `$_SESSION['User_id']` alongside the appointment id. This is the IDOR protection: the Model's SQL always filters by *both* the appointment id *and* the owning user id, so a request for someone else's appointment simply matches zero rows — it's indistinguishable from "doesn't exist," not a distinguishable "forbidden."

### `AuthController`
Constructed with the submitted credentials/profile fields. Two public entry points:

- `register()` — validates required fields, email format, password confirmation match, minimum password length (`validPasswordLenght()`, 8+ chars via `mb_strlen`), then checks the Model for existing email/username before hashing the password (`password_hash`) and inserting.
- `login()` — validates required fields and email format, looks up the user by email, and if found: checks `isAccountLocked()` (a `locked_until` timestamp in the future), then `password_verify()`s the password. On success, resets the failed-attempt counter and sets `$_SESSION["User_id"]`. On failure, increments the failed-attempt counter, and locks the account for 15 minutes if failed attempts reach 5.
- `loginCheck()` — a separate helper (not called from `login()`) used by the GET branch of `auth/login.php`/`register.php` to redirect an already-logged-in user away from the login/register form.

### `DashboardController`
One method, `index()`. Loads `config/news.php` for the NewsAPI key, builds the request URL, and calls `file_get_contents()` **with an explicit `User-Agent` header via a stream context** — NewsAPI rejects anonymous requests (no default `User-Agent`) with a `userAgentMissing` error otherwise. Returns the decoded JSON response as-is (the `articles` key is pulled out by the caller, `dashboard.php`).

---

## 4. `models/` — the only layer that touches the database

Every method follows the same shape: build a parameterized SQL string → `prepare()` → guard `if (!$stmt)` (throws `DatabaseException` on prepare failure, e.g. a syntax error or a schema mismatch) → `bind_param()` → `execute()` → guard again on execute failure → return the result (or `true` for writes).

- **`Appointment.php`**: `createAppointment`, `viewAppointments` (paginated, `ORDER BY appointment_time`), `countAppointments`, `delete`, `findById`, `updateAppointment`, `updateStatus`. Every query that touches a specific row includes `AND user_id = ?` in its `WHERE` clause — this is where the IDOR protection actually lives, not in the Controller.
- **`User.php`**: `createUser`, `usernameExists`, `emailExists`, `findByEmail`, `incrementFailedAttempts`, `lockAccount`, `resetFailedAttempts` — the last three back the login rate-limiting logic in `AuthController::login()`.

No raw string interpolation into SQL anywhere in either file — every dynamic value goes through `bind_param()`.

---

## 5. `BASE_URL` — making the app work from any URL prefix

**The problem this solves:** the app can be reached at different URL prefixes depending on environment — `/appointment-system/public/...` on MAMP (served from a subdirectory of `htdocs`), or from a domain root with no prefix at all (Docker's current config, and any real deployment). Early versions of this app had that prefix **hardcoded** as a literal string in ~15 PHP files and 5 JS files; moving between environments silently broke every link, form action, and redirect.

**The fix:** `public/index.php` computes the prefix once (`$basePath`, becoming empty string `''` when served from a root, or `/appointment-system/public` when served from a subdirectory) and exposes it as a global PHP constant: `define('BASE_URL', $basePath);`. Every file that needs to build a URL uses this constant instead of a literal string:

- In **PHP** (controllers, handlers): string concatenation — `BASE_URL . "/login"`.
- In **views** (HTML+PHP): echoed into attributes — `href="<?= BASE_URL ?>/appointments"`.
- In **JavaScript**, which can't see a PHP constant directly: `views/layout/header.php` prints it onto the `<body>` tag as `data-base-url="<?= BASE_URL ?>"`. Every JS file that needs it reads `document.body.dataset.baseUrl` into a `baseUrl` variable, then uses a template literal — `` fetch(`${baseUrl}/appointments/create`) ``.

**Why Docker currently still uses the `/appointment-system/public` prefix even though it doesn't have to:** the Docker image copies the code into `/var/www/html/appointment-system/` (see §9) specifically so that URLs stay identical to the MAMP setup during development. A true root-served deployment (e.g. Render/Railway) would just see `BASE_URL` resolve to an empty string automatically — no code changes needed, only the Apache/deployment config would differ.

---

## 6. The JSON response contract, and why handlers (not Controllers) touch HTTP

Every write endpoint returns the same shape: `{"status": "success"|"error", "message": "...", "code": N}`, where `code` is a plain integer mirroring the intended HTTP status (200/400/401/403/409/500).

**Why this split exists:** a Controller method like `AppointmentController::create()` just returns a plain PHP array — it never calls `header()` or `http_response_code()`. The *handler* file (`appointments/create.php`) is the only place that:
1. Checks the CSRF token (`$_POST['security_token'] === $_SESSION['csrf_token']`) before calling the Controller at all.
2. Wraps the Controller call in `try { ... } catch (DatabaseException $e) { $result = [...] }`, converting a thrown exception into the same array shape a normal error return would have.
3. Sends `header('Content-Type: application/json')`, `http_response_code($result['code'])`, and `echo json_encode($result)` — **once**, after either path.

This keeps Controllers testable without touching superglobals or the HTTP layer (see `tests/AppointmentControllerTest.php` — none of those tests need a running web server), and keeps the "how do we talk HTTP" decision in exactly one place per endpoint.

---

## 7. Frontend: real AJAX, not page reloads

Each mutating form (create/edit/delete appointment, login, register, status-update) has a matching JS file in `public/assets/js/`, all following the same pattern:

1. `document.getElementById('theForm').addEventListener('submit', onSubmit)`.
2. Inside the handler: `event.preventDefault()` (stop the browser's default full-page form submission), read field values via `document.getElementById(...).value`, build a `URLSearchParams` body.
3. `await fetch(...)`, `await res.json()`.
4. On `result.status === "success"`: `window.location = ...` (client-side redirect — note this is a *fresh navigation*, not an in-place DOM update, because the one-time CSRF token has just been consumed and the destination page needs a freshly-generated one).
5. On failure: `alert(result.message)`.

`update-status.js` is slightly different: it's a single **document-level** click listener (event delegation) rather than one listener per dropdown, because the status dropdowns are generated in a loop (one per appointment card) and delegating to `document` avoids attaching N listeners for N appointments. Its guard clause checks `event.target.dataset.status` to ignore clicks that aren't on a status option.

**Cache-busting:** `mainStyle.css` and `update-status.js` are linked with a `?v=<?= filemtime(...) ?>` query string (`views/layout/header.php`, `views/appointments/list.php`). `filemtime()` returns the file's last-modified Unix timestamp, so the URL changes automatically every time the file is edited — the browser treats it as a new resource and re-downloads it, without needing a manual cache-bust or hard refresh during development.

---

## 8. `database/` — three different SQL files, three different jobs

| File | Purpose |
|---|---|
| `schemaDB.sql` | Structure only — two `CREATE TABLE` statements (`users`, `appointments`), no data. Used to set up the real dev/production database. |
| `seed.sql` | Realistic sample data (3 users, 6 appointments) for demoing the app or local development, layered on top of `schemaDB.sql`. |
| `schemaDB_test.sql` | A **self-contained** copy: `CREATE DATABASE`/`USE` + the same table structure + two minimal dummy users (`id=1`, `id=2`) that the PHPUnit tests depend on via foreign key (`AppointmentModelTest` needs a real `user_id` to insert against). Kept as a separate file (rather than reusing `schemaDB.sql` + a separate insert step) so that both the Docker `db` service and GitHub Actions CI can set up a fully working, isolated test database from one mounted/copied file, without ever touching the real dev database. |

---

## 9. Docker

- **`Dockerfile`**: starts from `php:8.3-apache` (matches the `php: 8.3.30` platform pinned in `composer.json`), installs the `mysqli` extension and enables `mod_rewrite`, copies in a custom Apache vhost config, sets `WORKDIR /var/www/html/appointment-system` (see below for why), copies in the Composer binary from the official Composer image, copies the project code, and runs `composer install`.
- **`docker/apache.conf`**: sets `DocumentRoot /var/www/html` (the *parent* of where the code actually lives) and grants `AllowOverride All` specifically on `/var/www/html/appointment-system/public` — this is what lets `.htaccess` work inside the container. The code is deliberately nested one level deeper (`.../appointment-system/...`) instead of living directly at the document root, purely so the site keeps responding at the same `/appointment-system/public/...` URL prefix as MAMP (see §5's note on `BASE_URL`).
- **`docker-compose.yml`**: two services.
  - `app` — builds from the `Dockerfile`, maps host port `8080` to container port `80`, sets `DB_HOST: db` (overriding the `.env` value of `localhost`, since inside Docker the database is reachable by the service name `db`, not `localhost`), and bind-mounts the project directory (`.:/var/www/html/appointment-system`) so local file edits are reflected inside the running container immediately, without rebuilding the image.
  - `db` — the official `mysql:8.0` image, with `MYSQL_ROOT_PASSWORD`/`MYSQL_DATABASE` matching the `.env` values (`root`/`schemaDB`) so no extra credential wiring is needed, plus a health check (`mysqladmin ping`) so the `app` service doesn't try to connect before MySQL is actually ready to accept connections. Mounts both `schemaDB.sql` and `schemaDB_test.sql` into `/docker-entrypoint-initdb.d/` — the official MySQL image automatically runs every `.sql` file it finds there, once, on first container startup with an empty data directory.

**Running PHPUnit inside the container** (instead of via a locally-installed PHP) avoids a real gotcha: the Homebrew/system PHP on macOS has a different default `mysqli.default_socket` than MAMP's, so `Database::connect()` fails outside of MAMP's own bundled PHP unless you explicitly point at it. Running tests via `docker compose exec app vendor/bin/phpunit` sidesteps this entirely — the container's own PHP is correctly configured for the container's own MySQL.

---

## 10. `.github/workflows/tests.yml` — CI

Runs on every push. A single job on `ubuntu-latest`, with a `mysql:8.0` service (same root/root credentials and health check reasoning as Docker, above). Steps: checkout → install PHP 8.3 with the `mysqli` extension → `composer install` → import `schemaDB.sql` → seed two dummy test users directly via `mysql -e "INSERT ..."` (mirroring `schemaDB_test.sql`'s seed data, since the CI database is created fresh from `schemaDB.sql` alone, which has no seed data of its own) → write a throwaway `.env` file on the runner (`.env` is correctly gitignored, so it doesn't exist on a fresh checkout, and `Dotenv::createImmutable(...)->load()` throws if no `.env` file exists at all, regardless of already-set environment variables) → `vendor/bin/phpunit`.

---

## 11. `tests/`

| File | Covers |
|---|---|
| `AuthTest.php` | `Auth::isLoggedIn()`, both branches (session set / unset). |
| `AppointmentControllerTest.php` | Validation logic only — past-date/past-time rejection on `create()`/`update()`, invalid-status rejection on `updateStatus()`, empty-id rejection on `delete()`. None of these touch the database, since the validation happens before any query runs. |
| `AppointmentModelTest.php` | Real database writes: `createAppointment()` actually inserts a row; `findById()` returns `null` when the appointment belongs to a different user (the IDOR behavior, verified directly). |
| `UserModelTest.php` | `createUser()` actually inserts a row. Uses `uniqid()` in the username/email so the test is repeatable — `username`/`email` are `UNIQUE` columns, so a hardcoded value would only pass on the first run. |

All four run against `schemaDB_test`, never the real dev database — see `phpunit.xml`'s `<php><env name="DB_NAME" value="schemaDB_test"/></php>`, which relies on Dotenv's *immutable* mode never overwriting an already-set environment variable.

---

## 12. Notable trade-offs (deliberate, not oversights)

- **`findById()` returns `null` for both "doesn't exist" and "exists but belongs to someone else."** This is intentional, not a missing distinction — a differently-worded error for "not yours" vs "doesn't exist" would let an attacker enumerate valid appointment IDs that belong to other users. A dedicated `NotFoundException` was drafted at one point specifically to add that distinction and was deliberately deleted once this trade-off was recognized.
- **Status updates (`update-status.js`) do a full page reload on success instead of updating the badge in place.** The CSRF token is one-time-use (`unset($_SESSION['csrf_token'])` after each successful mutation) — an in-place update would need the response to include a freshly-issued token for the *next* action, which the JSON contract doesn't currently carry. Reloading the page is the simpler option that stays consistent with every other form's token-refresh behavior.
- **Delete is a GET-confirmation-page + POST-submit, not a single-click action.** `appointments/delete.php` splits into a GET branch (shows a read-only confirmation view) and a POST branch (the actual deletion, CSRF-checked, same JSON contract as everything else) — mirroring the same GET/POST split as edit, rather than deleting on a single unconfirmed click.
