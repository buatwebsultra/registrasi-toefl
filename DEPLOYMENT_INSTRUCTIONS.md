# Panduan Deployment ke Hostinger (Laravel 12)

Dokumen ini menjelaskan langkah-langkah untuk meng-upload dan men-deploy aplikasi Registrasi TOEFL ke Hostinger menggunakan hPanel.

## Persiapan Sebelum Upload


### 1. Persiapan Database (hPanel)
1. Masuk ke **Hostinger hPanel**.
2. Cari menu **Databases** > **MySQL Databases**.
3. Buat database baru:
   - **MySQL database name**: (Contoh: `u123456789_toefl`)
   - **MySQL username**: (Contoh: `u123456789_user`)
   - **Password**: (Gunakan password yang kuat)
4. Catat informasi di atas untuk digunakan di file `.env`.

> [!NOTE]
> Untuk panduan lengkap memindahkan data database via SSH, lihat: [SSH_DB_MIGRATION_GUIDE.md](SSH_DB_MIGRATION_GUIDE.md)

### 2. Membundel Aplikasi
Gunakan script `prepare_for_upload.sh` untuk membuat file ZIP yang siap di-upload. Script ini akan mengecualikan file yang tidak diperlukan (seperti `.git`, `node_modules`, dll).

Jalankan di terminal lokal:
```bash
bash prepare_for_upload.sh
```
Hasilnya adalah file `toefl_registration_system.zip`.

---

## Langkah-Langkah Deployment

### 1. Upload File ke hPanel
1. Buka **File Manager** di hPanel.
2. Masuk ke direktori root (biasanya `/domains/namadomain.com/public_html` atau setingkat di atasnya).
3. Upload file `toefl_registration_system.zip`.
4. Ekstrak file tersebut. Pastikan struktur foldernya benar.
   > [!TIP]
   > Disarankan meletakkan file Laravel satu tingkat di atas `public_html` untuk keamanan lebih baik, namun jika ingin simpel, letakkan langsung di root dan arahkan domain ke folder `public`.

### 2. Konfigurasi File .env
Jika file `.env` belum ada di server, buat baru atau rename `.env.example` menjadi `.env`. Sesuaikan isinya:

```env
APP_NAME="Sistem Registrasi TOEFL"
APP_ENV=production
APP_KEY=base64:xxx... (generated)
APP_DEBUG=false
APP_URL=https://domainanda.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=u123456789_toefl
DB_USERNAME=u123456789_user
DB_PASSWORD=password_database_anda
```

### 3. Mengatur Document Root (PENTING)
Secara default, Laravel melayani aplikasi dari folder `public`. Di Hostinger:
1. Pergi ke menu **Domains** > **Websites**.
2. Klik **Manage** pada domain Anda.
3. Cari menu **General** > **Settings**.
4. Ubah **Directoy** atau **Document Root** untuk mengarah ke folder `public` aplikasi Anda.
   - Contoh: `public_html/public`

### 4. Instalasi Dependency & Migrasi (Via SSH)
Hostinger Shared Hosting (Plan Premium ke atas) mendukung SSH.
1. Masuk via SSH ke server Anda.
2. Masuk ke folder aplikasi.
3. Jalankan perintah berikut:
   ```bash
   # Install vendor dependencies
   composer install --no-dev --optimize-autoloader

   # Generate App Key (jika belum)
   php artisan key:generate

   # Jalankan migrasi database
   php artisan migrate --force

   # Optimasi performa
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

---

## Izin Folder (Permissions)
Pastikan folder berikut memiliki izin tulis (writable):
- `storage/`
- `bootstrap/cache/`

Gunakan File Manager atau SSH:
```bash
chmod -R 775 storage bootstrap/cache
```

## Troubleshooting
- **Error 500**: Periksa file `.env` (terutama database) dan pastikan versi PHP minimal 8.2.
- **Mix/Vite Manifest Not Found**: Pastikan Anda sudah menjalankan `npm run build` secara lokal sebelum membundel aplikasi jika menggunakan Vite.
- **Symlink issue**: Jika logo atau file storage tidak muncul, jalankan `php artisan storage:link`. Di Hostinger Shared, Anda mungkin perlu membuat cron job atau route khusus untuk menjalankan perintah ini jika tidak ada akses SSH penuh.

---
*Dibuat untuk Sistem Registrasi TOEFL - 2025*