<style nonce="{{ $csp_nonce ?? '' }}">
    .info-item:hover {
        transition: all 0.3s ease;
        transform: translateY(-2px);
        background-color: #f8f9fa;
        border-radius: 8px;
        padding: 8px;
    }

    .btn:hover {
        transition: all 0.2s ease;
        transform: translateY(-1px);
    }

    .card {
        transition: all 0.3s ease;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }

    .animate__animated {
        animation-duration: 0.5s;
    }
</style>

@extends('layouts.app')

@section('title', 'Foto Peserta')

@section('content')
<div class="container-fluid">
    <div class="row mb-4 animate__animated animate__fadeIn">
        <div class="col-12">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-md-center gap-3">
                <div>
                    <h1 class="display-6 mb-1">Foto Peserta</h1>
                    <p class="lead text-muted mb-0">{{ $participant->name }}</p>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary rounded-pill px-4 btn-hover-rise">
                        <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                    </a>
                    <a href="{{ route('admin.participant.details', $participant->id) }}" class="btn btn-primary rounded-pill px-4 btn-hover-rise">
                        <i class="fas fa-arrow-left me-2"></i>Kembali ke Detail
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4 animate__animated animate__fadeIn">
        <div class="col-md-12">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show rounded-4 animate__animated animate__fadeIn" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show rounded-4 animate__animated animate__fadeIn" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
        </div>
    </div>

    <!-- Main Photo Card with Information on Left Side -->
    <div class="animate__animated animate__fadeIn">
        <div class="card border-0 shadow-lg rounded-4">
            <div class="card-header bg-primary text-white rounded-top-4">
                <h5 class="mb-0"><i class="fas fa-user-circle me-2"></i>Foto Peserta</h5>
            </div>
            <div class="card-body">
                <div class="two-column-container" style="display: grid; grid-template-columns: 4fr 1fr; gap: 1.5rem;">
                    <!-- First Column: Information Section -->
                    <div class="info-column">
                        <!-- Personal Information Section -->
                        <div class="section-header mb-4">
                            <h6 class="text-center">
                                <span class="d-inline-block bg-primary text-white px-4 py-2 rounded-pill">
                                    <i class="fas fa-user me-2"></i>INFORMASI PRIBADI
                                </span>
                            </h6>
                        </div>

                        <div class="row g-4 mb-4">
                            <div class="col-md-6">
                                <div class="info-item mb-3 p-2 rounded">
                                    <label class="fw-bold text-muted">Nomor Kursi</label>
                                    <div class="fw-bold fs-5 text-primary">{{ $participant->seat_number ?: 'Belum ditetapkan' }}</div>
                                </div>
                                <div class="info-item mb-3 p-2 rounded">
                                    <label class="fw-bold text-muted">NIM</label>
                                    <div class="fw-bold">{{ $participant->nim }}</div>
                                </div>
                                <div class="info-item mb-3 p-2 rounded">
                                    <label class="fw-bold text-muted">Nama</label>
                                    <div class="fw-bold">{{ $participant->name }}</div>
                                </div>
                                <div class="info-item mb-3 p-2 rounded">
                                    <label class="fw-bold text-muted">Username</label>
                                    <div class="fw-bold">{{ $participant->username }}</div>
                                </div>
                                <div class="info-item mb-3 p-2 rounded">
                                    <label class="fw-bold text-muted">Email</label>
                                    <div class="fw-bold text-break">{{ $participant->email }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-item mb-3 p-2 rounded">
                                    <label class="fw-bold text-muted">Jurusan</label>
                                    <div class="fw-bold">
                                        {{ $participant->studyProgram->name }} {{ $participant->studyProgram->level }}
                                    </div>
                                </div>
                                <div class="info-item mb-3 p-2 rounded">
                                    <label class="fw-bold text-muted">Fakultas</label>
                                    <div class="fw-bold">{{ $participant->faculty }}</div>
                                </div>
                                <div class="info-item mb-3 p-2 rounded">
                                    <label class="fw-bold text-muted">Jenis Kelamin</label>
                                    <div class="fw-bold">{{ $participant->gender == 'male' ? 'Laki-laki' : 'Perempuan' }}</div>
                                </div>
                                <div class="info-item mb-3 p-2 rounded">
                                    <label class="fw-bold text-muted">Tempat & Tanggal Lahir</label>
                                    <div class="fw-bold">{{ $participant->birth_place }}, {{ $participant->birth_date->format('d M Y') }}</div>
                                </div>
                                <div class="info-item mb-3 p-2 rounded">
                                    <label class="fw-bold text-muted">Nomor WhatsApp</label>
                                    <div class="fw-bold text-break">{{ $participant->phone }}</div>
                                </div>
                                <div class="info-item mb-3 p-2 rounded">
                                    <label class="fw-bold text-muted">Tanggal Pembayaran</label>
                                    <div class="fw-bold">{{ $participant->payment_date ? $participant->payment_date->format('d M Y') : '-' }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Test Information Section as Modal Button -->
                        <div class="section-header mb-4">
                            <h6 class="text-center">
                                <button type="button" class="btn btn-outline-info px-4 py-2 rounded-3" data-bs-toggle="modal" data-bs-target="#testInfoModal">
                                    <i class="fas fa-clipboard-check me-2"></i>Lihat Informasi Tes
                                </button>
                            </h6>
                        </div>
                    </div>

                    <!-- Second Column: Photo and Action Section -->
                    <div class="photo-action-column">
                        <!-- Photo Section -->
                        <div class="text-center mb-4">
                            @if($participant->photo_path && (\Storage::disk('private')->exists($participant->photo_path) || \Storage::disk('public')->exists($participant->photo_path)))
                                <img src="{{ route('participant.file.download', ['id' => $participant->id, 'type' => 'photo']) }}"
                                      alt="Foto Peserta"
                                      class="img-fluid rounded-3 shadow-sm border border-2"
                                      style="width: 151px; height: 227px; object-fit: cover; object-position: center;">
                            @else
                                <div class="bg-light d-flex align-items-center justify-content-center border border-2 rounded-3"
                                     style="width: 151px; height: 227px; margin: 0 auto; border-style: dashed !important;">
                                    <span class="text-muted">Foto tidak tersedia</span>
                                </div>
                            @endif
                        </div>

                        <!-- Action Section -->
                        <div class="card border-0 shadow-sm rounded-4">
                            <div class="card-header bg-primary text-white py-3 rounded-top-4">
                                <h6 class="mb-0"><i class="fas fa-cogs me-2"></i>Aksi</h6>
                            </div>
                            <div class="card-body p-3">
                                <div class="d-grid gap-3">
                                    @if(Auth::user()->isAdmin())
                                    <a href="{{ route('admin.participant.card.preview', $participant->id) }}" class="btn btn-outline-primary rounded-4 py-2 fw-bold" target="_blank">
                                        <i class="fas fa-eye me-2"></i>Pratinjau Kartu
                                    </a>
                                    @endif

                                    <!-- Action Buttons Grid -->
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <a href="{{ route('admin.participants.list', $participant->schedule_id) }}" class="btn btn-outline-secondary rounded-4 w-100 py-2 fw-bold">
                                                <i class="fas fa-arrow-left me-1"></i> Kembali
                                            </a>
                                        </div>
                                        <div class="col-6">
                                            <button type="button" class="btn btn-warning rounded-4 w-100 py-2 fw-bold text-dark" data-bs-toggle="modal" data-bs-target="#resetPasswordModal">
                                                <i class="fas fa-key me-1"></i> Reset
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Delete Button -->
                                    <form action="{{ route('admin.participant.delete', $participant->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger rounded-4 w-100 py-2 fw-bold" onclick="return confirm('Apakah Anda yakin ingin menghapus peserta ini?')">
                                            <i class="fas fa-trash-alt me-2"></i>Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@section('modals')
<!-- Reset Password Modal -->
<div class="modal fade" id="resetPasswordModal" tabindex="-1" aria-labelledby="resetPasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-warning text-dark rounded-top-4">
                <h5 class="modal-title" id="resetPasswordModalLabel">Reset Kata Sandi Peserta - {{ $participant->name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.reset.participant.password') }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    @if($errors->any())
                        <div class="alert alert-danger rounded-3 mb-4">
                            <ul class="mb-0 small">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <input type="hidden" name="participant_id" value="{{ $participant->id }}">
                    <div class="mb-4">
                        <label class="form-label fw-bold">Nama Pengguna:</label>
                        <p class="form-control-plaintext bg-light border border-2 rounded-3 p-3">{{ $participant->username }}</p>
                    </div>
                    <div class="mb-3">
                        <label for="new_password" class="form-label fw-bold">Kata Sandi Baru</label>
                        <input type="password" class="form-control rounded-3" id="new_password" name="new_password" required>
                    </div>
                    <div class="mb-3">
                        <label for="new_password_confirmation" class="form-label fw-bold">Konfirmasi Kata Sandi Baru</label>
                        <input type="password" class="form-control rounded-3" id="new_password_confirmation" name="new_password_confirmation" required>
                    </div>
                    <div class="alert alert-warning rounded-3">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <small>Peserta perlu menggunakan kata sandi baru ini untuk login berikutnya.</small>
                    </div>
                </div>
                <div class="modal-footer bg-light rounded-bottom-4">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning rounded-pill px-4">Reset Kata Sandi</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Test Information Modal -->
<div class="modal fade" id="testInfoModal" tabindex="-1" aria-labelledby="testInfoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header" style="background-color: #99f6e4;">
                <h5 class="modal-title fw-bold" id="testInfoModalLabel">
                    <i class="fas fa-clipboard-check me-2"></i>INFORMASI TES
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="info-item mb-3 p-3 rounded bg-light">
                            <label class="fw-bold text-muted">Kategori Tes</label>
                            <div class="fw-bold fs-5">{{ $participant->test_category }}</div>
                        </div>
                        <div class="info-item mb-3 p-3 rounded bg-light">
                            <label class="fw-bold text-muted">Jadwal Tes</label>
                            <div class="fw-bold fs-5">{{ $participant->schedule->room }} - {{ $participant->schedule->date->format('d M Y') }}</div>
                        </div>
                        <div class="info-item mb-3 p-3 rounded bg-light">
                            <label class="fw-bold text-muted">Tanggal Pendaftaran</label>
                            <div class="fw-bold fs-5">{{ $participant->created_at->format('d M Y H:i') }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-item mb-3 p-3 rounded bg-light">
                            <label class="fw-bold text-muted">Status</label>
                            <div>
                                <span class="badge bg-{{ $participant->status === 'confirmed' ? 'success' : ($participant->status === 'pending' ? 'warning' : 'danger') }} px-4 py-2 fs-5 rounded-pill">
                                    {{ $participant->status === 'confirmed' ? 'Terkonfirmasi' : ($participant->status === 'pending' ? 'Tertunda' : 'Dibatalkan') }}
                                </span>
                            </div>
                        </div>
                        <div class="info-item mb-3 p-3 rounded bg-light">
                            <label class="fw-bold text-muted">Nilai Test</label>
                            <div class="fw-bold fs-5">
                                @if($participant->test_score)
                                    {{ $participant->test_score }}
                                @else
                                    <span class="text-muted">Belum dinilai</span>
                                @endif
                            </div>
                        </div>
                        <div class="info-item mb-3 p-3 rounded bg-light">
                            <label class="fw-bold text-muted">Tanggal Test</label>
                            <div class="fw-bold fs-5">
                                @if($participant->test_date)
                                    {{ $participant->test_date->format('d M Y') }}
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- Tambahkan library Animate.css jika belum ada -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

<script nonce="{{ $csp_nonce ?? '' }}">
    @if($errors->any())
    document.addEventListener('DOMContentLoaded', function() {
        var modalEl = document.getElementById('resetPasswordModal');
        if (modalEl) {
            var myModal = new bootstrap.Modal(modalEl);
            myModal.show();
        }
    });
    @endif
</script>
@endsection

@endsection