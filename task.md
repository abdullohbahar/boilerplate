# Task List

## In Progress

## Backlog

### Auth
- [x] Password reset — halaman input password baru dari link email (`GET/POST /reset-password/{token}`)
- [x] Login rate limiting — throttle max attempt via ENV (`LOGIN_THROTTLE=true/false`, `LOGIN_MAX_ATTEMPTS=5`, `LOGIN_DECAY_MINUTES=5`)
- [x] Captcha pada form login & register:
  - [x] Toggle via ENV (`CAPTCHA_ENABLED=true/false`)
  - [x] Provider via ENV (`CAPTCHA_PROVIDER=google|cloudflare`)
  - [x] Support Google reCAPTCHA v2
  - [x] Support Cloudflare Turnstile
  - [x] Verifikasi server-side sebelum proses login/register
- [x] Social login via Laravel Socialite:
  - [x] Toggle per provider via ENV (`GOOGLE_LOGIN_ENABLED=true/false`, `GITHUB_LOGIN_ENABLED=true/false`)
  - [x] Support Google OAuth
  - [x] Support GitHub OAuth
  - [x] Handle akun baru (auto-register) vs akun lama (link ke existing user by email)
- [x] Two-Factor Authentication (2FA):
  - [x] TOTP via authenticator app (Google Authenticator, Authy)
  - [x] User bisa enable/disable 2FA dari halaman profil
  - [x] QR code setup + backup codes
  - [x] Middleware `require2fa` untuk route yang butuh 2FA

### Admin
- [x] Admin middleware — protect route dengan `role:admin`
- [x] User management — list, create, edit, delete, assign role
- [x] Role management — CRUD roles
- [ ] Menu management (RBAC) — hybrid config + DB approach:
  - [ ] `config/menus.php` — definisi menu tree (key, label, icon, route, parent, sort)
  - [ ] Tabel `menus` di DB + Artisan command `menu:sync`
  - [ ] UI assign menu per role (checklist)
  - [ ] Middleware cek akses route berdasarkan role
  - [ ] Sidebar render dinamis dari DB berdasarkan role user
- [ ] Activity log viewer — halaman admin lihat semua log aktivitas
- [x] Impersonation — admin bisa login sebagai user lain:
  - [x] Tombol "Login as" di halaman user management
  - [x] Banner di atas app saat sedang impersonate
  - [x] Tombol "Kembali ke akun saya" untuk stop impersonation
  - [x] Log aktivitas impersonation (siapa impersonate siapa)

### File Management
- [x] Tabel `files` polymorphic (`fileable_type`, `fileable_id`, `field`, `disk`, `path`, `original_name`, `mime_type`, `size`, `sort_order`)
- [x] Model `File` + trait `HasFile` pada model:
  - [x] Boot `saving` — deteksi `UploadedFile`, store, replace dengan path, delete file lama
  - [x] Boot `deleted` — auto-delete semua file terkait dari storage
  - [x] Relasi `morphOne` untuk single file, `morphMany` untuk multiple file
  - [x] Helper `fileUrl(string $field)` untuk akses URL dari Blade
- [x] Image resize & compression via Intervention Image:
  - [x] Auto-resize gambar ke dimensi maksimal yang dikonfigurasi per field
  - [x] Konfigurasi per field di model (`protected array $fileFields = ['avatar' => ['width' => 400, 'height' => 400]]`)
- [x] Integrasi ke `User` model — field `avatar`
- [x] UI upload avatar di halaman edit profil

### App Settings
- [x] Tabel `settings` key-value store (`key`, `value`, `type`, `group`, `is_encrypted`)
- [x] Model `Setting` + helper global `setting('key', $default)`
- [x] Cache settings agar tidak query DB setiap request
- [x] Field sensitif di-encrypt otomatis (`is_encrypted = true`) — pakai `encrypt()`/`decrypt()` Laravel
- [x] UI halaman admin untuk edit settings per group
- [x] Seeder settings default (nama app, deskripsi, dll)
- [x] SMTP settings di DB (group `mail`):
  - [x] Field: `mail.host`, `mail.port`, `mail.username`, `mail.password`, `mail.encryption`, `mail.from_address`, `mail.from_name`
  - [x] `mail.password` di-encrypt (`is_encrypted = true`)
  - [x] Load & override `config('mail.mailers.smtp')` di `AppServiceProvider::boot()`
  - [x] Fallback ke `.env` kalau setting DB kosong

