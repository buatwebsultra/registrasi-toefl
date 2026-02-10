@extends('layouts.app')

@section('title', 'File Terlalu Besar')

@section('content')
    <div class="container h-100 py-5">
        <div class="row justify-content-center align-items-center">
            <div class="col-md-8 col-lg-6 text-center">
                <div class="mb-4">
                    <i class="fas fa-file-export fa-5x text-warning opacity-50"></i>
                </div>
                <h1 class="display-4 fw-bold mb-3">413</h1>
                <h2 class="h3 mb-4">File Terlalu Besar!</h2>
                <p class="lead text-muted mb-5">
                    Maaf bro, file yang kamu upload kegedean buat server kita.
                    Batas maksimal upload per file adalah <strong>2MB</strong>, dan total kiriman maksimal
                    <strong>5MB</strong>.
                </p>
                <div class="d-flex justify-content-center gap-3">
                    <a href="{{ url()->previous() }}" class="btn btn-primary btn-lg rounded-pill px-5">
                        <i class="fas fa-arrow-left me-2"></i>Kembali & Coba Lagi
                    </a>
                    <a href="{{ route('home') }}" class="btn btn-outline-secondary btn-lg rounded-pill px-5">
                        Halaman Utama
                    </a>
                </div>

                <div class="mt-5 p-4 bg-light rounded-4 text-start">
                    <h6 class="fw-bold mb-3"><i class="fas fa-info-circle me-2"></i>Tips buat kamu:</h6>
                    <ul class="mb-0 small text-muted">
                        <li>Gunakan format <strong>JPG</strong> atau <strong>PNG</strong>.</li>
                        <li>Kompres gambar kamu sebelum diupload (banyak tool online gratis kok).</li>
                        <li>Pastikan ukuran masing-masing file tidak lebih dari <strong>2MB</strong>.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection