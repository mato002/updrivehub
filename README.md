# Driver Recruitment Application Portal

A public-facing driver recruitment portal built with Laravel, Blade, Tailwind CSS, and MySQL. Applicants can submit personal details, employment history, driving experience, and supporting documents. Each submission is stored securely and triggers email notifications to HR and the applicant.

## Features

- **Landing page** with company info, benefits, qualifications, process, FAQ, and contact details
- **6-step application form** with progress indicator and client-side validation
- **Secure document uploads** (PDF, JPG, JPEG, PNG — max 5 MB each)
- **Auto-save** form progress in browser localStorage
- **Drag-and-drop** file uploads with image previews and progress bars
- **Reference number generation** (e.g. `DRV-20260805-000001`)
- **Email notifications** to HR and applicant confirmation
- **Rate limiting** (5 submissions per hour per IP)
- **CSRF protection** and MIME type validation

## Requirements

- PHP 8.3+
- Composer
- Node.js 18+
- MySQL 8+ (or MariaDB)

## Setup

### 1. Install dependencies

```bash
composer install
npm install
npm run build
```

### 2. Environment configuration

Copy `.env.example` to `.env` if needed, then configure:

```env
APP_URL=http://localhost/driver-application/public

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=driver_recruitment
DB_USERNAME=root
DB_PASSWORD=

MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-email
MAIL_PASSWORD=your-password
MAIL_FROM_ADDRESS=recruitment@yourcompany.com
MAIL_FROM_NAME="Your Company"

RECRUITMENT_COMPANY_NAME="Your Company Name"
RECRUITMENT_HR_EMAIL=hr@yourcompany.com
RECRUITMENT_PHONE="+254 700 000 000"
RECRUITMENT_EMAIL=recruitment@yourcompany.com
RECRUITMENT_ADDRESS="Nairobi, Kenya"
```

### 3. Generate key and prepare storage

```bash
php artisan key:generate
php artisan storage:link
```

### 4. Create database and migrate

Create the MySQL database:

```sql
CREATE DATABASE driver_recruitment CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Then run migrations:

```bash
php artisan migrate
```

### 5. XAMPP / Apache

Point your virtual host or access the app via:

```
http://localhost/driver-application/public
```

Ensure the `storage` and `bootstrap/cache` directories are writable.

## Routes

| Method | URI | Description |
|--------|-----|-------------|
| GET | `/` | Landing page |
| GET | `/apply` | Application form |
| POST | `/apply` | Submit application |
| GET | `/apply/success/{reference}` | Success page |

## File Storage

Uploaded documents are stored in:

```
storage/app/public/driver_documents/{year}/{month}/
```

Files use UUID-based filenames and are never overwritten.

## Development

Run the dev server with Vite hot reload:

```bash
composer dev
```

Or separately:

```bash
php artisan serve
npm run dev
```

## Email Testing

For local development, set `MAIL_MAILER=log` in `.env`. Emails will be written to `storage/logs/laravel.log`.

## Project Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── HomeController.php
│   │   └── DriverApplicationController.php
│   └── Requests/
│       └── StoreDriverApplicationRequest.php
├── Mail/
│   ├── HrApplicationNotification.php
│   └── ApplicantConfirmation.php
├── Models/
│   └── DriverApplication.php
└── Services/
    ├── DocumentStorageService.php
    └── ReferenceNumberGenerator.php
config/recruitment.php
resources/views/
├── home.blade.php
├── applications/
│   ├── create.blade.php
│   └── success.blade.php
└── emails/
database/migrations/
```

## License

MIT
