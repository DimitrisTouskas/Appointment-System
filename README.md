# Online Appointment System

A web-based appointment management system built with PHP (OOP/MVC), MySQL, JavaScript, and Bootstrap 5. Built as a portfolio project without a framework, to demonstrate the underlying concepts frameworks usually hide: routing, request/response handling, session-based auth, and a consistent JSON API contract.

---

## Highlights

- **Real front controller / router** — a single entry point (`public/index.php`) dispatches every request through a routing table, not direct file access.
- **Security-first design** — CSRF tokens on every mutating request, IDOR protection at the query level (not bolted on after), password hashing, login rate limiting with account lockout, and hardened session cookies. See [Security](#security) below.
- **Consistent JSON API contract** — every write endpoint (login, register, create/edit/delete appointment) returns the same `{status, message, code}` shape, with real HTTP status codes and real AJAX (`fetch`) on the frontend — no page reloads, no raw JSON dumped on screen.
- **Custom exception handling** — database failures raise a typed `DatabaseException` instead of failing silently or crashing with an unhandled fatal error; a single `try`/`catch` per entry point turns it into a clean JSON error response.

---

## Features

- User registration and login with secure password hashing (`password_hash`/`password_verify`)
- Session-based authentication with login/logout
- Full CRUD for appointments (create, view, edit, delete), scoped per-user
- Pagination on the appointments list (5 per page)
- Client-side and server-side validation
- Status tracking for appointments (Pending, Confirmed, Cancelled, Completed)
- Auth-aware navigation (shows different links depending on login state)
- Route protection — unauthenticated users are redirected to login

---

## Security

| Threat | Mitigation |
|---|---|
| CSRF | One-time token generated per form, verified server-side before any state-changing action; unset after use |
| SQL injection | `mysqli` prepared statements everywhere, no raw string interpolation into queries |
| IDOR (accessing another user's appointment) | Every query scopes by `user_id` at the SQL level (`WHERE id = ? AND user_id = ?`), not filtered after the fact — a request for someone else's appointment returns nothing, indistinguishable from "not found" |
| Brute-force login | Account locks for 15 minutes after 5 failed attempts (tracked in `users.failed_login_attempts` / `locked_until`) |
| Weak passwords | Minimum 8 characters enforced on registration |
| Session hijacking | Session cookies set with `httponly` and `samesite=Lax` |
| Credential leakage | DB credentials in `.env` (gitignored), never hardcoded or committed |
| Unhandled DB failures | Database errors raise `DatabaseException` and are caught centrally, returned as a structured JSON error instead of a raw PHP fatal error or stack trace |

---

## Technologies Used

| Layer | Technology |
|---|---|
| Frontend | HTML5, CSS3, JavaScript (`fetch` API, no framework) |
| Styling | Bootstrap 5 |
| Backend | PHP 8.3 (OOP, MVC, PSR-4 autoloading via Composer) |
| Database | MySQL (via `mysqli`, prepared statements) |
| Testing | PHPUnit |
| Local server | MAMP (Apache + PHP + MySQL) |

---

## Architecture

- **Front controller** (`public/index.php`) — single entry point; parses the request URI against a routing table and dispatches to the matching handler. Everything else is unreachable directly (`.htaccess` rewrites all requests through it).
- **Handlers** (`auth/`, `appointments/`) — read the request, call the relevant Controller, and are the only place that talks HTTP directly (`header()`, `http_response_code()`, `echo json_encode(...)`).
- **Controllers** (`controllers/`) — business logic and validation. Never call HTTP functions directly; they return a plain `['status', 'message', 'code']` array (or throw), leaving HTTP concerns to the handler.
- **Models** (`models/`) — all database access, via prepared statements. Raise `DatabaseException` on failure instead of returning `false` silently.
- **Views** (`views/`) — pure PHP templates, no business logic. Shared `header.php`/`footer.php` layout.

### API response contract

Every write endpoint returns:

```json
{ "status": "success" | "error", "message": "...", "code": 200 }
```

`code` mirrors the HTTP status code the handler actually sends:

| Code | Meaning here |
|---|---|
| 200 | Success |
| 400 | Client sent invalid/malformed data (empty field, bad format) |
| 401 | Authentication failed (wrong password, user not found) |
| 403 | Forbidden (CSRF token mismatch, locked account) |
| 409 | Conflict (email/username already registered) |
| 500 | Server-side failure (database error) |

### Endpoints

| Route | Method | Description |
|---|---|---|
| `/login` | GET | Login form |
| `/login` | POST | Authenticate, returns JSON |
| `/register` | GET | Registration form |
| `/register` | POST | Create account, returns JSON |
| `/logout` | GET | Destroy session, redirect to login |
| `/appointments` | GET | Paginated list of the logged-in user's appointments |
| `/appointments/create` | GET | Create-appointment form |
| `/appointments/create` | POST | Create an appointment, returns JSON |
| `/appointments/edit` | GET | Edit form, pre-filled (`?appointment_id=`) |
| `/appointments/edit` | POST | Update an appointment, returns JSON |
| `/appointments/delete` | GET | Delete confirmation page (`?appointment_id=`) |
| `/appointments/delete` | POST | Delete an appointment, returns JSON |

All `/appointments/*` routes require an active session; all POST routes require a valid CSRF token.

---

## Project Structure

```
appointment-system/
├── app/core/            # Database, Model, Controller, Auth base classes, DatabaseException
├── appointments/        # Request handlers for appointment actions (create/edit/delete/list)
├── auth/                # Request handlers for login, register, logout
├── config/              # DB config (reads from .env)
├── controllers/         # AppointmentController, AuthController
├── database/            # SQL schema and seed data
├── models/              # Appointment, User
├── public/
│   ├── index.php        # Front controller / router
│   ├── .htaccess        # Rewrites all requests through index.php
│   └── assets/          # CSS, JS
├── tests/                # PHPUnit tests
├── views/
│   ├── appointments/    # list, create, edit, delete templates
│   ├── auth/            # login, register templates
│   └── layout/          # shared header.php / footer.php
├── .env.example
├── composer.json
└── phpunit.xml
```

---

## Prerequisites

- [MAMP](https://www.mamp.info/) (or XAMPP / any local Apache + PHP + MySQL stack) with `mod_rewrite` enabled
- PHP 8.3+
- MySQL 5.7+
- [Composer](https://getcomposer.org/)

---

## Installation

1. Clone the repository into your local server's `htdocs` (MAMP) or `www` (XAMPP) folder:

   ```bash
   git clone https://github.com/DimitrisTouskas/Appointment-System.git
   ```

2. Install dependencies:

   ```bash
   composer install
   ```

3. Copy `.env.example` to `.env` and fill in your database credentials:

   ```bash
   cp .env.example .env
   ```

4. Create a database and import the schema:
   - Create a database matching `DB_NAME` in your `.env`
   - Import `database/schemaDB.sql`
   - Optionally, import `database/seed.sql` for sample users/appointments (login with any seeded email, password `Password123!`)

5. Make sure Apache's `mod_rewrite` module is enabled (required for the router), then visit:

   ```
   http://localhost:8888/appointment-system/public/login
   ```

---

## Testing

```bash
vendor/bin/phpunit
```

---

## Author

**Dimitris Touskas**
GitHub: [DimitrisTouskas](https://github.com/DimitrisTouskas)
