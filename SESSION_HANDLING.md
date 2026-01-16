# Session Expiration Handling

## Overview
Aplikasi ini sekarang akan secara otomatis mengarahkan pengguna ke halaman login yang sesuai ketika session mereka habis, tanpa menampilkan error "419 | Page Expired".

## Implementasi

### Exception Handler
File: [`bootstrap/app.php`](file:///home/ypi/Documents/registrasi-toefl/registrasi-toefl-app/bootstrap/app.php)

Handler exception menangkap `TokenMismatchException` (error 419) dan menentukan redirect berdasarkan path URL:

- **Path admin** (`/admin` atau `/admin/*`) → redirect ke login admin
- **Path participant** (`/participant` atau `/participant/*`) → redirect ke login peserta
- **Path lainnya** → redirect ke login peserta (default)

### Pesan Error
Setiap redirect menyertakan pesan flash session:
```
"Sesi Anda telah habis. Silakan login kembali."
```

Pesan ini ditampilkan di halaman login menggunakan alert Bootstrap yang sudah ada di:
- [`resources/views/auth/admin-login.blade.php`](file:///home/ypi/Documents/registrasi-toefl/registrasi-toefl-app/resources/views/auth/admin-login.blade.php#L13-L15)
- [`resources/views/auth/participant-login.blade.php`](file:///home/ypi/Documents/registrasi-toefl/registrasi-toefl-app/resources/views/auth/participant-login.blade.php#L13-L15)

## User Experience

### Sebelumnya
1. Session user habis
2. User melakukan action (submit form, click button, dll)
3. **Muncul halaman error "419 | Page Expired"**
4. User bingung dan harus manual ke halaman login

### Sekarang
1. Session user habis
2. User melakukan action (submit form, click button, dll)
3. **Otomatis redirect ke halaman login yang sesuai**
4. Muncul pesan informatif: "Sesi Anda telah habis. Silakan login kembali."
5. User langsung bisa login kembali

## Testing

Untuk menguji fitur ini:

1. Login sebagai admin atau participant
2. Tunggu hingga session habis (default: 120 menit), atau hapus session secara manual
3. Lakukan action apapun (submit form, klik link yang memerlukan CSRF token)
4. Verifikasi bahwa Anda di-redirect ke halaman login dengan pesan error

## Konfigurasi Session

Session Laravel dikonfigurasi di `config/session.php`. Setting yang relevan:
- `lifetime`: durasi session dalam menit (default: 120)
- `expire_on_close`: session expire ketika browser ditutup (default: false)

## Security

Penanganan session expiration ini meningkatkan keamanan dengan:
- Memaksa re-authentication setelah session habis
- Memberikan feedback yang jelas kepada user
- Mencegah confusion yang dapat menyebabkan security risks
