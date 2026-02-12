@extends('layouts.app')

@section('title', 'Server Error')

@section('content')
    <div class="container h-100 py-5">
        <div class="row justify-content-center align-items-center">
            <div class="col-md-8 col-lg-6 text-center">
                <div class="mb-4">
                    <i class="fas fa-server fa-5x text-danger opacity-50"></i>
                </div>
                <h1 class="display-4 fw-bold mb-3">500</h1>
                <h2 class="h3 mb-4">Terjadi Kesalahan Internal Server</h2>
                <p class="lead text-muted mb-5">
                    Sepertinya ada masalah teknis pada server kami.
                    Kami sedang berupaya memperbaikinya. Silakan muat ulang halaman atau coba lagi nanti.
                </p>
                <div class="d-flex justify-content-center gap-3">
                    <a href="{{ url()->previous() }}" class="btn btn-primary btn-lg rounded-pill px-5">
                        <i class="fas fa-arrow-left me-2"></i>Coba Lagi
                    </a>
                    <a href="{{ route('home') }}" class="btn btn-outline-secondary btn-lg rounded-pill px-5">
                        Halaman Utama
                    </a>
                </div>

                <div class="mt-5 p-4 bg-light rounded-4 text-start">
                    <h6 class="fw-bold mb-3"><i class="fas fa-tools me-2"></i>Langkah yang dapat Anda lakukan:</h6>
                    <ul class="mb-0 small text-muted">
                        <li>Muat ulang halaman ini (tekan F5).</li>
                        <li>Pastikan koneksi internet Anda stabil.</li>
                        <li>Jika masalah berlanjut, hubungi admin sistem dengan melampirkan tangkapan layar halaman ini.
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection