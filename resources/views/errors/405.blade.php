@extends('layouts.app')

@section('title', 'Metode Tidak Diizinkan')

@section('content')
    <div class="container h-100 py-5">
        <div class="row justify-content-center align-items-center">
            <div class="col-md-8 col-lg-6 text-center">
                <div class="mb-4">
                    <i class="fas fa-exclamation-circle fa-5x text-danger opacity-50"></i>
                </div>
                <h1 class="display-4 fw-bold mb-3">405</h1>
                <h2 class="h3 mb-4">Metode Tidak Diizinkan</h2>
                <p class="lead text-muted mb-5">
                    Maaf, permintaan Anda tidak dapat diproses karena kesalahan metode akses.
                    Silakan kembali ke halaman sebelumnya dan coba muat ulang halaman.
                </p>
                <div class="d-flex justify-content-center gap-3">
                    <a href="{{ url()->previous() }}" class="btn btn-primary btn-lg rounded-pill px-5">
                        <i class="fas fa-arrow-left me-2"></i>Kembali
                    </a>
                    <a href="{{ route('home') }}" class="btn btn-outline-secondary btn-lg rounded-pill px-5">
                        Halaman Utama
                    </a>
                </div>

                <div class="mt-5 p-4 bg-light rounded-4 text-start">
                    <h6 class="fw-bold mb-3"><i class="fas fa-info-circle me-2"></i>Saran Perbaikan:</h6>
                    <ul class="mb-0 small text-muted">
                        <li>Pastikan Anda tidak menekan tombol 'Back' setelah mengirimkan formulir.</li>
                        <li>Coba segarkan (refresh) halaman pendaftaran atau dashboard Anda.</li>
                        <li>Hubungi admin jika masalah ini tetap berlanjut.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection