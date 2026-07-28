# Boilerplate Docs

Full reference for every feature in this boilerplate. See [README.md](README.md) for installation steps.

---

## Table of Contents

1. [Authentication](#authentication)
2. [Two-Factor Authentication (2FA)](#two-factor-authentication)
3. [Social Login](#social-login)
4. [CAPTCHA](#captcha)
5. [Login Rate Limiting](#login-rate-limiting)
6. [Session Management](#session-management)
7. [Role-Based Access Control](#role-based-access-control)
8. [Admin Panel](#admin-panel)
9. [Menu Management (RBAC)](#menu-management)
10. [Impersonation](#impersonation)
11. [Activity Log](#activity-log)
12. [File Uploads & Image Resize](#file-uploads--image-resize)
13. [App Settings](#app-settings)
14. [Multi-language (i18n)](#multi-language)
15. [AJAX Form Validation](#ajax-form-validation)
16. [Blade Components](#blade-components)
17. [Model Traits](#model-traits)
18. [Global Helpers](#global-helpers)
19. [Middleware](#middleware)
20. [Email Templates](#email-templates)
21. [Error Pages](#error-pages)
22. [Developer Experience](#developer-experience)

---

## Authentication

Standard auth routes are registered under `guest` middleware.

| Route | Description |
|---|---|
| `GET/POST /login` | Login form |
| `GET/POST /register` | Registration form |
| `GET/POST /forgot-password` | Request password reset email |
| `GET/POST /reset-password/{token}` | Set new password from email link |
| `POST /logout` | Logout (auth required) |

All forms support **AJAX validation** — errors appear inline without page reload. See [AJAX Form Validation](#ajax-form-validation).

---

## Two-Factor Authentication

TOTP-based 2FA via `pragmarx/google2fa-laravel` (Google Authenticator, Authy, etc.).

### Routes

| Route | Description |
|---|---|
| `GET /profile/two-factor` | Setup page with QR code |
| `POST /profile/two-factor/enable` | Verify TOTP code and enable 2FA |
| `DELETE /profile/two-factor` | Disable 2FA (requires current password) |
| `GET/POST /two-factor-challenge` | Login challenge page |

### Flow

1. User opens the setup page → QR code is generated from a fresh secret stored in session.
2. User scans with authenticator app and enters the 6-digit code.
3. On success: secret is saved (encrypted) + 8 backup codes generated. Backup codes are shown **once** in a flash message.
4. On next login: credentials are validated but login is deferred — user is redirected to `/two-factor-challenge` to enter TOTP or a backup code.

### Protecting routes with 2FA

Apply the `require2fa` middleware alias to any route that must have 2FA active:

```php
Route::middleware(['auth', 'require2fa'])->group(function () {
    Route::get('/sensitive', SensitiveController::class);
});
```

If the user has not enabled 2FA, they are redirected to the setup page.

### Backup codes

- 8 codes are generated on enable, each single-use.
- Stored as `encrypted:array` on the `User` model.
- A used code is removed from the array after successful login.

---

## Social Login

Powered by `laravel/socialite` v5. Supports Google and GitHub OAuth.

### Enable per provider

```env
GOOGLE_LOGIN_ENABLED=true
GOOGLE_CLIENT_ID=...
GOOGLE_CLIENT_SECRET=...
GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback

GITHUB_LOGIN_ENABLED=true
GITHUB_CLIENT_ID=...
GITHUB_CLIENT_SECRET=...
GITHUB_REDIRECT_URI=http://localhost:8000/auth/github/callback
```

### Routes

```
GET /auth/{provider}/redirect   — redirect to OAuth provider
GET /auth/{provider}/callback   — handle OAuth callback
```

Supported values for `{provider}`: `google`, `github`.

### Behavior

- **New user**: account is auto-created with a random password, `user` role assigned, email verified.
- **Existing user** (same email): logged into the existing account.
- No password is required for social-authenticated accounts; password login still works if one was set.

---

## CAPTCHA

Server-side CAPTCHA verification without any extra package — implemented in `App\Support\Captcha`.

### Enable

```env
CAPTCHA_ENABLED=true
CAPTCHA_PROVIDER=google        # or: cloudflare
```

**Google reCAPTCHA v2:**
```env
GOOGLE_RECAPTCHA_SITE_KEY=...
GOOGLE_RECAPTCHA_SECRET_KEY=...
```

**Cloudflare Turnstile:**
```env
CLOUDFLARE_TURNSTILE_SITE_KEY=...
CLOUDFLARE_TURNSTILE_SECRET_KEY=...
```

### Usage in controllers

```php
use App\Support\Captcha;

if (! Captcha::verify($request)) {
    return back()->withErrors(['captcha' => 'CAPTCHA verification failed.']);
}
```

### Usage in views

```blade
<x-captcha />
```

The component conditionally renders the widget (Google or Turnstile) and pushes the required script to `@stack('head')` once. Add `@stack('head')` inside `<head>` of your layout if not already present.

---

## Login Rate Limiting

Brute-force protection using Laravel's `RateLimiter` facade.

### Configure

```env
LOGIN_THROTTLE=true
LOGIN_MAX_ATTEMPTS=5
LOGIN_DECAY_MINUTES=5
```

When `LOGIN_THROTTLE=true`, failed login attempts over `LOGIN_MAX_ATTEMPTS` within the window trigger a lockout. The rate limit key is hashed from email + IP.

On a successful login or a correct 2FA challenge, the limiter is cleared.

---

## Session Management

Users can view and revoke their active sessions from the profile page.

### Routes

| Route | Description |
|---|---|
| `GET /profile/sessions` | List active sessions |
| `DELETE /profile/sessions/{session}` | Revoke a specific session |
| `DELETE /profile/sessions` | Revoke all sessions except current |

Session data is read from the `sessions` table (requires `SESSION_DRIVER=database`). Each session shows: IP address, browser/device, last activity time, and whether it is the current session.

---

## Role-Based Access Control

Powered by `spatie/laravel-permission` v8.

### Default roles

| Role | Description |
|---|---|
| `admin` | Full access to admin panel |
| `user` | Standard user |

### Assign / check roles

```php
// Assign
$user->assignRole('admin');
$user->syncRoles(['editor', 'user']);

// Check in PHP
$user->hasRole('admin');
$user->hasAnyRole(['admin', 'editor']);

// Check in Blade
@role('admin')
    <a href="{{ route('admin.users.index') }}">Users</a>
@endrole
```

### Protect routes

```php
// Single role
Route::middleware('admin')->prefix('admin')->group(function () {
    // ...
});

// Any role (via spatie middleware)
Route::middleware('role:editor|admin')->group(...);
```

The `admin` middleware alias maps to `App\Http\Middleware\EnsureIsAdmin`.

### Manage roles via admin panel

Admin panel at `/admin/roles` provides CRUD for roles. New roles can be created and assigned to users from `/admin/users`.

---

## Admin Panel

All admin routes are protected by `middleware(['auth', 'admin'])` and prefixed `/admin`.

| Route | Description |
|---|---|
| `GET /admin/users` | User list with search + sort |
| `GET /admin/users/create` | Create user form |
| `GET /admin/users/{id}/edit` | Edit user (name, email, role) |
| `DELETE /admin/users/{id}` | Delete user |
| `GET /admin/roles` | Role list |
| `GET/POST /admin/roles/create` | Create role |
| `GET/PUT /admin/roles/{id}/edit` | Edit role |
| `DELETE /admin/roles/{id}` | Delete role |
| `GET /admin/activity` | Activity log viewer |
| `GET/PUT /admin/menus` | Menu access (role-menu assignment) |
| `GET/PUT/POST /admin/settings` | App settings |

---

## Menu Management

Hybrid config + DB approach for RBAC-based sidebar visibility.

### How it works

1. `config/menus.php` — static source of truth (key, label, icon, route, sort).
2. `menus` table — synced from config via Artisan command.
3. `menu_role` pivot — stores which roles can see which menu.
4. Sidebar renders dynamically based on the authenticated user's roles.

### Sync menus from config

Run after adding or removing entries in `config/menus.php`:

```bash
php artisan menu:sync
```

### Assign roles via UI

Go to `/admin/menus` — a checklist grid lets you assign one or more roles to each menu item.

### Adding a new menu item

**1. Add to `config/menus.php`:**

```php
[
    'key'        => 'reports',
    'label'      => 'Reports',
    'icon'       => 'reports',
    'route'      => 'reports.index',
    'parent_key' => null,
    'sort'       => 200,
],
```

**2. Sync:**
```bash
php artisan menu:sync
```

**3. Add a sidebar entry** in `resources/views/components/sidebar.blade.php`:
```blade
@if ($canSee('reports'))
    <li class="sidebar__item">
        <a class="sidebar__button {{ request()->routeIs('reports.*') ? 'active' : '' }}"
            href="{{ route('reports.index') }}">
            {{-- your SVG icon here --}}
            <span>Reports</span>
        </a>
    </li>
@endif
```

**4. Assign roles** at `/admin/menus`.

### Protect a route with menu access middleware

```php
Route::middleware(['auth', 'menu'])->get('/reports', ReportController::class);
```

The `menu` middleware (`App\Http\Middleware\CheckMenuAccess`) looks up the route name in the `menus` table and checks if the user's role is assigned. If the route is not in the menus table, it passes through.

### Fallback behavior

If the `menus` table is empty (fresh install before seeding), the sidebar falls back to config-based visibility filtered by role — admins see all menus, users see non-`admin.*` menus. Run `php artisan db:seed --class=MenuSeeder` to seed default assignments.

---

## Impersonation

Admins can log in as any non-admin user to debug or support.

### Usage

On the `/admin/users` page, click **Login as** next to a user. A banner appears at the top of the app with a **Return to my account** button.

### Behavior

- Only admins can impersonate.
- Admins cannot impersonate other admins.
- The original admin's ID is stored in `session('impersonator_id')`.
- Both start and stop events are logged to the activity log.

### Check in code

```php
if (session('impersonator_id')) {
    // currently impersonating someone
}
```

---

## Activity Log

Powered by `spatie/laravel-activitylog` v5.

### Automatic logging on User model

Changes to `name` and `email` are logged automatically via the `HasActivity` trait (included on `User`).

### Log manually

```php
// Simple
activity()->causedBy(auth()->user())->log('Exported report');

// With subject
activity()
    ->causedBy(auth()->user())
    ->performedOn($post)
    ->log('Published post');
```

### View logs

Admin panel at `/admin/activity` shows all activity with search (by description or user) and date sort.

On the profile page (`/profile`), each user sees their own recent activity.

### Add activity logging to a model

```php
use App\Models\Concerns\HasActivity;

class Post extends Model
{
    use HasActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['title', 'body', 'status'])
            ->logOnlyDirty();
    }
}
```

---

## File Uploads & Image Resize

The `HasFile` trait provides polymorphic file management backed by the `files` table and Intervention Image v4.

### Setup on a model

```php
use App\Models\Concerns\HasFile;

class Product extends Model
{
    use HasFile;

    protected array $fileFields = [
        'thumbnail' => ['disk' => 'public', 'dir' => 'products', 'width' => 800, 'height' => 600],
        'brochure'  => ['disk' => 'public', 'dir' => 'brochures'],  // no resize
    ];
}
```

### Upload

Assign an `UploadedFile` to the field before saving — the trait handles storage, resizing, and DB record creation automatically:

```php
$product->thumbnail = $request->file('thumbnail');
$product->save();
```

### Access the URL

```php
// In PHP
$product->fileUrl('thumbnail');                  // returns URL or null
$product->fileUrl('thumbnail', '/img/default.png'); // with fallback

// In Blade
<img src="{{ $product->fileUrl('thumbnail', asset('img/placeholder.png')) }}" />
```

### Relationships

```php
$product->file('thumbnail');   // morphOne — latest File record for this field
$product->files();             // morphMany — all File records
```

### Automatic cleanup

- When a field is updated with a new file, the old file is deleted from storage and the old `File` record is removed.
- When a model is deleted (including soft-deleted then force-deleted), all associated files are removed.

---

## App Settings

Key-value settings stored in the `settings` table, cache-backed, with optional encryption.

### Read a setting

```php
// Global helper
$name = setting('app.name', 'My App');

// Model static method
$name = Setting::get('app.name', 'My App');
```

### Write a setting

```php
Setting::set('app.name', 'New Name');

// With type and encryption
Setting::set('mail.password', 'secret', [
    'type'         => 'string',
    'group'        => 'mail',
    'is_encrypted' => true,
]);
```

### Supported types

| Type | Stored as | Cast on read |
|---|---|---|
| `string` | raw string | string |
| `boolean` | `"1"` / `"0"` | `(bool)` |
| `integer` | numeric string | `(int)` |
| `json` | JSON string | `array` |

### Cache

All settings are cached under the `settings` key (`Cache::rememberForever`). The cache is cleared automatically on any `Setting` save or delete. Manually clear with:

```bash
php artisan cache:clear
```

### SMTP from DB

Settings with keys `mail.host`, `mail.port`, `mail.username`, `mail.password`, `mail.encryption`, `mail.from_address`, `mail.from_name` override the `config('mail')` values at boot time. This lets you change SMTP without redeploying.

`mail.password` is stored encrypted (`is_encrypted = true`).

### Admin UI

`/admin/settings` lists settings grouped by `group`. Encrypted fields render as a password input — leave blank to keep the existing value.

---

## Multi-language

Supports English (`en`) and Indonesian (`id`). Locale is stored in the session.

### Switch language

```html
<form method="POST" action="{{ route('locale.switch', 'id') }}">@csrf</form>
```

A language switcher is built into the navbar. Any number of locales can be supported by adding them to `config/app.php`:

```php
'supported_locales' => ['en', 'id'],
```

### Translation files

```
lang/
├── en/
│   ├── app.php      # boilerplate-specific strings
│   ├── auth.php
│   ├── pagination.php
│   └── passwords.php
└── id/
    ├── app.php
    ├── auth.php
    ├── pagination.php
    └── passwords.php
```

Use in Blade:

```blade
{{ __('app.save_changes') }}
{{ __('auth.failed') }}
```

### Add a new language

1. Create `lang/{locale}/` directory with the required files.
2. Add the locale to `config/app.supported_locales`.

---

## AJAX Form Validation

A global `formAjax` Alpine.js component handles form submissions with inline error display.

### Basic usage

```html
<form x-data="formAjax" @submit.prevent="submit" method="POST" action="{{ route('profile.update') }}">
    @csrf
    @method('PUT')

    <input name="name" :class="{ 'is-invalid': errors.name }" value="{{ old('name', $user->name) }}" />
    <p x-show="errors.name" x-text="errors.name?.[0] ?? ''" class="field__error"></p>

    <button type="submit" :disabled="loading">
        <span x-show="!loading">Save</span>
        <span x-show="loading" x-cloak>Saving…</span>
    </button>
</form>
```

### Server response contract

| HTTP status | Body | Component behavior |
|---|---|---|
| `422` | `{ errors: { field: ['msg'] } }` | Errors displayed inline |
| `200` | `{ message: 'Saved.' }` | Success toast shown |
| `200` | `{ redirect: '/url' }` | Browser redirects |
| `200` | `{ message: '...', redirect: '/url' }` | Toast then redirect |

### Controller setup

Controllers must return JSON for AJAX requests. This is enabled globally in `bootstrap/app.php`:

```php
$exceptions->shouldRenderJsonWhen(
    fn (Request $request) => $request->is('api/*') || $request->wantsJson(),
);
```

In your `FormRequest`, set `Accept: application/json` by ensuring the form sends it (the `formAjax` component handles this automatically via `fetch`).

---

## Blade Components

All anonymous components live in `resources/views/components/`.

### `<x-breadcrumb>`

Renders breadcrumb `<li>` elements. Last item gets `aria-current="page"`.

```blade
@section('breadcrumb')
    <x-breadcrumb :items="[
        ['label' => 'Dashboard', 'route' => 'dashboard'],
        ['label' => 'Users', 'route' => 'admin.users.index'],
        ['label' => $user->name],
    ]" />
@endsection
```

Item keys:
- `label` *(required)* — display text
- `route` — named route (makes it a link)
- `url` — explicit URL (alternative to `route`)
- `params` — route parameters array (used with `route`)

### `<x-search-bar>`

Search form with a clear button when a value is present.

```blade
<x-search-bar
    :action="route('admin.users.index')"
    :value="request('search')"
    placeholder="Search name or email…"
    name="search"
/>
```

### `<x-pagination>`

Conditionally renders `{{ $paginator->links() }}` inside `card__footer`.

```blade
<x-pagination :paginator="$users" />
```

### `<x-sortable-th>`

Table header cell with a clickable sort link. Reads `?sort=` and `?direction=` from the current request.

```blade
<x-sortable-th column="name" label="Name" />
<x-sortable-th column="created_at" label="Joined" />
```

Clicking toggles direction between `asc` and `desc`. The active column shows a directional arrow; inactive columns show a neutral up/down indicator.

For the controller, whitelist sortable columns:

```php
$allowed = ['name', 'email', 'created_at'];
$sort = in_array($request->get('sort'), $allowed) ? $request->get('sort') : 'created_at';
$direction = $request->get('direction') === 'asc' ? 'asc' : 'desc';

$users = User::orderBy($sort, $direction)->paginate(15)->withQueryString();
```

### `<x-captcha>`

Renders the CAPTCHA widget (Google reCAPTCHA or Cloudflare Turnstile) if `CAPTCHA_ENABLED=true`. Pushes required scripts once via `@pushOnce('head')`.

```blade
<x-captcha />
```

No output when `CAPTCHA_ENABLED=false`.

---

## Model Traits

### `HasFile`

Polymorphic file uploads with automatic resize. See [File Uploads](#file-uploads--image-resize).

### `HasActivity`

Wraps spatie/laravel-activitylog. Add to any model to enable automatic change logging:

```php
use App\Models\Concerns\HasActivity;

class Post extends Model
{
    use HasActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logOnly(['title', 'status'])->logOnlyDirty();
    }
}
```

### `HasSlug`

Auto-generates a `slug` column from a source field. Only activates when the model's table has a `slug` column.

```php
use App\Models\Concerns\HasSlug;

class Post extends Model
{
    use HasSlug;

    protected string $slugFrom = 'title'; // default: 'name'
}
```

Duplicate slugs get a numeric suffix: `my-post`, `my-post-1`, `my-post-2`.

### `HasSoftDelete`

Thin wrapper around Laravel's `SoftDeletes` that adds named helpers for clarity:

```php
use App\Models\Concerns\HasSoftDelete;

class Post extends Model
{
    use HasSoftDelete;
}

$post->restore();              // restore soft-deleted record
$post->forceDeletePermanently(); // hard-delete including files
```

---

## Global Helpers

Autoloaded from `app/helpers.php`.

### `setting(string $key, mixed $default = null)`

Read a value from the `settings` table (cache-backed).

```php
$appName = setting('app.name', config('app.name'));
```

### `format_date(mixed $date, string $format = 'd M Y')`

Parse and format a date string or Carbon instance.

```php
format_date($user->created_at);            // "28 Jul 2026"
format_date('2026-01-15', 'Y/m/d');        // "2026/01/15"
```

### `format_currency(int|float $amount, string $currency = 'IDR')`

Format a number as currency.

```php
format_currency(150000);           // "Rp 150.000"
format_currency(19.99, 'USD');     // "USD 19.99"
```

### `format_bytes(int $bytes)`

Convert bytes to a human-readable size.

```php
format_bytes(1048576);   // "1 MB"
format_bytes(500);       // "500 B"
```

---

## Middleware

| Alias | Class | Description |
|---|---|---|
| `admin` | `EnsureIsAdmin` | Requires `admin` role; 403 otherwise |
| `require2fa` | `RequireTwoFactor` | Requires 2FA to be enabled; redirects to setup if not |
| `menu` | `CheckMenuAccess` | 403 if the current route is in `menus` table but user's role is not assigned |
| *(global web)* | `SetLocale` | Applies `session('locale')` to every web request |

### Using `menu` middleware

```php
Route::middleware(['auth', 'menu'])->get('/reports', ReportController::class)->name('reports.index');
```

The route name must match the `route` column in the `menus` table. Routes not in the table pass through unconditionally.

---

## Email Templates

A branded HTML email base layout at `resources/views/emails/layout.blade.php`.

### Create an email view

```blade
@extends('emails.layout')

@section('subject', 'Welcome to ' . config('app.name'))

@section('content')
    <h1>Welcome, {{ $user->name }}!</h1>
    <p>Your account is ready.</p>
    <a href="{{ url('/') }}" class="btn">Go to app</a>
@endsection
```

### Mailable

```php
class WelcomeMail extends Mailable
{
    public function content(): Content
    {
        return new Content(view: 'emails.welcome', with: ['user' => $this->user]);
    }
}
```

---

## Error Pages

Custom error views using the Meridian design are in `resources/views/errors/`.

| File | HTTP Status | Triggered by |
|---|---|---|
| `403.blade.php` | Forbidden | `abort(403)` |
| `404.blade.php` | Not Found | Route not found |
| `419.blade.php` | Page Expired | CSRF token mismatch |
| `500.blade.php` | Server Error | Unhandled exception |
| `503.blade.php` | Maintenance | `php artisan down` |

### Maintenance mode

```bash
php artisan down              # enable
php artisan down --secret=abc # enable with bypass secret (/abc still works)
php artisan up                # disable
```

---

## Developer Experience

### One-command setup

```bash
composer setup
```

Runs: `composer install` → `php artisan key:generate` → `php artisan migrate --seed` → `npm install` → `npm run build`.

### Local dev server

```bash
composer dev
```

Starts concurrently: `php artisan serve`, `npm run dev` (Vite HMR), `php artisan queue:listen`.

### Development seeder

`DevSeeder` creates fake users and is only callable when `APP_ENV=local`:

```bash
php artisan db:seed --class=DevSeeder
```

Creates: 1 admin (seeded by `DatabaseSeeder`) + 3 fake users with `user` role.

### Re-seed from scratch

```bash
php artisan migrate:fresh --seed
```

Then re-seed menu assignments:
```bash
php artisan db:seed --class=MenuSeeder
```

### Custom Artisan stubs

Published stubs in `stubs/` override the defaults for `php artisan make:`:

- `controller.stub` — return types, no PHPDoc
- `request.stub` — `authorize()` returns `true` by default
- Others are the Laravel defaults (published for future customization)

### Useful commands

```bash
php artisan menu:sync              # sync menus table from config/menus.php
php artisan db:seed --class=MenuSeeder  # re-seed menu role assignments
php artisan route:list --except-vendor  # list app routes
vendor/bin/pint                    # format PHP code
php artisan test --compact         # run test suite
php artisan cache:clear            # clear all caches (settings, views, etc.)
```

### `.env.example`

All ENV variables are documented with comments, grouped by feature (auth, captcha, social login, mail, etc.). Copy and fill:

```bash
cp .env.example .env
```
