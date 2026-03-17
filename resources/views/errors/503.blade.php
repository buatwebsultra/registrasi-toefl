@extends('layouts.app')

@section('title', 'Layanan Tidak Tersedia')

@section('content')
    <div class="container h-100 py-5">
        <div class="row justify-content-center align-items-center">
            <div class="col-md-8 col-lg-6 text-center">
                <div class="mb-4">
                    <i class="fas fa-tools fa-5x text-secondary opacity-50"></i>
                </div>
                <h1 class="display-4 fw-bold mb-3">503</h1>
                <h2 class="h3 mb-4">Sistem Dalam Pemeliharaan</h2>
                <p class="lead text-muted mb-5">
                    Maaf atas ketidaknyamanannya. Saat ini sistem sedang dalam proses pemeliharaan berkala atau sedang
                    mengalami beban tinggi. Silakan coba beberapa saat lagi.
                </p>
                <div class="d-flex justify-content-center gap-3">
                    <button onclick="window.location.reload()" class="btn btn-primary btn-lg rounded-pill px-5">
                        <i class="fas fa-sync-alt me-2"></i>Segarkan Halaman / Refresh
                    </button>
                    <a href="{{ url('/') }}" class="btn btn-outline-secondary btn-lg rounded-pill px-5">
                        Beranda
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection