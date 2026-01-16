# Update Tanda Tangan Sertifikat

## Perubahan yang Dilakukan
**Tanggal:** 28 Desember 2025

### File yang Diperbarui
- **File Signature:** [`public/signature.png`](file:///home/ypi/Documents/registrasi-toefl/registrasi-toefl-app/public/signature.png)
- **Ukuran File:** 208KB

### Detail
Tanda tangan pada sertifikat TOEFL telah diperbarui dengan file signature baru tanpa mengubah struktur konten sertifikat.

### Implementasi
File signature digunakan dalam certificate template di:
- [`resources/views/participant/certificate.blade.php`](file:///home/ypi/Documents/registrasi-toefl/registrasi-toefl-app/resources/views/participant/certificate.blade.php#L350)

Signature di-embed menggunakan base64 encoding:
```php
<img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('signature.png'))) }}" 
     alt="Signature" 
     style="position: absolute; width: 120px; height: auto; left: 50%; transform: translateX(-50%); top: 30px; z-index: 10;">
```

### Verifikasi
Untuk memverifikasi perubahan:
1. Login sebagai peserta yang sudah PASS
2. Unduh sertifikat dari dashboard
3. Periksa tanda tangan di sertifikat PDF

### Catatan
- Struktur konten sertifikat tidak berubah
- Hanya file signature.png yang diperbarui
- Perubahan langsung terlihat pada sertifikat yang digenerate selanjutnya
