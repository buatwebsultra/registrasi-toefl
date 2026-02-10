@extends('layouts.app')

@section('title', 'Sesi Berakhir')

@section('content')
    <div class="container h-100 py-5">
        <div class="row justify-content-center align-items-center">
            <div class="col-md-8 col-lg-6 text-center">
                <div class="mb-4">
                    <i class="fas fa-history fa-5x text-warning opacity-50"></i>
                </div>
                <h1 class="display-4 fw-bold mb-3">419</h1>
                <h2 class="h3 mb-4">Sesi Kamu Sudah Habis, Bro!</h2>
                <p class="lead text-muted mb-5">
                    Halamannya sudah "basi" karena terlalu lama didiamkan.
                    Demi keamanan, kamu perlu refresh halaman atau login ulang untuk lanjut.
                </p>
                <div class="d-flex justify-content-center gap-3">
                    <a href="{{ url()->previous() }}" class="btn btn-primary btn-lg rounded-pill px-5">
                        <i class="fas fa-sync me-2"></i>Refresh Halaman
                    </a>
                    <a href="{{ route('home') }}" class="btn btn-outline-secondary btn-lg rounded-pill px-5">
                        Halaman Utama
                    </a>
                </div>

                <div class="mt-5 p-4 bg-light rounded-4 text-start">
                    <h6 class="fw-bold mb-3"><i class="fas fa-lightbulb me-2"></i>Kenapa ini terjadi?</h6>
                    <ul class="mb-0 small text-muted">
                        <li>Kamu mendiamkan halaman ini terlalu lama (CSRF Token Expired).</li>
                        <li>Kamu membuka tab baru dan logout di sana.</li>
                        <li>Silakan login kembali untuk melanjutkan aktivitasmu.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection