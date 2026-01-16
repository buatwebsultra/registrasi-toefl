# Panduan Update Aplikasi & Database ke Hostinger

Panduan ini menjelaskan cara memperbarui website Anda di Hostinger jika Anda telah melakukan perubahan fitur (kodingan) atau perubahan database di komputer lokal.

---

## 1. Update Fitur (Kode / Tampilan)
Jika Anda hanya mengubah file controller, view (blade), css, atau logic php lainnya:

1.  **Edit & Test Lokal**: Pastikan perubahan berjalan lancar di komputer Anda.
2.  **Upload File yang Berubah**:
    -   Buka **File Manager Hostinger**.
    -   Masuk ke folder `public_html`.
    -   Upload file yang Anda modifikasi saja (timpa file lama).
    -   *Contoh*: Jika Anda mengedit `resources/views/welcome.blade.php`, upload file itu ke lokasi yang sama di server.

> [!TIP]
> Jika Anda mengubah banyak file, lebih aman untuk men-zip folder project lokal (kecuali `vendor` dan `.env`), upload zipnya, lalu ekstrak di server untuk menimpa semua file sekaligus.

---

## 2. Update Database (Menambah Tabel / Kolom)
⚠️ **PENTING**: Jangan pernah mengedit file `2024_01_01...snapshot.php` lagi. File itu adalah masa lalu.

Jika Anda ingin menambah tabel baru atau kolom baru, ikuti **Siklus Migrasi** yang benar:

### Langkah A: Di Komputer Lokal
1.  Buat file migrasi baru untuk perubahan Anda.
    ```bash
    php artisan make:migration tambah_kolom_hp_ke_users
    ```
2.  Edit file baru tersebut di `database/migrations/xxxx_xx_xx_tambah_kolom....php`.
3.  Test jalankan di lokal: `php artisan migrate`.

### Langkah B: Di Hostinger
1.  **Upload File Migrasi Baru**:
    -   Upload **hanya file migrasi baru tersebut** ke folder `database/migrations/` di Hostinger.
2.  **Jalankan Perintah**:
    -   Buka SSH Hostinger.
    -   Jalankan:
        ```bash
        php artisan migrate --force
        ```
    -   Sistem akan mendeteksi *hanya* file baru tersebut dan menjalankannya. Database Anda akan terupdate tanpa menghapus data lama.

---

## 3. Jika Error atau Lupa (Cache)
Setiap kali Anda mengubah file `.env` atau konfigurasi (folder `config/`), selalu jalankan ini di SSH agar perubahan terbaca:

```bash
php artisan optimize:clear
```
