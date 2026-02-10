# Panduan Memperbaiki Error "413 Request Entity Too Large"

Error ini terjadi karena server (Nginx) membatasi ukuran file yang diupload (default 1MB), meskipun aplikasi kita mengizinkan hingga 2MB.

Untuk memperbaikinya, Anda perlu menjalankan perintah berikut di server produksi (akses via SSH).

## 1. Edit Konfigurasi Nginx

Buka file konfigurasi Nginx untuk situs anda:

```bash
sudo nano /etc/nginx/sites-available/sipena
```

Tambahkan baris `client_max_body_size 10M;` di dalam blok `server`:

```nginx
server {
    server_name sipena.uho.ac.id;
    
    # ... konfigurasi lain ...

    client_max_body_size 10M;  # <--- TAMBAHKAN BARIS INI

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
}
```

Simpan file (Ctrl+X, Y, Enter).

Kemudian, cek konfigurasi dan reload Nginx:

```bash
sudo nginx -t
sudo systemctl reload nginx
```

## 2. Edit Konfigurasi PHP

Anda juga perlu memastikan PHP mengizinkan upload file yang lebih besar.

Cek versi PHP yang digunakan (misalnya 8.1, 8.2, atau 8.3). Contoh untuk PHP 8.1:

```bash
sudo nano /etc/php/8.1/fpm/php.ini
```

Cari dan ubah nilai berikut:

```ini
upload_max_filesize = 10M
post_max_size = 12M
```

Simpan file.

Restart PHP-FPM:

```bash
sudo systemctl restart php8.1-fpm
```

(Ganti `8.1` dengan versi PHP yang sesuai di server Anda).

## 3. Selesai

Setelah menjalankan langkah di atas, error 413 seharusnya hilang dan user bisa mengupload file hingga 2MB (sesuai batasan aplikasi Laravel).
