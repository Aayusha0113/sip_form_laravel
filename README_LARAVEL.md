# Nepal Telecom SIP System (Laravel)

Laravel version of the SIP PBX / SIP Trunk application project. It provides:

- **Public form**: SIP application form at `/form` (submit creates `applications` + `dashboard_companies`).
- **Login**: Username + password + role (admin/user) at `/login`.
- **Dashboard**: List of companies and view company details (requires login).

## Requirements

- PHP 8.2+
- Composer
- MySQL (or use existing `telecom_db`)

## Setup

1. **Copy environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

2. **Configure database** in `.env`:
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=telecom_db
   DB_USERNAME=root
   DB_PASSWORD=
   ```

3. **Migrations**
   - **New database**: Run all migrations and seed an admin user:
     ```bash
     php artisan migrate
     php artisan db:seed
     ```
     Default admin: `username: admin`, `password: password`, role: admin.
   - **Existing telecom_db**: If your current MySQL `telecom_db` already has tables from the old PHP project, you can either:
     - Use a new database name (e.g. `sip_laravel`) and run `php artisan migrate` + `php artisan db:seed`, or
     - Manually add the new columns to `users` (username, role, permissions) and add the new tables (applications, dashboard_companies, user_activities, uploaded_files) if you want to keep existing data.

4. **Run the app**
   ```bash
   php artisan serve
   ```
   Open http://127.0.0.1:8000

## Routes

| Route            | Description                    |
|------------------|--------------------------------|
| `/`              | Redirects to `/form`           |
| `/form`          | Public SIP application form    |
| `/form/submit`   | POST submit application        |
| `/login`         | Login page                     |
| `/dashboard`     | SIP documents list (auth)      |
| `/dashboard/{sip}` | Company details (auth)       |
| `/logout`        | POST logout                    |

## Next steps (to match old project)

- Admin dashboard: user management, permissions, activity log (reuse logic from `admin_dashboard.php`).
- Applications list and status update (from `admin.php`).
- Document upload and view documents (from `upload_docs.php`, `view_documents.php`).
- Import/edit company (from `import.php`, `update_company.php`).
- Delete company (from `delete_company.php`).
- Estimate and letter generation (from `estimate.php`, `letter.php`).

Use the same database `telecom_db` and the same table structures so you can switch between the old PHP app and this Laravel app during migration.
