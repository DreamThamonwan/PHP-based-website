# EasyEV-Charging (PHP + MySQL) — EV Charging Station Management

EasyEV-Charging is a dynamic web application built with **object-oriented PHP** and **MySQL** to manage EV charging locations and charging sessions. It supports two roles—**Customer** and **Administrator**—and models real operational workflows such as station availability, check-in/check-out, and session cost calculation.

This project was completed as a major assignment for **MTS9307 Web Server Programming**.

---

## Why this project matters (real-world workflow)
Many businesses still rely on manual, paper-based or spreadsheet-heavy tracking for operational processes. This app demonstrates how to:
- centralise operational data (locations, capacity, users, sessions),
- enforce rules (availability, one active session, etc.),
- and provide role-based views that reduce confusion and errors.

---

## Key Features

### Customer features
- View all charging locations and availability
- Search charging locations
- **Check-in** to start charging (only if a spot is available)
- **Check-out** to end charging and see total cost
- View **active sessions** (current check-ins)
- View **charging history** (past sessions)

### Administrator features
- Add and modify charging locations
- List stations (all / available-only / full-only)
- Search charging locations
- List all registered users
- List users currently checked in (active sessions)

---

## Tech Stack
- **Backend:** Object-Oriented PHP
- **Database:** MySQL (MySQLi)
- **Frontend:** HTML, CSS, Bootstrap 5

---

## High-Level Architecture

**Presentation layer**
- PHP templates render Bootstrap 5 UI
- Helper modules generate tables/cards for consistent presentation (e.g., `EVTable()`)

**Domain layer (OOP)**
- Traits encapsulate DB and business logic:
  - `database` trait: connection + DB bootstrap
  - `EV` trait: station CRUD & validation
  - `Session` trait: check-in/check-out workflow + cost calculation
- Concrete classes:
  - `User`: registration/login + customer actions
  - `Admin`: extends `User` and adds admin reporting/actions

**Data layer**
Self-initialising schema created on first run:
- `users` — registered accounts and roles
- `charging_stations` — station capacity and live availability
- `sessions` — one row per charging session (start/end time, cost)

---

## Setup (Local Run)

### Prerequisites
- PHP + MySQL local server (e.g., **XAMPP**, WAMP, MAMP)
- Web browser

### Steps
1. Place project files into your web server root (e.g., `htdocs/` in XAMPP) or a sub-folder.
2. Start **Apache** and **MySQL** in your local server environment.
3. Open the project entry point:
   - `http://localhost/<your-folder>/index.php`

### Database notes
- The application attempts to create the database **`EasyEV_Charging`** if it does not exist.
- Default DB config (from the report) is in `classes.php` under the `database` trait:
  - host: `localhost`
  - username: `root`
  - password: `""` (empty)
- If your environment differs, update the connection details in `classes.php`.
- Required tables (`users`, `charging_stations`, `sessions`) are created automatically if missing.
- An SQL file may also be included (e.g., `easyev_charging.sql`) as a bootstrap/reference.

---

## Project Structure (as per implementation)
- `index.php` — entry point: login, registration, logout, public search, list available stations
- `signInForm.php`, `signUpForm.php` — auth UI
- `classes.php` — core OOP logic (traits + classes)
- `adminPanel.php` — admin dashboard
- `addEV.php` — add station form (admin)
- `editEV.php` — edit stations (admin)
- `customerPanel.php` — customer dashboard
- `customerCheckIn.php` / `customerCheckOut.php` — session start/end
- `admin-functions.php` / `customer-functions.php` — UI helper functions
- `style_sheet.css` — site styling
- `Image/` — images for UI sections

---

## Validation & Error Handling
- **Client-side:** Bootstrap validation states toggled based on PHP validation flags.
- **Server-side:** Inputs are validated using regex checks before database operations.
- Database actions are wrapped with exception handling (`mysqli_sql_exception`) and error messages are returned to the UI.

---

## UI/UX Notes
- Bootstrap-based responsive layout with a desktop breakpoint at ~992px
- Cards and tables used for consistent scanning of stations/sessions
- Availability status uses clear colour indicators (e.g., available vs full)

---

## Security / Implementation Notes (Honest)
- Password hashing in this assignment uses **MD5** (as per report).  
  For production systems, I would replace this with `password_hash()` / `password_verify()` and add stronger security controls.
- Payment processing is **not implemented** (only cost calculation & display).

---

## What I learned
- Designing an end-to-end CRUD application with role-based functionality
- Translating a workflow into data models (stations, sessions, capacity, availability)
- Applying input validation and predictable error handling
- Keeping logic reusable by separating domain logic (traits/classes) from presentation helpers

---

## Screenshots
If you are reviewing this project, see the `/Image` folder and UI panels:
- Home + authentication
- Admin panel (add/edit/list/search)
- Customer panel (available stations / active sessions / history)
- Check-in/out flow and cost display

---

## Author
Thamonwan (Dream) Nitatwichit  
GitHub: https://github.com/DreamThamonwan
