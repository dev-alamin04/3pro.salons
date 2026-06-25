# Laravel & Bootstrap Admin Dashboard Stater kit

Thank you for visiting this repository.

## Project Overview

### Backend

|                                                    ||
| :------------------------------------------------: | :------------------------------------------------: |
|                 Dashboard Preview                  | Table Demo|
| ![Admin Dashboard](/screenshort/dashboard.png) |![User table demo](/screenshort/demo.png)|

<!-- ### Frontend APP -->

<!-- 
|                                                  |
| :----------------------------------------------: |
|                   APP Preview                    |
| ![Admin Dashboard](/screenshort/app-preview.jpg) | -->


## Installation Guide

### 1. Clone Repository

```bash
git clone https://github.com/dev-alamin04/BANGLA-PUZZLE-LIMITED-Task.git
cd BANGLA-PUZZLE-LIMITED-Task
```

### 2. Install Dependencies

```bash
composer install
npm install && npm run dev
```

### 3. Configure Environment

Copy `.env.example` to `.env` and update database credentials:

```env
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password
```

### 4. Generate Application Key

```bash
php artisan key:generate
```

### 5. Run Migrations & Seed Data

```bash
php artisan migrate:fresh --seed
```

### 6. Optimize & Autoload

```bash
composer dump-autoload
php artisan optimize:clear
```

### 7. Start Development Server

```bash
php artisan serve
```

Visit **[http://127.0.0.1:8000](http://127.0.0.1:8000)**

---

## Default Credentials

### Admin Panel

* **URL**: `http://127.0.0.1:8000/admin/dashboard`
* **Email**: `admin@gmail.com`
* **Password**: `12345678`

### User Panel

* **URL**: `http://127.0.0.1:8000`
* **Email**: `user@gmail.com`
* **Password**: `12345678`

---

## API Usage (Sanctum)

Use **Bearer Token** authentication for protected routes.

* **Register:** `POST /api/register`
* **Login:** `POST /api/login`
* **Verify OTP:** `POST /api/verify-otp`
* **Forgot Password:** `POST /api/forgot-password`
* **Reset Password:** `POST /api/reset-password`
* **Logout:** `POST /api/logout`

---

## Design System

### Button and Banner Color
- `#D92D20` → `#00b4c8`
- Button info `#35E07Be6` → `#00b4c8`
- Link `#0d6efd` → `#00b4c8`
- Text muted `#a4abc5` → `#818898`

### Sidebar
- `#27282D` → `#ffffff`

### Container
- `#0B0B0D` → `#F5F5F5`

### Table Border
- `#59595A`

### Guest Background (Linear)
- `#D92D20` = `#090909`

### Typography
- **Black**: `#0B0B0D`
- **Graphite**: `#16171A`
- **Text**: `#F2F3F5` / `#A9AFBB`

### Action Colors
- **CTA Green**: `#35E07B`
- **Alert Red**: `#E04747`
- **Rank Gold**: `#E2B84B`

---

## Deployment Commands

```bash
php artisan key:generate
php artisan storage:link
php artisan migrate --force
php artisan config:cache
php artisan route:cache
```




