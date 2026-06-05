# logistic_fragile_final  

A PHP‑based web application for managing fragile freight logistics. It provides an admin dashboard for handling drivers, vehicles, trips, expenses, incomes, and generating reports, all backed by a MySQL database.

---

## Overview  

- **Database**: `Database/fragile_db.sql` – schema and seed data.  
- **Documentation**: `Logistics for Fragile Freights.docx` – functional specifications.  
- **Admin panel**: Located under the `admin/` directory (login, CRUD operations, reporting, etc.).  
- **Core configuration**: `config.php` (global settings) and `admin/config.php` (admin‑specific settings).  
- **Styling**: `css/style.css`.  
- **Support**: `contact_support.php` – simple contact form for user inquiries.

---

## Features  

| Category | Description |
|----------|-------------|
| **User Management** | Add, edit, and view drivers (`add_driver.php`, `edit_driver.php`, `view_drivers.php`). |
| **Vehicle Management** | Add, edit, and view vehicles (`add_vehcile.php`, `edit_vehicle.php`, `view_vehcile.php`). |
| **Trip Management** | Create, edit, and list trips (`add_trip.php`, `edit_trip.php`, `view_trips.php`). |
| **Financial Tracking** | Record expenses and incomes, view summaries (`add_expense.php`, `add_income.php`, `view_expenses.php`, `view_incomes.php`). |
| **Reporting** | Generate PDF/CSV reports for trips, finances, and driver performance (`generate_report.php`). |
| **Authentication** | Secure admin login/logout (`admin_login.php`, `logout.php`). |
| **Responsive UI** | Clean layout styled with `css/style.css`. |
| **Support** | Contact form for technical assistance (`contact_support.php`). |

---

## Tech Stack  

| Layer | Technology |
|-------|------------|
| **Backend** | PHP 7.4+ |
| **Database** | MySQL |
| **Frontend** | HTML5, CSS3 (custom stylesheet) |
| **Server** | Apache / Nginx (compatible with any LAMP/LEMP stack) |
| **Documentation** | Microsoft Word (`.docx`) for functional specs |

---

## Installation  

1. **Clone the repository**  
   ```bash
   git clone https://github.com/yourusername/logistic_fragile_final.git
   cd logistic_fragile_final
   ```

2. **Create a MySQL database**  
   ```sql
   CREATE DATABASE fragile_logistics;
   ```
   Import the schema and seed data:  
   ```bash
   mysql -u root -p fragile_logistics < Database/fragile_db.sql
   ```

3. **Configure the application**  
   - Copy `config.sample.php` to `config.php` (if a sample exists) or edit `config.php` directly.  
   - Set your database credentials, base URL, and any API keys (e.g., for email notifications).  
   ```php
   // config.php
   define('DB_HOST', 'localhost');
   define('DB_NAME', 'fragile_logistics');
   define('DB_USER', 'your_db_user');
   define('DB_PASS', 'your_db_password');
   define('BASE_URL', 'http://yourdomain.com/');
   define('YOUR_API_KEY', 'YOUR_OWN_API_KEY'); // replace with real key
   ```

4. **Set file permissions** (if required)  
   ```bash
   chmod -R 755 admin/
   ```

5. **Configure the web server**  
   - Point the document root to the project folder.  
   - Ensure `mod_rewrite` (Apache) or equivalent is enabled for clean URLs.

6. **Install dependencies** (optional)  
   The project uses only core PHP; no Composer packages are required.

---

## Usage