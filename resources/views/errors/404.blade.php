@extends('layouts.app')

@section('title', 'Halaman Tidak Ditemukan')

@section('content')
    <div class="container h-100 py-5">
        <div class="row justify-content-center align-items-center">
            <div class="col-md-8 col-lg-6 text-center">
                <div class="mb-4">
                    <i class="fas fa-map-signs fa-5x text-info opacity-50"></i>
                </div>
                <h1 class="display-4 fw-bold mb-3">404</h1>
                <h2 class="h3 mb-4">Halaman Tidak Ditemukan</h2>
                <p class="lead text-muted mb-5">
                    Halaman yang Anda cari tidak tersedia atau telah dipindahkan.
                    Silakan periksa kembali tautan Anda atau kembali ke halaman utama.
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
                    <h6 class="fw-bold mb-3"><i class="fas fa-compass me-2"></i>Bantuan Navigasi:</h6>
                    <div class="list-group list-group-flush bg-transparent">
                        <a href="{{ route('participant.login') }}"
                            class="list-group-item list-group-item-action bg-transparent border-0 px-0 py-1 text-primary">
                            <i class="fas fa-user me-2"></i>Login Peserta
                        </a>
                        <a href="{{ route('admin.login.form') }}"
                            class="list-group-item list-group-item-action bg-transparent border-0 px-0 py-1 text-primary">
                            <i class="fas fa-user-shield me-2"></i>Login Admin
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection