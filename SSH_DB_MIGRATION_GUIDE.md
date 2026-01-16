# Panduan Migrasi Database via SSH (Hostinger)

Panduan ini menjelaskan cara memindahkan database lokal Anda ke Hostinger menggunakan akses SSH. Metode ini lebih cepat dan stabil dibandingkan phpMyAdmin, terutama untuk database berukuran besar.

---

## 1. Persiapan: Aktifkan Akses SSH

1. Login ke **Hostinger hPanel**.
2. Pergi ke menu **Advanced** > **SSH Access**.
3. Pastikan status SSH adalah **Enabled**.
4. Catat informasi berikut:
   - **SSH IP**: (Contoh: `185.xxx.xxx.xxx`)
   - **SSH Port**: (Biasanya `65002`)
   - **SSH Username**: (Contoh: `u123456789`)
   - **SSH Password**: (Sama dengan password akun hosting/FTP Anda. Jika lupa, bisa di-reset di sini).

---

## 2. Hubungkan ke Server via SSH

### Pengguna Linux / Mac / Windows Terminal
Buka terminal dan jalankan perintah:

```bash
ssh -p 65002 u123456789@185.xxx.xxx.xxx
```
*(Ganti dengan username dan IP Anda. Masukkan password saat diminta - ketikan password tidak akan terlihat).*

---

## 3. Skenario A: Migrasi Struktur Saja (Fresh Install)

Gunakan metode ini jika Anda ingin memulai dengan database bersih di server, tanpa data testing dari lokal.

1. **Pastikan Database Kosong dibuat**:
   - Di hPanel, buat database MySQL baru (catat nama DB, user, dan password).
2. **Konfigurasi `.env`**:
   - Edit file `.env` di server (via File Manager atau `nano .env` di SSH).
   - Isi kredensial database sesuai yang baru dibuat.
3. **Jalankan Migrasi**:
   Di terminal SSH, masuk ke folder aplikasi:
   ```bash
   cd domains/domainanda.com/public_html
   ```
   Jalankan artisan migrate:
   ```bash
   php artisan migrate --force
   ```
4. **Jalankan Seeder (Opsional)**:
   Jika Anda punya data awal (admin default, role, dll):
   ```bash
   php artisan db:seed --force
   ```

---

## 4. Skenario B: Migrasi Data Lengkap (Lokal -> Server)

Gunakan metode ini jika Anda ingin menyalin seluruh isi database lokal (termasuk data peserta, akun, dll) ke server.

### Langkah 1: Ekspor Database Lokal
Di komputer lokal Anda, buka terminal dan jalankan:

```bash
# Format: mysqldump -u [user_lokal] -p [nama_db_lokal] > [nama_file].sql
mysqldump -u root -p toefl_db > backup_toefl.sql
```
*(File `backup_toefl.sql` akan tercipta di folder Anda saat ini).*

---

### Langkah 2: Upload SQL ke Server
Anda bisa upload file `.sql` tersebut via File Manager hPanel, atau gunakan **SCP** (Secure Copy) via terminal lokal agar lebih profesional:

```bash
# Format: scp -P [port] [file_lokal] [user]@[ip]:[path_tujuan]
scp -P 65002 backup_toefl.sql u123456789@185.xxx.xxx.xxx:/home/u123456789/domains/domainanda.com/public_html/
```

---

### Langkah 3: Import ke Database Server
1. Kembali ke terminal **SSH Hostinger**.
2. Masuk ke folder tempat Anda meng-upload file `.sql`.
3. Jalankan perintah import mysql:

```bash
# Format: mysql -u [user_db_hostinger] -p [nama_db_hostinger] < [file_sql]
mysql -u u123456789_user -p u123456789_toefl < backup_toefl.sql
```
4. Masukkan password database Hostinger Anda (bukan password SSH).

**Selesai!** Database Anda sekarang sudah identik dengan yang ada di lokal.

---

## Troubleshooting Umum

> [!WARNING]
> **Error: Access denied for user...**
> Pastikan Anda menggunakan **Username Database** dan **Password Database** yang benar saat menjalankan perintah `mysql`, BUKAN username akun hosting.

> [!TIP]
> **Definer Error**
> Jika saat import muncul error tentang `DEFINER`, itu karena user database lokal (`root@localhost`) tidak ada di server. Solusi:
> 1. Buka file `.sql` di text editor.
> 2. Cari dan Hapus semua teks `DEFINER=`... sampai spasi berikutnya.
> 3. Simpan dan coba import lagi.

> **Koneksi Terputus**
> Jika SSH sering putus, tambahkan konfigurasi ini di file `~/.ssh/config` lokal Anda:
> ```
> Host *
>     ServerAliveInterval 60
> ```
