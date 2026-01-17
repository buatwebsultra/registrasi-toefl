# Panduan Setup Domain & SSL (SIPENA)

Dokumen ini berisi langkah-langkah yang harus dilakukan **setelah** domain `sipena.uho.ac.id` aktif dan diarahkan ke server ini.

## Prasyarat
Pastikan Admin Jaringan/IT UHO sudah mengarahkan DNS record:
- **A Record**: `sipena.uho.ac.id` --> `76.13.19.225`

## Langkah 1: Update Konfigurasi Aplikasi (Di Server)

Login ke server via SSH, lalu jalankan perintah berikut:

1. **Update file `.env` Laravel:**
   ```bash
   nano /var/www/sipena/.env
   ```
   Ubah baris `APP_URL` menjadi:
   ```ini
   APP_URL=https://sipena.uho.ac.id
   ```
   *(Simpan: Ctrl+X, Y, Enter)*

2. **Update Konfigurasi Nginx:**
   ```bash
   nano /etc/nginx/sites-available/sipena
   ```
   Cari baris `server_name` dan ubah menjadi:
   ```nginx
   server_name sipena.uho.ac.id;
   ```
   *(Simpan: Ctrl+X, Y, Enter)*

3. **Reload Nginx:**
   ```bash
   nginx -t  # Pastikan syntax ok
   systemctl reload nginx
   ```

## Langkah 2: Install SSL Gratis (Certbot)

Jalankan perintah ini untuk mengaktifkan HTTPS otomatis:

```bash
# 1. Pastikan tools terinstall (jika belum)
apt install certbot python3-certbot-nginx

# 2. Request sertifikat
certbot --nginx -d sipena.uho.ac.id
```

**Saat proses Certbot:**
- Masukkan email admin jika diminta.
- Setujui Terms of Service (Ketik `Y`).
- Jika ditanya tentang redirect HTTP ke HTTPS, pilih **2** (Redirect) agar semua akses otomatis aman.

## Langkah 3: Verifikasi

Buka browser dan akses `https://sipena.uho.ac.id`.
- Pastikan ada ikon gembok (Secure).
- Coba login dan pastikan semua fitur berjalan lancar.
