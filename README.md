# Laravel Boilerplate

A Laravel 13 starter kit with Blade + Alpine.js, role-based access control, activity logging, and AJAX form validation — built on the [Meridian / Stisla](https://github.com/stisla) UI template.

## Stack

| Layer | Package |
|---|---|
| Framework | Laravel 13, PHP 8.3+ |
| Frontend | Blade, Alpine.js v3, Tailwind CSS v4 |
| UI Template | @stisla/style + @stisla/vanilla (Meridian) |
| Charts | ApexCharts |
| Roles & Permissions | spatie/laravel-permission v8 |
| Activity Log | spatie/laravel-activitylog v5 |
| Database | MySQL |

---

## Installation

### Requirements

- PHP 8.3+
- Composer
- Node.js 18+
- MySQL

### Steps

**1. Clone and enter the directory**

```bash
git clone <repo-url> your-project
cd your-project
```

**2. Configure environment**

```bash
cp .env.example .env
```

Edit `.env` with your database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=boilerplate
DB_USERNAME=your_user
DB_PASSWORD=your_password
```

**3. Install everything in one command**

```bash
composer setup
```

This runs: `composer install` → generate app key → migrate + seed → `npm install` → `npm run build`.

---

## Running Locally

```bash
composer dev
```

Starts three processes concurrently:

- `php artisan serve` — Laravel at `http://localhost:8000`
- `npm run dev` — Vite HMR
- `php artisan queue:listen` — queue worker

---

## Default Credentials

| Email | Password | Role |
|---|---|---|
| admin@example.com | password | admin |

---

## Features

### Authentication

| Feature | Route |
|---|---|
| Login | `GET/POST /login` |
| Register | `GET/POST /register` |
| Forgot password | `GET/POST /forgot-password` |
| Logout | `POST /logout` |

All auth forms use **AJAX validation** via Alpine.js — validation errors appear inline without a page reload.

### Profile

| Feature | Route |
|---|---|
| View profile | `GET /profile` |
| Edit name & email | `GET /profile/edit` |
| Change password | `GET /profile/edit` (second form) |

Both edit forms use AJAX submission with inline error display and a success message on save.

### Roles & Permissions

Powered by [spatie/laravel-permission](https://github.com/spatie/laravel-permission).

Two roles are seeded by default:

- `admin`
- `user`

Assign a role to a user:

```php
$user->assignRole('admin');

// Check in Blade
@role('admin')
    <a href="/admin">Admin panel</a>
@endrole

// Check in PHP
if ($user->hasRole('admin')) { ... }
```

### Activity Log

Powered by [spatie/laravel-activitylog](https://github.com/spatie/laravel-activitylog).

The `User` model automatically logs changes to `name` and `email`. Recent activity is shown on the profile page.

Log an event manually:

```php
activity()->causedBy($user)->log('Did something');
```

### AJAX Form Validation

A global `formAjax` Alpine.js component handles all AJAX form submissions. Any form can opt in:

```html
<form x-data="formAjax" @submit.prevent="submit" method="POST" action="/your-route">
    @csrf

    <input name="field" :class="{ 'is-invalid': errors.field }" />
    <p x-show="errors.field" x-text="errors.field?.[0] ?? ''"></p>

    <button type="submit" :disabled="loading">
        <span x-show="!loading">Save</span>
        <span x-show="loading" x-cloak>Saving…</span>
    </button>
</form>
```

The component expects the server to return:
- `422` with `{ errors: { field: ['message'] } }` on validation failure
- `200` with `{ message: 'Success text' }` on success
- `200` with `{ redirect: '/url' }` to redirect after success

---

## Project Structure

```
app/
├── Http/Controllers/
│   ├── Auth/
│   │   ├── LoginController.php
│   │   ├── RegisterController.php
│   │   └── ForgotPasswordController.php
│   └── ProfileController.php
└── Models/
    └── User.php              # HasRoles + HasActivity

resources/
├── css/app.css               # Tailwind v4 + Stisla theme
├── js/app.js                 # Alpine.js + formAjax component
└── views/
    ├── layouts/
    │   ├── app.blade.php     # Authenticated shell (sidebar + navbar)
    │   └── auth.blade.php    # Two-panel auth layout
    ├── components/
    │   ├── sidebar.blade.php
    │   └── flash-message.blade.php
    ├── auth/                 # login, register, forgot-password
    ├── dashboard/
    └── profile/              # show, edit

database/
└── seeders/
    ├── RolePermissionSeeder.php   # creates admin + user roles
    └── DatabaseSeeder.php         # seeds admin@example.com
```

## Useful Commands

```bash
# Re-seed the database
php artisan migrate:fresh --seed

# List routes
php artisan route:list

# Run tests
composer test

# Format PHP code
vendor/bin/pint
```
