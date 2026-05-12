# Bayn Backend — Laravel 12 API

Backend API untuk aplikasi Bayn, dibangun dengan Laravel 12, Docker, dan JWT Authentication.

---

## 🧱 Tech Stack

| Komponen | Versi |
|---|---|
| PHP | 8.3 |
| Laravel | 12.x |
| MariaDB | 11 |
| Nginx | Alpine |
| Docker | 29.x |
| JWT Auth | tymon/jwt-auth ^2.3 |
| Spatie Permission | ^6.x |
| Spatie Activity Log | ^5.x |
| Spatie Settings | ^3.7 |

---

## 🏗️ Arsitektur

```
Client → Nginx → PHP-FPM (Laravel API)
                      ↓
                  MariaDB
```

---

## 📁 Struktur Proyek

```
my-project/
├── docker-compose.yml
├── nginx/
│   └── default.conf
├── php/
│   ├── Dockerfile
│   └── php.ini
└── backend/
    ├── app/
    │   ├── Http/
    │   │   ├── Controllers/Api/
    │   │   │   ├── AuthController.php
    │   │   │   ├── PasswordResetController.php
    │   │   │   ├── UserController.php
    │   │   │   ├── RoleController.php
    │   │   │   └── PermissionController.php
    │   │   └── Middleware/
    │   │       ├── EnsureUserIsActive.php
    │   │       └── LogIpActivity.php
    │   ├── Mail/
    │   │   ├── OtpMail.php
    │   │   └── VerifyEmailMail.php
    │   ├── Models/
    │   │   ├── User.php
    │   │   ├── UserProfile.php
    │   │   ├── Role.php
    │   │   ├── Permission.php
    │   │   ├── Otp.php
    │   │   ├── AuthLog.php
    │   │   └── RefreshToken.php
    │   ├── Settings/
    │   │   └── AppSettings.php
    │   └── Traits/
    │       └── ApiResponse.php
    ├── database/
    │   ├── migrations/
    │   └── seeders/
    └── resources/views/emails/
        ├── otp.blade.php
        └── verify_email.blade.php
```

---

## ⚙️ Setup & Instalasi

### Prasyarat
- Docker & Docker Compose
- Git

### 1. Clone Repository

```bash
git clone https://github.com/Lab-Kerkom-IT-Commun/web-backend-laravel.git
cd web-backend-laravel
git checkout leafy
```

### 2. Setup Environment

```bash
cp .env.example .env
```

Edit `.env`:

```env
APP_NAME=Bayn
APP_ENV=local
APP_DEBUG=true

DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=root

CACHE_STORE=file
CACHE_DRIVER=file
SESSION_DRIVER=database
QUEUE_CONNECTION=database

MAIL_MAILER=smtp
MAIL_HOST=smtp.hostinger.com
MAIL_PORT=465
MAIL_ENCRYPTION=ssl
MAIL_USERNAME=your@domain.com
MAIL_PASSWORD=yourpassword
MAIL_FROM_ADDRESS=your@domain.com
MAIL_FROM_NAME="${APP_NAME}"

JWT_SECRET=your_jwt_secret
```

### 3. Build & Jalankan Container

```bash
docker compose up -d --build
```

### 4. Inisialisasi Laravel

```bash
docker exec -it my-project-php-1 php artisan key:generate
docker exec -it my-project-php-1 php artisan migrate:fresh --seed
docker exec -it my-project-php-1 php artisan permission:cache-reset
```

### 5. Fix Permissions

```bash
docker exec -it my-project-php-1 chmod -R 775 /var/www/html/storage
docker exec -it my-project-php-1 chown -R www-data:www-data /var/www/html/storage
```

Aplikasi berjalan di: `http://localhost:8080`

---

## 🗃️ Database Schema

