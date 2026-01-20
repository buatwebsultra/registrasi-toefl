---
description: Standar Keamanan Pengembangan (CSP & JavaScript)
---

Pedomani standar berikut dalam setiap pengembangan fitur baru atau perbaikan di aplikasi SIPENA untuk menjaga skor keamanan (audit) tetap tinggi.

### 1. Larangan JavaScript Inline
DILARANG menggunakan atribut event handler langsung di elemen HTML.
*   **Salah**: `<button onclick="doSomething()">`
*   **Benar**: `<button class="btn-action" data-id="123">` (Gunakan event listener di file script).

### 2. Larangan URL `javascript:`
DILARANG menggunakan `javascript:` di dalam atribut `href`.
*   **Salah**: `<a href="javascript:history.back()">`
*   **Benar**: `<a href="{{ url()->previous() }}">` atau menggunakan event listener dengan `e.preventDefault()`.

### 3. Penggunaan Nonce & Section Scripts
Setiap tag `<script>` HARUS menggunakan atribut `nonce` dan diletakkan di dalam `@section('scripts')` yang berada di LUAR `@section('content')`.

```blade
@section('content')
    <!-- HTML Content -->
@endsection

@section('scripts')
<script nonce="{{ $csp_nonce ?? '' }}">
    document.addEventListener('DOMContentLoaded', function() {
        // Logika JavaScript di sini
    });
</script>
@endsection
```

### 4. Komunikasi Data Blade ke JS
Gunakan atribut `data-*` untuk mengirim data dari PHP/Blade ke JavaScript.

```blade
<!-- Di Blade -->
<button class="btn-whatsapp" data-phone="{{ $participant->phone }}">Chat</button>

<!-- Di JavaScript -->
const phone = this.getAttribute('data-phone');
```

### 5. Dialog Konfirmasi
Gunakan class khusus (seperti `.btn-delete-confirm`) dan event listener untuk memicu dialog `confirm()`, bukan menuliskannya langsung di atribut `onclick`.
