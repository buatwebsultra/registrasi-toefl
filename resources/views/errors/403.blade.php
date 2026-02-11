@extends('layouts.app')

@section('title', 'Akses Ditolak')

@section('content')
    <div class="container h-100 py-5">
        <div class="row justify-content-center align-items-center">
            <div class="col-md-8 col-lg-6 text-center">
                <div class="mb-4">
                    <i class="fas fa-user-lock fa-5x text-danger opacity-50"></i>
                </div>
                <h1 class="display-4 fw-bold mb-3">403</h1>
                <h2 class="h3 mb-4">Mohon Maaf, Akses Terbatas</h2>
                <p class="lead text-muted mb-5">
                    Anda tidak memiliki izin untuk mengakses halaman ini. Hal ini mungkin terjadi karena hak akses akun
                    Anda tidak mencukupi atau sesi login Anda telah berakhir. Silakan hubungi Administrator jika Anda
                    merasa seharusnya memiliki akses.
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
                    <h6 class="fw-bold mb-3"><i class="fas fa-shield-alt me-2"></i>Informasi Keamanan:</h6>
                    <p class="small text-muted mb-0">
                        Sistem mencatat seluruh aktivitas untuk menjaga keamanan data. Pastikan Anda masuk menggunakan
                        akun dengan wewenang yang sesuai.
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection