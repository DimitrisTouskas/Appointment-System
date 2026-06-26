# Online Appointment System

A web-based appointment management system built with PHP (OOP/MVC), MySQL, JavaScript, and Bootstrap 5. This project was developed as a portfolio piece to demonstrate full-stack web development skills using core technologies without frameworks.

---

## Features

- User registration and login with secure password hashing
- Session-based authentication with login/logout functionality
- Create, view, edit, and delete appointments (full CRUD)
- Client-side and server-side validation
- Status management for appointments (Pending, Confirmed, Cancelled, Completed)
- Route protection — unauthenticated users are redirected to login

---

## Technologies Used

| Layer | Technology |
|---|---|
| Frontend | HTML5, CSS3, JavaScript |
| Styling | Bootstrap 5 |
| Backend | PHP 8 (OOP, MVC Architecture) |
| Database | MySQL (via mysqli) |
| Local Server | MAMP (Apache + PHP + MySQL) |

---

## Architecture

The project follows the **MVC (Model-View-Controller)** pattern:

- **Models** — Handle all database interactions using prepared statements
- **Controllers** — Handle business logic, validation, and session management
- **Views** — PHP templates responsible only for rendering HTML

---

## Prerequisites

- [MAMP](https://www.mamp.info/) (or XAMPP / any local Apache + PHP + MySQL stack)
- PHP 8.0 or higher
- MySQL 5.7 or higher

---

## Installation

1. Clone the repository into your local server's `htdocs` (MAMP) or `www` (XAMPP) folder:

```bash
git clone https://github.com/DimitrisTouskas/Appointment-System.git
```

2. Open **phpMyAdmin** and create a new database called `schemadb`.

3. Import the database schema:
   - Go to the `database/` folder
   - Import `schemaDB.sql` into your newly created database

4. Configure the database connection:
   - Open `config/config.php`
   - Update the database credentials if needed (host, username, password, database name)

5. Start your local server (MAMP/XAMPP) and visit:

```
http://localhost:8888/appointment-system/public/index.php
```

---

## Project Structure

```
appointment-system/
├── app/
│   └── core/          # Core classes (Database, Controller, Model)
├── appointments/      # Request handlers for appointment actions
├── auth/              # Request handlers for login, register, logout
├── config/            # Database configuration
├── controllers/       # AppointmentController, AuthController
├── models/            # Appointment, User models
├── public/
│   └── assets/        # CSS, JS, Images
├── views/
│   ├── appointments/  # create, edit, list views
│   └── auth/          # login, register views
└── database/          # SQL schema and seed files
```

---

## Author

**Dimitris Touskas**  
GitHub: [DimitrisTouskas](https://github.com/DimitrisTouskas)
