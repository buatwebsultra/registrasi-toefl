@extends('layouts.app')

@section('title', 'Ukuran Berkas Terlalu Besar - 413')

@section('content')
<div class="container py-5 mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="card-body p-5">
                    <div class="mb-4">
                        <div class="bg-danger bg-opacity-10 text-danger rounded-circle d-inline-flex align-items-center justify-content-center mb-4" style="width: 100px; height: 100px;">
                            <i class="fas fa-file-export fa-4x"></i>
                        </div>
                        <h1 class="display-4 fw-bold text-dark mb-2">413</h1>
                        <h2 class="h3 fw-bold text-muted mb-4">Berkas Terlalu Besar</h2>
                    </div>
                    
                    <p class="lead text-muted mb-5">
                        Maaf, berkas yang Anda unggah secara keseluruhan melebihi batas maksimal yang diizinkan oleh server kami. <br>
                        Pastikan total ukuran semua file yang diunggah tidak melebihi <strong>5 MB</strong>.
                    </p>
                    
                    <div class="d-flex justify-content-center gap-3">
                        <button onclick="window.history.back()" class="btn btn-outline-secondary rounded-pill px-4 py-2">
                            <i class="fas fa-arrow-left me-2"></i>Kembali
                        </button>
                        <a href="{{ route('home') }}" class="btn btn-primary rounded-pill px-4 py-2 shadow">
                            <i class="fas fa-home me-2"></i>Beranda
                        </a>
                    </div>
                </div>
                <div class="card-footer bg-light border-0 py-3">
                    <small class="text-muted">TOEFL Registration System &copy; {{ date('Y') }}</small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
