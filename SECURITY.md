# Security Enhancements Documentation

## Overview

Dokumen ini menjelaskan security enhancements yang telah diimplementasikan dalam aplikasi registrasi TOEFL untuk meningkatkan keamanan di production environment.

## Implemented Security Features

### 1. Session Security Configuration

File: `config/session.php`

Konfigurasi session yang aman:
- **Encryption**: Session data dienkripsi (`encrypt = true`)
- **HTTPS Only**: Cookie hanya dikirim via HTTPS (`secure = true`)
- **HTTP Only**: Cookie tidak bisa diakses JavaScript (`http_only = true`)
- **SameSite Policy**: Strict same-site policy untuk mencegah CSRF (`same_site = strict`)
- **Session Lifetime**: 60 menit (dapat disesuaikan via `SESSION_LIFETIME`)
- **Expire on Close**: Session otomatis expire saat browser ditutup

### 2. Authentication Security

File: `app/Http/Controllers/AuthController.php`

Fitur keamanan pada autentikasi:
- **Constant-time password comparison**: Mencegah timing attacks
- **Random delay on failed login**: 100-300ms untuk mencegah timing enumeration
- **Rate limiting**: 5 login attempts per menit
- **Session regeneration**: Session ID di-regenerate setelah login sukses
- **Security logging**: Semua login attempts di-log dengan IP dan user agent

### 3. Authorization & Access Control

Files: 
- `app/Http/Middleware/AdminMiddleware.php`
- `app/Http/Middleware/ParticipantMiddleware.php`

Kontrol akses yang ketat:
- **IDOR Protection**: Semua route parameters diverifikasi dengan session
- **Role-based access**: Admin dan participant memiliki middleware terpisah
- **Automatic logout**: User di-logout jika role tidak sesuai
- **Logging**: IDOR attempts di-log dengan detail lengkap

### 4. Mass Assignment Protection

File: `app/Models/Participant.php`

Perlindungan terhadap mass assignment:
- **Guarded fields**: Field kritis dilindungi dengan `$guarded`
- **Admin-only fields**: Field tertentu hanya bisa diupdate admin
- **System-only fields**: Field sistem tidak bisa diupdate manual
- **Safe update methods**: `safeParticipantUpdate()` dan `safeAdminUpdate()`

### 5. Rate Limiting

File: `routes/web.php`

Rate limiting pada endpoints sensitif:
- **Login**: 5 attempts/minute
- **Registration**: 10 attempts/minute
- **Retake**: 5 attempts/minute
- **Resubmit Payment**: 3 attempts/minute

### 6. Security Headers

File: `app/Http/Middleware/SecurityHeaders.php`

Security headers yang ditambahkan ke semua response:
- **X-Frame-Options**: `SAMEORIGIN` - Mencegah clickjacking
- **X-Content-Type-Options**: `nosniff` - Mencegah MIME sniffing
- **X-XSS-Protection**: `1; mode=block` - XSS filter browser
- **Strict-Transport-Security**: `max-age=31536000` - Force HTTPS
- **Referrer-Policy**: `strict-origin-when-cross-origin` - Kontrol referrer
- **Content-Security-Policy**: Mengontrol sumber resource. Diupdate untuk mengizinkan:
  - Font Awesome & Google Fonts (CDN)
  - Vite Development Server (localhost:5173)
  - Inline scripts/styles (unsafe-inline)
- **Permissions-Policy**: Disable fitur browser yang tidak diperlukan

### 7. File Access Control

Features:
- **Private storage disk**: File sensitif disimpan di `storage/app/private/`
- **Authorization checks**: Semua file download memerlukan authorization
- **Storage directory protection**: `.htaccess` mencegah direct web access
- **Secure file serving**: File disajikan melalui controller dengan authorization

### 8. CSRF Protection

Laravel built-in CSRF protection aktif untuk semua form submissions melalui `VerifyCsrfToken` middleware.

### 9. Input Validation & Sanitization

Semua user input divalidasi dan disanitasi:
- **Validation rules**: Setiap form memiliki validation rules
- **Input sanitization**: `trim()` dan `strip_tags()` pada input text
- **SQL Injection protection**: Eloquent ORM mencegah SQL injection
- **XSS protection**: Blade templates auto-escape output

## Environment Configuration

File: `.env.example`

Environment variables untuk production:

```bash
# Application
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

# Session Security
SESSION_DRIVER=database
SESSION_LIFETIME=60
SESSION_ENCRYPT=true
SESSION_SECURE_COOKIE=true
SESSION_SAME_SITE=strict
SESSION_HTTP_ONLY=true
```

## Deployment Checklist

Sebelum deploy ke production, pastikan:

- [ ] `APP_ENV=production`
- [ ] `APP_DEBUG=false`
- [ ] `APP_URL` sesuai dengan domain production
- [ ] Database credentials sudah benar
- [ ] `SESSION_DRIVER=database` dan jalankan `php artisan session:table` migration
- [ ] Semua session security settings aktif
- [ ] HTTPS enabled (untuk HSTS header)
- [ ] Storage directory tidak accessible dari web
- [ ] File permissions sudah benar (`storage/` dan `bootstrap/cache/` writable)
- [ ] `php artisan config:cache` untuk optimize configuration
- [ ] `php artisan route:cache` untuk optimize routes
- [ ] `php artisan view:cache` untuk optimize views

## Testing Security

### Manual Testing

1. **Test Storage Access**
```bash
# Pastikan URL ini return 403 Forbidden
curl https://yourdomain.com/storage/app/private/
```

2. **Test Security Headers**
```bash
# Check semua security headers ada
curl -I https://yourdomain.com
```

3. **Test IDOR Protection**
- Login sebagai participant
- Coba akses data participant lain
- Harus dapat 403 Forbidden

4. **Test Rate Limiting**
- Coba login 6x dalam 1 menit dengan credentials salah
- Request ke-6 harus dapat error rate limit

### Automated Testing

File security tests tersedia di `tests/` directory.

## Maintenance

### Monitoring

Monitor security logs untuk:
- Failed login attempts
- IDOR attempts
- Rate limit hits
- Unusual access patterns

Logs tersimpan di `storage/logs/laravel.log`

### Regular Updates

- Update Laravel dan dependencies secara berkala
- Follow Laravel security advisories
- Review dan update CSP policy sesuai kebutuhan
- Rotate APP_KEY jika terjadi security breach

## Additional Resources

- [Laravel Security Best Practices](https://laravel.com/docs/security)
- [OWASP Top 10](https://owasp.org/www-project-top-ten/)
- [Content Security Policy Reference](https://developer.mozilla.org/en-US/docs/Web/HTTP/CSP)

## Support

Untuk pertanyaan security, hubungi security team atau review dokumentasi Laravel security.
