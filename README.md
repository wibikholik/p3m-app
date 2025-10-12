<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

---

## 🚀 Cara Menjalankan Proyek **P3M-App**

Berikut langkah-langkah untuk menjalankan proyek Laravel ini di komputer lokal:

```bash
# 1️⃣ Clone repositori dari GitHub
git clone https://github.com/wibikholik/p3m-app.git

# 2️⃣ Masuk ke folder project
cd p3m-app

# 3️⃣ Install dependensi composer
composer install

# 4️⃣ Salin file .env.example menjadi .env
cp .env.example .env

# 5️⃣ Atur koneksi database di file .env
# Contoh:
# DB_DATABASE=p3m_db
# DB_USERNAME=root
# DB_PASSWORD=


# 7️⃣ Jalankan migrasi database
php artisan migrate

# 8️⃣ Buat symbolic link untuk upload file (storage)
php artisan storage:link

(Opsional) Jalankan seeder jika tidak ingin register manual
Seeder ini akan membuat akun bawaan untuk login ke aplikasi.

php artisan db:seed

# 9️⃣ Jalankan server Laravel
php artisan serve

Jika kamu menjalankan php artisan db:seed, maka akun berikut otomatis tersedia:


Admin P3M	
email: admin@p3m.com 
pass: admin123
Reviewer	
email: reviewer@p3m.com
pass: reviewer123
Dosen		
email: wibi@p3m.com
pass: dosen123