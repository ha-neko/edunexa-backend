# Edunexa Backend Setup Guide

Repository ini berisi infrastruktur Docker (Nginx, MySQL, Redis) dan aplikasi Laravel 13 untuk project **Edunexa**.

## 🛠 Prasyarat (Windows)
Sebelum memulai, pastikan tim sudah menginstal:
1. **Docker Desktop**: [Download di sini](https://www.docker.com/products/docker-desktop/)
2. **WSL 2**: Pastikan Docker Desktop dikonfigurasi untuk menggunakan backend WSL 2.
3. **Git**: [Download di sini](https://git-scm.com/downloads)

---

## 🚀 Cara Setup Pertama Kali

Ikuti langkah-langkah berikut secara berurutan:

### 1. Clone Repository
Buka PowerShell atau Terminal di Windows, lalu jalankan:
```bash
git clone git@github.com:ha-neko/edunexa-backend.git
cd edunexa-backend
```

### 2. Persiapkan Environment File
Salin file template environment untuk Laravel:
```bash
cp edunexa/.env.example edunexa/.env
```

### 3. Jalankan Docker Containers
Nyalakan semua service (Nginx, PHP-FPM, MySQL, Redis):
```bash
docker-compose up -d --build
```
*Tunggu proses build selesai. Ini mungkin memakan waktu beberapa menit pada percobaan pertama.*

### 4. Install Dependencies & Generate Key
Jalankan perintah ini untuk menginstal library PHP dan mengamankan aplikasi:
```bash
# Install composer packages
docker exec -it edunexa-app composer install

# Generate Laravel Application Key
docker exec -it edunexa-app php artisan key:generate

# Jalankan migrasi database
docker exec -it edunexa-app php artisan migrate
```

---

## 🔗 Akses Aplikasi
Setelah setup selesai, kamu bisa mengakses:
- **Web App**: [http://localhost:8081](http://localhost:8081)
- **Database**: localhost:3306 (User: `root`, Pass: `password`)

---

## 💡 Tips Pengembangan
- **Perintah Artisan**: Selalu jalankan melalui docker, contoh:  
  `docker exec -it edunexa-app php artisan make:controller NamaController`
- **Permission Error**: Jika muncul error "Permission Denied" di folder storage, jalankan:  
  `docker exec -it edunexa-app chmod -R 777 storage bootstrap/cache`
- **Update Config**: Jika kamu mengubah file di folder `nginx/`, jangan lupa restart container:  
  `docker restart edunexa-nginx`