### `users`
| Kolom | Tipe | Keterangan |
|---|---|---|
| id | ulid | Primary key |
| google_id | varchar | nullable |
| email | varchar | unique |
| username | varchar | unique, nullable |
| password | varchar | hashed |
| email_verified_at | timestamp | nullable |
| status | enum | Active, NonActive, Suspended, Banned |
| access_token | text | nullable |
| refresh_token | text | nullable |
| expired_at | timestamp | nullable |
| deleted_at | timestamp | SoftDeletes |

### `user_profiles`
| Kolom | Tipe | Keterangan |
|---|---|---|
| id | ulid | Primary key |
| user_id | ulid | FK → users |
| fullname | varchar | nullable |
| photo | varchar | nullable |
| bio | text | nullable |
| phone_number | varchar | nullable |
| gender | enum | Laki, Perempuan, Tidak Disebutkan |
| birth_of_date | date | nullable |
| place_of_birth | varchar | nullable |
| citizen | enum | WNA, WNI |
| country_id | int | nullable |
| province_id | int | nullable |
| city_id | int | nullable |
| district_id | int | nullable |
| village_id | int | nullable |
| postal_code | int | nullable |
| rt | int | nullable |
| rw | int | nullable |
| address | text | nullable |

### `otps`
| Kolom | Tipe | Keterangan |
|---|---|---|
| id | ulid | Primary key |
| user_id | ulid | FK → users |
| email | varchar | |
| code | varchar | bcrypt hashed |
| type | varchar | email_verification, password_reset |
| expired_at | timestamp | 5 menit |
| used_at | timestamp | nullable, jika diisi = sudah digunakan |
| attempts | tinyint | default 0 |

### `auth_logs`
| Kolom | Tipe | Keterangan |
|---|---|---|
| id | ulid | Primary key |
| user_id | ulid | FK → users |
| event | varchar | register, login, logout, password_reset, email_verified |
| ip_address | varchar | nullable |

### `refresh_tokens`
| Kolom | Tipe | Keterangan |
|---|---|---|
| id | ulid | Primary key |
| user_id | ulid | FK → users |
| token | text | |
| is_revoked | boolean | default false |
| expires_at | timestamp | |

---

## 🔐 Roles

| Role | Keterangan |
|---|---|
| super_admin | Akses penuh |
| administrator | Admin aplikasi |
| pegawai | Pegawai |
| user | Pengguna umum |

---

## 🛡️ Middleware

| Middleware | Alias | Keterangan |
|---|---|---|
| EnsureUserIsActive | `active` | Hanya user dengan status Active yang bisa akses |
| LogIpActivity | `log.ip` | Mencatat IP dan event ke auth_logs |

---

## 📡 API Endpoints

Base URL: `http://localhost:8080/api`

Headers wajib:
```
Accept: application/json
Content-Type: application/json
```

### Format Response

Semua response menggunakan format seragam:

```json
{
    "status": true,
    "message": "Pesan response",
    "data": {},
    "meta": {
        "app_name": "Bayn",
        "app_version": "1.0.0"
    }
}
```

---

### Auth (Public)

| Method | Endpoint | Keterangan |
|---|---|---|
| POST | `/auth/register` | Daftar akun baru |
| POST | `/auth/verify-email` | Verifikasi email dengan OTP |
| POST | `/auth/resend-otp` | Kirim ulang OTP verifikasi |
| POST | `/auth/login` | Login |
| POST | `/auth/forgot-password` | Kirim OTP reset password |
| POST | `/auth/verify-otp` | Verifikasi OTP reset password |
| POST | `/auth/reset-password` | Reset password dengan token |

#### Register
```json
{
    "email": "user@example.com",
    "password": "password123",
    "password_confirmation": "password123",
    "username": "username"
}
```

#### Verify Email
```json
{
    "email": "user@example.com",
    "code": "123456"
}
```

#### Login
```json
{
    "email": "user@example.com",
    "password": "password123"
}
```

#### Forgot Password
```json
{
    "email": "user@example.com"
}
```

#### Verify OTP
```json
{
    "email": "user@example.com",
    "code": "123456"
}
```
Response: `{ "reset_token": "..." }`