### Multi-language (i18n)
- [x] Struktur translation files `lang/en/` dan `lang/id/`
- [x] `APP_LOCALE` di `.env`
- [x] Language switcher di navbar (toggle EN/ID)
- [x] Translation files: auth, passwords, pagination, app strings (EN + ID)
- [ ] Wrap semua hardcode string di view dengan `__()`  — ongoing as views are added

### Helpers & Utilities
- [ ] File `app/helpers.php` + autoload via `composer.json`:
  - [ ] `format_date($date, $format)` — format tanggal konsisten
  - [ ] `format_currency($amount, $currency)` — format angka ke mata uang
  - [ ] `format_bytes($bytes)` — convert bytes ke KB/MB/GB
  - [ ] `setting($key, $default)` — ambil app setting (wrapper)
- [ ] Trait `HasSlug`:
  - [ ] Auto-generate slug dari field yang didefinisikan di model (`protected string $slugFrom = 'name'`)
  - [ ] Hanya aktif jika model memiliki kolom `slug` (cek via `Schema::hasColumn`)
  - [ ] Handle duplicate slug dengan suffix angka (`judul`, `judul-1`, `judul-2`)
- [ ] Trait `HasSoftDelete` — konvensi soft delete:
  - [ ] Gunakan bawaan Laravel `SoftDeletes`, trait ini hanya tambah scope & helper
  - [ ] Scope `onlyTrashed()`, `withTrashed()` sudah ada di Laravel
  - [ ] Komponen UI badge "Deleted" + tombol Restore & Force Delete di halaman admin

### Developer Experience
- [ ] Development seeder — generate fake users dengan berbagai role menggunakan Faker (hanya jalan di `APP_ENV=local`)
- [ ] Custom artisan stubs — override stub `make:controller`, `make:model`, `make:request` agar output-nya sesuai konvensi boilerplate
- [ ] `.env.example` lengkap — semua ENV variable terdokumentasi dengan komentar, kelompokkan per fitur (App, DB, Mail, Auth, Captcha, Social Login, dll)

### UI / UX
- [ ] Pagination + search — komponen tabel reusable (search, sort, paginate)
- [x] Active menu indicator — sidebar highlight item aktif berdasarkan route saat ini
- [ ] Dynamic breadcrumb — helper atau component untuk generate breadcrumb dari array, tidak perlu hardcode per halaman
- [ ] Email template — base Blade layout branded Meridian untuk semua transactional email (password reset, welcome, dll)
- [ ] Maintenance mode — halaman custom dengan desain Meridian, toggle dari App Settings, whitelist IP via ENV (`MAINTENANCE_BYPASS_IPS`)
- [x] Custom error pages — 404, 403, 500, 419 dengan desain Meridian
- [ ] Session management di halaman profil:
  - [ ] Tampilkan semua sesi aktif (device, IP, last active, browser)
  - [ ] Tombol revoke sesi tertentu
  - [ ] Tombol "Logout semua perangkat lain"

### Nice to Have
- [ ] Notifikasi in-app — bell icon di navbar, Laravel Notification
- [ ] Export data — export tabel ke CSV

## Done
- [x] Setup project (Laravel 13, MySQL, Vite, Tailwind v4)
- [x] Install & konfigurasi Stisla / Meridian template
- [x] Auth — login, register, forgot password
- [x] Roles & Permissions (spatie/laravel-permission)
- [x] Activity log (spatie/laravel-activitylog)
- [x] Layout — app shell, sidebar, navbar, auth layout
- [x] Dashboard
- [x] Profile — halaman view profil + activity log
- [x] Profile — edit nama, email, ganti password
- [x] AJAX form validation dengan Alpine.js (`formAjax` component)
- [x] Fix AJAX form validation — `shouldRenderJsonWhen` di `bootstrap/app.php` hanya return JSON untuk `api/*`, ditambah `|| $request->wantsJson()`
- [x] README
