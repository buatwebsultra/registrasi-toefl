@extends('layouts.app')

@section('title', 'Terlalu Banyak Permintaan')

@section('content')
    <div class="container h-100 py-5">
        <div class="row justify-content-center align-items-center">
            <div class="col-md-8 col-lg-6 text-center">
                <div class="mb-4">
                    <i class="fas fa-hourglass-half fa-5x text-warning opacity-50"></i>
                </div>
                <h1 class="display-4 fw-bold mb-3">429</h1>
                <h2 class="h3 mb-4">Terlalu Banyak Permintaan</h2>
                <p class="lead text-muted mb-5">
                    Melakukan terlalu banyak permintaan dalam waktu singkat.
                    Silakan tunggu beberapa saat sebelum mencoba kembali untuk menjaga keamanan sistem.
                </p>
                <div class="d-flex justify-content-center gap-3">
                    <a href="{{ url()->previous() }}" class="btn btn-primary btn-lg rounded-pill px-5">
                        <i class="fas fa-arrow-left me-2"></i>Kembali
                    </a>
                    <a href="{{ url('/') }}" class="btn btn-outline-secondary btn-lg rounded-pill px-5">
                        Beranda
                    </a>
                </div>

                <div class="mt-5 p-4 bg-light rounded-4 text-start">
                    <h6 class="fw-bold mb-3"><i class="fas fa-info-circle me-2"></i>Mengapa ini terjadi?</h6>
                    <p class="text-muted small mb-0">
                        Membatasi jumlah percobaan akses atau login dalam 1 waktu. Pembatasan ini bersifat sementara.
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection