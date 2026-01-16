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

@section('title', 'Detail Peserta')

@section('content')
<div class="container-fluid">
    <div class="row mb-4 animate__animated animate__fadeIn">
        <div class="col-12">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-md-center gap-3">
                <div>
                    <h1 class="display-6 mb-1">Detail Peserta</h1>
                    <p class="lead text-muted mb-0">{{ $participant->name }}</p>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary rounded-pill px-4 btn-hover-rise">
                        <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                    </a>
                    <a href="javascript:history.back()" class="btn btn-primary rounded-pill px-4 btn-hover-rise">
                        <i class="fas fa-arrow-left me-2"></i>Kembali
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

    <!-- Main Information Card with Two Column Layout -->
    <div class="animate__animated animate__fadeIn">
        <div class="card border-0 shadow-lg rounded-4">
            <div class="card-header bg-primary text-white rounded-top-4">
                <h5 class="mb-0"><i class="fas fa-user-circle me-2"></i>Informasi Peserta</h5>
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
                                    <label class="fw-bold text-muted">Status Kehadiran</label>
                                    <div class="fw-bold">
                                        @if($participant->attendance === 'present')
                                            <span class="badge bg-success">Hadir</span>
                                        @elseif($participant->attendance === 'absent')
                                            <span class="badge bg-danger">Tidak Hadir</span>
                                        @elseif($participant->attendance === 'permission')
                                            <span class="badge bg-warning text-dark">Izin</span>
                                        @else
                                            <span class="badge bg-secondary">-</span>
                                        @endif
                                    </div>
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
                                    <div class="fw-bold text-break">
                                        <a href="javascript:void(0)"
                                           onclick="sendWhatsApp('{{ $participant->phone }}')"
                                           class="text-decoration-none"
                                           title="Kirim pesan WhatsApp ke peserta ini">
                                            <i class="fab fa-whatsapp text-success me-1"></i>{{ $participant->phone }}
                                            <i class="fas fa-external-link-alt ms-1 text-muted"></i>
                                        </a>
                                    </div>
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
                            @if($participant->photo_url)
                                <img src="{{ $participant->photo_url }}"
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
                        <div class="card border-0 shadow-sm rounded-3">
                            <div class="card-header bg-light py-2">
                                <h6 class="mb-0"><i class="fas fa-cogs me-1"></i>Aksi</h6>
                            </div>
                            <div class="card-body p-2">
                                <div class="d-grid gap-2">
                                    @if(Auth::user()->isAdmin())
                                    <button type="button" class="btn btn-info text-white rounded-3 py-2 btn-hover-rise" data-bs-toggle="modal" data-bs-target="#cardPreviewModal">
                                        <i class="fas fa-eye me-1"></i>Pratinjau Kartu
                                    </button>
                                    @endif

                                    @if($participant->test_score)
                                    <a href="{{ route('admin.participant.certificate.download', $participant->id) }}" class="btn btn-success rounded-3 py-2 btn-hover-rise">
                                        <i class="fas fa-certificate me-1"></i>Unduh Sertifikat
                                    </a>
                                    
                                    @if(!$participant->is_score_validated)
                                    <form action="{{ route('admin.participant.score.validate', $participant->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-primary rounded-3 w-100 py-2 btn-hover-rise">
                                            <i class="fas fa-check-double me-1"></i>Validasi Nilai
                                        </button>
                                    </form>
                                    @endif
                                    @endif

                                    @if(Auth::user()->isOperator())
                                    <!-- Action Buttons Grid -->
                                    <div class="row g-1">
                                        <div class="col-6">
                                            <a href="{{ route('admin.participants.list', $participant->schedule_id) }}" class="btn btn-outline-secondary rounded-3 w-100 py-1 btn-hover-rise">
                                                <i class="fas fa-arrow-left me-1"></i> Kembali
                                            </a>
                                        </div>
                                        <div class="col-6">
                                            <button type="button" class="btn btn-warning rounded-3 w-100 py-1 btn-hover-rise" data-bs-toggle="modal" data-bs-target="#resetPasswordModal">
                                                <i class="fas fa-key me-1"></i> Reset
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Delete Button -->
                                    <form action="{{ route('admin.participant.delete', $participant->id) }}" method="POST" class="mt-1">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger rounded-3 w-100 py-2 btn-hover-rise" onclick="return confirm('Apakah Anda yakin ingin menghapus peserta ini?')">
                                            <i class="fas fa-trash-alt me-1"></i>Hapus
                                        </button>
                                    </form>
                                    @else
                                    <a href="{{ route('admin.participants.list', $participant->schedule_id) }}" class="btn btn-outline-secondary rounded-3 w-100 py-2 btn-hover-rise">
                                        <i class="fas fa-arrow-left me-2"></i>Kembali ke Daftar
                                    </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style nonce="{{ $csp_nonce ?? '' }}">
        @media (max-width: 768px) {
            .two-column-container {
                grid-template-columns: 1fr !important;
            }

            .photo-action-column {
                order: -1; /* Let photo appear first on mobile */
            }
        }
    </style>

    <!-- Documents Section (Payment Proof & KTP) -->
    <div class="animate__animated animate__fadeIn mt-4">
        <div class="card border-0 shadow-lg rounded-4">
            <div class="card-header bg-info text-white rounded-top-4">
                <h5 class="mb-0"><i class="fas fa-file-alt me-2"></i>Dokumen Peserta</h5>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <!-- Payment Proof Section -->
                    <div class="col-md-6">
                        <div class="text-center">
                            <h6 class="fw-bold mb-3"><i class="fas fa-receipt me-2"></i>Bukti Pembayaran</h6>
                            @if($participant->payment_proof_path && (\Storage::disk('private')->exists($participant->payment_proof_path) || \Storage::disk('public')->exists($participant->payment_proof_path)))
                                <a href="{{ route('participant.file.download', ['id' => $participant->id, 'type' => 'payment_proof']) }}" target="_blank" class="d-block">
                                    <img src="{{ route('participant.file.download', ['id' => $participant->id, 'type' => 'payment_proof']) }}" 
                                         alt="Bukti Pembayaran" 
                                         class="img-thumbnail rounded shadow-sm" 
                                         style="max-height: 300px; width: auto; object-fit: cover;">
                                </a>
                                <div class="mt-2">
                                    <a href="{{ route('participant.file.download', ['id' => $participant->id, 'type' => 'payment_proof']) }}" 
                                       target="_blank" 
                                       class="btn btn-sm btn-outline-primary rounded-pill">
                                        <i class="fas fa-external-link-alt me-1"></i>Buka di Tab Baru
                                    </a>
                                </div>
                            @else
                                <div class="bg-light d-flex align-items-center justify-content-center border border-2 rounded-3" 
                                     style="height: 300px; border-style: dashed !important;">
                                    <span class="text-muted"><i class="fas fa-file-image me-2"></i>Bukti pembayaran tidak tersedia</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- KTP Section -->
                    <div class="col-md-6">
                        <div class="text-center">
                            <h6 class="fw-bold mb-3"><i class="fas fa-id-card me-2"></i>KTP</h6>
                            @if($participant->ktp_path && (\Storage::disk('private')->exists($participant->ktp_path) || \Storage::disk('public')->exists($participant->ktp_path)))
                                <a href="{{ route('participant.file.download', ['id' => $participant->id, 'type' => 'ktp']) }}" target="_blank" class="d-block">
                                    <img src="{{ route('participant.file.download', ['id' => $participant->id, 'type' => 'ktp']) }}" 
                                         alt="KTP" 
                                         class="img-thumbnail rounded shadow-sm" 
                                         style="max-height: 300px; width: auto; object-fit: cover;">
                                </a>
                                <div class="mt-2">
                                    <a href="{{ route('participant.file.download', ['id' => $participant->id, 'type' => 'ktp']) }}" 
                                       target="_blank" 
                                       class="btn btn-sm btn-outline-primary rounded-pill">
                                        <i class="fas fa-external-link-alt me-1"></i>Buka di Tab Baru
                                    </a>
                                </div>
                            @else
                                <div class="bg-light d-flex align-items-center justify-content-center border border-2 rounded-3" 
                                     style="height: 300px; border-style: dashed !important;">
                                    <span class="text-muted"><i class="fas fa-id-card me-2"></i>KTP tidak tersedia</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Score Input Card -->
    <div class="animate__animated animate__fadeIn mt-4">
        @if(Auth::user()->isAdmin())
        @if($participant->attendance === 'present')
        <div class="card border-0 shadow-lg rounded-4">
            <div class="card-header bg-success text-white rounded-top-4">
                <h5 class="mb-0"><i class="fas fa-graduation-cap me-2"></i>Input Nilai TOEFL - {{ $participant->name }} ({{ $participant->nim }})</h5>
            </div>
            <div class="card-body">
        @elseif($participant->attendance === 'absent')
            <div class="alert alert-danger shadow-sm rounded-4">
                <h4 class="alert-heading"><i class="fas fa-times-circle me-2"></i>Peserta Tidak Hadir</h4>
                <p class="mb-0">Peserta ini ditandai sebagai <strong>Tidak Hadir</strong>. Nilai otomatis diset ke 0 dan status <strong>Tidak Lulus (FAIL)</strong>.</p>
            </div>
        @elseif($participant->attendance === 'permission')
            <div class="alert alert-warning shadow-sm rounded-4">
                <h4 class="alert-heading"><i class="fas fa-clock me-2"></i>Peserta Izin</h4>
                <p class="mb-0">Peserta ini mengajukan izin. Silakan jadwalkan ulang melalui halaman daftar peserta.</p>
            </div>
        @else
            <div class="alert alert-info shadow-sm rounded-4">
                <h4 class="alert-heading"><i class="fas fa-info-circle me-2"></i>Menunggu Kehadiran</h4>
                <p class="mb-0">Silakan tandai kehadiran peserta terlebih dahulu di halaman daftar peserta untuk dapat menginput nilai.</p>
            </div>
        @endif

        @if($participant->attendance === 'present')
                <form action="{{ route('admin.participant.score.update', $participant->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row g-4">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="test_date" class="form-label fw-bold">Tanggal Test</label>
                                <input type="date" class="form-control rounded-3" id="test_date" name="test_date"
                                       value="{{ old('test_date', $participant->test_date ? $participant->test_date->format('Y-m-d') : $participant->schedule->date->format('Y-m-d')) }}" required>
                                <input type="hidden" name="test_format" value="PBT">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label fw-bold">Jenjang Pendidikan</label>
                                <div class="form-control-plaintext bg-light border border-2 rounded-3 p-3">
                                    {{ $participant->academic_level_display ?? ($participant->studyProgram->level ?? 'Tidak Tersedia') }}
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label fw-bold">Status Kelulusan</label>
                                <div class="form-control-plaintext bg-light border border-2 rounded-3 p-3">
                                    @if($participant->test_score)
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="badge bg-{{ $participant->passed ? 'success' : 'warning' }} fs-6">
                                                {{ $participant->passed ? 'PASS' : 'FAIL' }}
                                            </span>
                                            @if($participant->is_score_validated)
                                                <span class="badge bg-primary px-2"><i class="fas fa-check-circle me-1"></i>Tervalidasi</span>
                                            @else
                                                <span class="badge bg-secondary px-2"><i class="fas fa-hourglass-half me-1"></i>Menunggu Validasi</span>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-muted">Belum Dinilai</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="row g-4">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="listening_score_pbt" class="form-label fw-bold">Listening</label>
                                <input type="number" class="form-control rounded-3" id="listening_score_pbt" name="listening_score_pbt"
                                       min="0" max="68" step="0.1"
                                       value="{{ old('listening_score_pbt', $participant->listening_score_pbt ?? '') }}"
                                       placeholder="0-68" oninput="calculateTotalScore()">
                                <small class="form-text text-muted">Skor Listening (0-68)</small>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="structure_score_pbt" class="form-label fw-bold">Structure</label>
                                <input type="number" class="form-control rounded-3" id="structure_score_pbt" name="structure_score_pbt"
                                       min="0" max="68" step="0.1"
                                       value="{{ old('structure_score_pbt', $participant->structure_score_pbt ?? '') }}"
                                       placeholder="0-68" oninput="calculateTotalScore()">
                                <small class="form-text text-muted">Skor Structure (0-68)</small>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="reading_score_pbt" class="form-label fw-bold">Reading</label>
                                <input type="number" class="form-control rounded-3" id="reading_score_pbt" name="reading_score_pbt"
                                       min="0" max="67" step="0.1"
                                       value="{{ old('reading_score_pbt', $participant->reading_score_pbt ?? '') }}"
                                       placeholder="0-67" oninput="calculateTotalScore()">
                                <small class="form-text text-muted">Skor Reading (0-67)</small>
                            </div>
                        </div>
                    </div>

                    <div class="row g-4 mt-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label fw-bold">Skor Total</label>
                                <div class="form-control-plaintext bg-light border border-2 rounded-3 p-3">
                                    @if($participant->test_score)
                                        <span class="fs-4 fw-bold" id="total_score_display">{{ number_format($participant->test_score, 0, '', '') }}</span>
                                    @else
                                        <span class="text-muted" id="total_score_display">Belum dihitung</span>
                                    @endif
                                </div>
                                <small class="form-text text-muted">Rumus: ((Listening + Structure + Reading) / 3) * 10</small>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label fw-bold">Syarat Kelulusan</label>
                                <div class="form-control-plaintext bg-light border border-2 rounded-3 p-3">
                                    @if($participant->test_score)
                                        @if($participant->academic_level === 'undergraduate' || $participant->academic_level === 'bachelor' || $participant->academic_level === 'sarjana' || $participant->academic_level === 's1' || stripos($participant->academic_level, 's1') !== false || stripos($participant->academic_level, 'sarjana') !== false)
                                            <span class="d-block">
                                                <span class="fw-bold text-primary">Sarjana S1 Skor ≥ 400</span>
                                            </span>
                                        @elseif($participant->academic_level === 'master' || $participant->academic_level === 'magister' || $participant->academic_level === 's2' || stripos($participant->academic_level, 's2') !== false || stripos($participant->academic_level, 'magister') !== false)
                                            <span class="d-block">
                                                <span class="fw-bold text-primary">Magister S2 Skor ≥ 450</span>
                                            </span>
                                        @elseif($participant->academic_level === 'doctorate' || $participant->academic_level === 'doctor' || $participant->academic_level === 'doktor' || $participant->academic_level === 's3' || stripos($participant->academic_level, 's3') !== false || stripos($participant->academic_level, 'doktor') !== false)
                                            <span class="d-block">
                                                <span class="fw-bold text-primary">Doktor S3 Skor ≥ 500</span>
                                            </span>
                                        @else
                                            <span class="text-muted">Jenjang Pendidikan Tidak Dikenali</span>
                                        @endif
                                    @else
                                        <span class="text-muted">Belum Dinilai</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <button type="submit" class="btn btn-success rounded-3 px-4 py-2 btn-hover-rise">
                            <i class="fas fa-save me-2"></i>Simpan Nilai
                        </button>
                        @if($participant->test_score && !$participant->is_score_validated)
                        <button type="button" class="btn btn-primary rounded-3 px-4 py-2 btn-hover-rise" onclick="event.preventDefault(); document.getElementById('validate-score-form').submit();">
                            <i class="fas fa-check-double me-2"></i>Validasi & Tampilkan ke Peserta
                        </button>
                        @endif
                    </div>
                </form>
                
                @if($participant->test_score && !$participant->is_score_validated)
                <form id="validate-score-form" action="{{ route('admin.participant.score.validate', $participant->id) }}" method="POST" class="d-none">
                    @csrf
                </form>
                @endif
            </div>
        </div>
        @endif
        @else
            @if($participant->attendance === 'present')
            <div class="alert alert-info shadow-sm rounded-4">
                <h4 class="alert-heading"><i class="fas fa-graduation-cap me-2"></i>Input Nilai TOEFL - {{ $participant->name }} ({{ $participant->nim }})</h4>
                <p class="mb-0">Hanya Admin yang dapat menginput atau mengubah nilai TOEFL.</p>
            </div>
            @endif
        @endif

    <!-- Test History Section -->
    <div class="row mt-4 animate__animated animate__fadeIn">
        <div class="col-md-12">
            <div class="card border-0 shadow-lg rounded-4">
                <div class="card-header bg-secondary text-white rounded-top-4">
                    <h5 class="mb-0"><i class="fas fa-history me-2"></i>Riwayat Tes (NIM: {{ $participant->nim }})</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Kategori Tes</th>
                                    <th>Tanggal Tes</th>
                                    <th>Jadwal / Ruangan</th>
                                    <th>Nilai Total</th>
                                    <th>Status</th>
                                    <th>Detail Skor</th>
                                    <th>Tgl Daftar</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($testHistory as $history)
                                <tr class="{{ $history->id == $participant->id ? 'table-primary' : '' }}">
                                    <td>
                                        <span class="fw-bold">{{ $history->test_category }}</span>
                                        @if($history->id == $participant->id)
                                            <span class="badge bg-primary ms-1">Aktif</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($history->test_date)
                                            {{ $history->test_date->format('d M Y') }}
                                        @else
                                            <span class="text-muted small">Belum tes</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="small fw-bold">{{ $history->schedule->room ?? '-' }}</div>
                                        <div class="small text-muted">{{ $history->schedule->date ? $history->schedule->date->format('d M Y') : '-' }}</div>
                                    </td>
                                    <td>
                                        @if($history->test_score)
                                            <span class="fs-5 fw-bold text-primary">{{ number_format($history->test_score, 0, '', '') }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($history->test_score)
                                            <span class="badge bg-{{ $history->passed ? 'success' : 'warning' }}">
                                                {{ $history->passed ? 'PASS' : 'FAIL' }}
                                            </span>
                                        @else
                                            <span class="badge bg-{{ $history->status === 'confirmed' ? 'info' : 'secondary' }}">
                                                {{ $history->status === 'confirmed' ? 'Terkonfirmasi' : 'Pending' }}
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if(($history->test_format ?? 'iBT') == 'PBT')
                                            <div class="small text-muted">
                                                L:{{ $history->listening_score_pbt ?: '0' }} | 
                                                S:{{ $history->structure_score_pbt ?: '0' }} | 
                                                R:{{ $history->reading_score_pbt ?: '0' }}
                                            </div>
                                        @else
                                            <div class="small text-muted">
                                                R:{{ $history->reading_score ?: '0' }} | 
                                                L:{{ $history->listening_score ?: '0' }} | 
                                                S:{{ $history->speaking_score ?: '0' }} | 
                                                W:{{ $history->writing_score ?: '0' }}
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="small text-muted">{{ $history->created_at->format('d/m/y H:i') }}</div>
                                    </td>
                                    <td>
                                        @if($history->id != $participant->id)
                                            <a href="{{ route('admin.participant.details', $history->id) }}" class="btn btn-sm btn-outline-primary rounded-pill">
                                                <i class="fas fa-eye"></i> Lihat
                                            </a>
                                        @else
                                            <span class="text-muted small">Sedang dibuka</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4 text-muted">Tidak ada riwayat tes ditemukan.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


@section('scripts')
<!-- Tambahkan library Animate.css jika belum ada -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

<script nonce="{{ $csp_nonce ?? '' }}">
function sendWhatsApp(phoneNumber) {
    // Hapus semua karakter non-digit
    const cleanedNumber = phoneNumber.replace(/\D/g, '');

    // Buat sure bahwa nomor dimulai dengan 62 (kode negara Indonesia) atau 0
    let waNumber;
    if (cleanedNumber.startsWith('62')) {
        waNumber = cleanedNumber;
    } else if (cleanedNumber.startsWith('0')) {
        // Ganti 0 di awal dengan 62
        waNumber = '62' + cleanedNumber.substring(1);
    } else {
        // Asumsikan ini nomor lokal, tambahkan kode negara
        waNumber = '62' + cleanedNumber;
    }

    // Buka tautan WhatsApp
    window.open('https://wa.me/' + waNumber, '_blank');
}

// JavaScript to load card preview
document.getElementById('cardPreviewModal').addEventListener('shown.bs.modal', function () {
    const modalBody = this.querySelector('.modal-body');
    modalBody.innerHTML = `
        <div class="d-flex justify-content-center align-items-center" style="min-height: 500px;">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Memuat...</span>
            </div>
        </div>
    `;

    const iframe = document.createElement('iframe');
    iframe.src = '{{ route('admin.participant.card.preview', $participant->id) }}';
    iframe.style.width = '100%';
    iframe.style.height = '70vh';
    iframe.style.border = 'none';
    iframe.onload = function() {
        modalBody.innerHTML = '';
        modalBody.appendChild(iframe);
    };

    setTimeout(() => {
        if (!modalBody.querySelector('iframe')) {
            modalBody.innerHTML = '';
            modalBody.appendChild(iframe);
        }
    }, 300);
});

document.getElementById('cardPreviewModal').addEventListener('hidden.bs.modal', function () {
    const iframe = this.querySelector('iframe');
    if (iframe) {
        iframe.src = 'about:blank';
    }
});

function calculateTotalScore() {
    const listening = parseFloat(document.getElementById('listening_score_pbt')?.value) || 0;
    const structure = parseFloat(document.getElementById('structure_score_pbt')?.value) || 0;
    const reading = parseFloat(document.getElementById('reading_score_pbt')?.value) || 0;

    const total = Math.round(((listening + structure + reading) / 3) * 10);

    const totalScoreElement = document.getElementById('total_score_display');
    if (totalScoreElement) {
        if (listening === 0 && structure === 0 && reading === 0) {
            totalScoreElement.textContent = 'Belum dihitung';
            totalScoreElement.className = 'text-muted';
        } else {
            totalScoreElement.textContent = total;
            totalScoreElement.className = 'fs-4 fw-bold';
        }
    }
}

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

@section('modals')
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
                            <label class="fw-bold text-muted">Skor TOEFL</label>
                            <div class="fw-bold fs-5">
                                @if($participant->test_score)
                                    {{ number_format($participant->test_score, 0, '', '') }}
                                    <span class="badge bg-{{ $participant->passed ? 'success' : 'warning' }} ms-2">
                                        {{ $participant->passed ? 'PASS' : 'FAIL' }}
                                    </span>
                                    <div class="mt-2">
                                        <a href="{{ route('admin.participant.certificate.download', $participant->id) }}" class="btn btn-sm btn-success rounded-pill px-3">
                                            <i class="fas fa-download me-1"></i>Unduh Sertifikat
                                        </a>
                                    </div>
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

<!-- Card Preview Modal -->
<div class="modal fade" id="cardPreviewModal" tabindex="-1" aria-labelledby="cardPreviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header">
                <h5 class="modal-title" id="cardPreviewModalLabel">Pratinjau Kartu Ujian - {{ $participant->name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <div class="d-flex justify-content-center align-items-center" style="min-height: 500px;">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Memuat...</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">Tutup</button>
                <a href="{{ route('admin.participant.card.preview', $participant->id) }}" target="_blank" class="btn btn-primary rounded-pill">Buka di Tab Baru</a>
                <a href="{{ route('admin.participant.card.download', $participant->id) }}" class="btn btn-success rounded-pill">Unduh PDF</a>
            </div>
        </div>
    </div>
</div>

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
@endsection

@endsection