#### Reset Password
```json
{
    "reset_token": "token_dari_verify_otp",
    "password": "newpassword123",
    "password_confirmation": "newpassword123"
}
```

---

### Auth (Protected)

Header: `Authorization: Bearer {token}`

| Method | Endpoint | Keterangan |
|---|---|---|
| GET | `/auth/me` | Data user yang login |
| POST | `/auth/logout` | Logout |
| POST | `/auth/refresh` | Refresh token |

---

### Users (Protected)

| Method | Endpoint | Keterangan |
|---|---|---|
| GET | `/users` | Daftar semua user (paginated) |
| GET | `/users/{id}` | Detail user |
| POST | `/users` | Buat user baru |
| PUT | `/users/{id}` | Update user & profile |
| DELETE | `/users/{id}` | Soft delete user |
| POST | `/users/{id}/restore` | Restore user yang dihapus |
| POST | `/users/{id}/roles` | Assign role ke user |
| DELETE | `/users/{id}/roles` | Hapus role dari user |
| POST | `/users/{id}/permissions` | Assign permission ke user |

#### Query Params (GET /users)
| Param | Keterangan |
|---|---|
| `search` | Cari berdasarkan email atau username |
| `status` | Filter berdasarkan status |
| `per_page` | Jumlah data per halaman (default: 10) |

#### Create User
```json
{
    "email": "user@example.com",
    "password": "password123",
    "password_confirmation": "password123",
    "username": "username",
    "status": "Active",
    "role": "user"
}
```

#### Update User
```json
{
    "fullname": "Nama Lengkap",
    "bio": "Bio singkat",
    "phone_number": "081234567890",
    "gender": "Laki",
    "citizen": "WNI",
    "address": "Alamat lengkap"
}
```

---

### Roles (Protected)

| Method | Endpoint | Keterangan |
|---|---|---|
| GET | `/roles` | Daftar semua role |
| POST | `/roles` | Buat role baru |
| DELETE | `/roles/{id}` | Hapus role |

#### Create Role
```json
{
    "name": "nama_role"
}
```

---

### Permissions (Protected)

| Method | Endpoint | Keterangan |
|---|---|---|
| GET | `/permissions` | Daftar semua permission |
| POST | `/permissions` | Buat permission baru |
| DELETE | `/permissions/{id}` | Hapus permission |

#### Create Permission
```json
{
    "name": "nama_permission"
}
```

---

## 🔄 Alur Auth

```
Register → OTP Email → Verify Email → Auto Login → JWT Token
                ↓
           OTP Expired? → Resend OTP

Forgot Password → OTP Email → Verify OTP → Reset Token → Reset Password
```

---

## 📦 Packages

| Package | Versi | Kegunaan |
|---|---|---|
| tymon/jwt-auth | ^2.3 | JWT Authentication |
| spatie/laravel-permission | ^6.x | Role & Permission |
| spatie/laravel-activitylog | ^5.x | Activity Logging |
| spatie/laravel-settings | ^3.7 | App Settings |

---

## 🐳 Docker Services

| Service | Image | Port |
|---|---|---|
| nginx | nginx:alpine | 8080:80 |
| php | php:8.3-fpm-alpine | - |
| db | mariadb:11 | - |
| redis | redis:alpine | - |

---

## 📌 Catatan Pengembangan

- Semua primary key menggunakan **ULID**
- Model `User`, `Role`, `Permission` menggunakan **SoftDeletes**
- OTP berlaku **5 menit** dan hanya bisa digunakan **sekali**
- Reset token berlaku **10 menit**
- Setiap aksi auth dicatat di tabel `auth_logs`
- Semua response API menggunakan format seragam dengan `meta` berisi `app_name` dan `app_version`
- Middleware `active` memblokir user dengan status selain `Active`
- Email menggunakan template bahasa Indonesia bermerek **Bayn**

---

## 🌿 Branch

| Branch | Keterangan |
|---|---|
| `main` | Branch utama |
| `leafy` | Branch development aktif |
