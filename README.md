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

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework. You can also check out [Laravel Learn](https://laravel.com/learn), where you will be guided through building a modern Laravel application.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## SKENARIO UJI MANUAL - AUTENTIKASI & OTORISASI RBAC (INCREMENT 7)

1.  Skenario Uji: Peran Admin (Langkah 9 - Poin 2)
    Langkah Pengujian:
    1. Buka browser dan akses halaman `/login`.
    2. Masukkan email `admin@pos.test` dan kata sandi `password`.
    3. Setelah berhasil masuk ke sistem, klik menu Produk atau akses langsung URL `http://127.0.0`.
       Hasil yang Diharapkan:
    - Pengguna berhasil masuk dan dialihkan.
    - Menu navigasi atas menampilkan menu Produk dan Kategori serta nama akun "Admin Kafe (admin)".
    - Halaman daftar produk (`/products`) terbuka secara normal dan menampilkan data produk.

2.  Skenario Uji: Peran Kasir (Langkah 9 - Poin 3 & 4)
    Langkah Pengujian :
    1. Melakukan logout dari akun admin.
    2. Buka kembali halaman `/login` lalu masukkan email `kasir@pos.test` (atau `test@example.com`) dan kata sandi `password`.
    3. Coba ketik secara manual URL `http://127.0.0` atau `/categories` pada address bar browser.
       Hasil yang Diharapkan :
    - Saat berhasil login, kasir diarahkan ke halaman utama transaksi kasir (`/pos`) secara normal.
    - Menu navigasi atas menyembunyikan menu Produk dan Kategori.
    - Saat mengakses URL `/products` secara manual, sistem menolak dan menampilkan Halaman Error 403 Forbidden dengan pesan "Anda tidak memiliki akses untuk halaman ini.".

3.  Skenario Uji: Tamu / Pengguna Belum Login (Langkah 9 - Poin 6)
    Langkah Pengujian:
    1. Pastikan browser dalam kondisi belum login (buka jendela Incognito baru).
    2. Coba akses langsung URL halaman terproteksi seperti `http://127.0.0` atau `http://127.0.0`.
       Hasil yang Diharapkan:
    - Sistem mendeteksi bahwa pengguna belum melakukan autentikasi (tamu).
    - Laravel otomatis melakukan redirect (pengalihan) kembali ke halaman `/login`, bukan memunculkan halaman error kode status.

## Diskusi: Pemilihan Format Ekspor Laporan

Untuk laporan 100.000 transaksi, format yang paling tepat adalah CSV karena diekspor dengan metode _streaming_ (`lazyById`) yang menghemat memori. Sebaliknya, format PDF (DomPDF) memuat seluruh dokumen di memori sekaligus sehingga rentan menyebabkan _Out of Memory_ jika data terlalu besar.

## Identifikasi Proses Simple POS yang Layak Dipindah ke Queued Job (Langkah 10)

Proses yang sangat layak dipindahkan ke **queued job** pada sistem Simple POS adalah **Pengiriman Struk Pembelian Digital (Email/WhatsApp Receipt)** dan **Notifikasi Peringatan Stok Menipis (Low Stock Alert)** ke Admin:

1. **Menghilangkan Latensi Kasir (Non-Blocking I/O):**
   Saat transaksi kasir selesai disimpan (`TransactionController::store`), kasir harus langsung dapat melayani antrean pelanggan berikutnya tanpa jeda. Menghubungi mail server (SMTP) atau webhook API WhatsApp secara _synchronous_ membutuhkan waktu 1-3 detik per transaksi. Dengan background queue, kasir mendapatkan respons instan.
2. **Ketahanan dan Mekanisme Coba Ulang (Retry & Resilience):**
   Layanan pihak ketiga (email/SMS gateway) rentan mengalami kendala jaringan sewaktu-waktu. Jika dijalankan di antrean (_queue worker_), kegagalan koneksi tidak akan membatalkan transaksi belanja yang sah, dan Laravel dapat mencoba mengirimkan kembali secara otomatis berkat konfigurasi `$tries` dan `$backoff`.
3. **Skalabilitas Kasir di Jam Sibuk (Peak Hours):**
   Pemisahan tugas berat seperti _rendering_ PDF struk digital dan pengiriman data ke antrean terpisah memastikan performa aplikasi kasir tetap stabil dan cepat walaupun ratusan transaksi masuk secara bersamaan.

## Pembagian Tugas Kelompok Jobsheet 9

| Nama Anggota           | Langkah/Tugas yang Dikerjakan                                                         | Hash Commit                      |
| ---------------------- | ------------------------------------------------------------------------------------- | -------------------------------- |
| Naufal Alif Adjani     | Langkah 1, 2, 3, 9, 10 (Filter kategori laporan, dokumentasi queue di README)        | `72770db`, `45967d9`, `c9ad0fa`  |
| Muhammad Rifat Syamlan | Langkah 4, 5, 9 (Pengukuran memori get vs lazyById, export laporan ke Excel)          | `6304a7a`, `6fa59ea`             |
| Achmad Alfi Maksum     | Langkah 6, 9, 10 (Laporan PDF, tabel produk terlaris di PDF, penutup increment 9)     | `996f832`, `8e1bfa3`, `cc214cb` |
| Siti Aisyah            | Langkah 7, 8, 9, 10 (Import CSV idempotent, queued job import, log baris gagal)       | `2ae1631`, `9030157`, `d86fea1` |
