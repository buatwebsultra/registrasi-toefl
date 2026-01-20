@extends('layouts.app')

@section('title', 'Dashboard Peserta')

@section('content')

<style nonce="{{ $csp_nonce ?? '' }}">
    :root {
        --premium-blue: #0f172a;
        --premium-accent: #3b82f6;
        --premium-bg: #f8fafc;
        --glass-bg: rgba(255, 255, 255, 0.8);
    }

    .welcome-banner {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        border-radius: 1.5rem;
        padding: 3rem 2rem;
        color: white;
        margin-bottom: 2.5rem;
        position: relative;
        overflow: hidden;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }

    .welcome-banner::before {
        content: "";
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(59, 130, 246, 0.1) 0%, transparent 70%);
        animation: rotate 20s linear infinite;
    }

    @keyframes rotate {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }

    .banner-content {
        position: relative;
        z-index: 1;
    }

    .status-badge-floating {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(8px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        padding: 0.5rem 1.25rem;
        border-radius: 9999px;
        font-size: 0.875rem;
        color: white;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .premium-card {
        background: white;
        border: none;
        border-radius: 1.25rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        overflow: hidden;
    }

    .premium-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }

    .premium-card .card-header {
        background: transparent;
        border-bottom: 1px solid #f1f5f9;
        padding: 1.5rem;
        font-weight: 700;
        color: #1e293b;
    }

    .nav-tabs-premium {
        border-bottom: none;
        gap: 0.75rem;
        margin-bottom: 2rem;
    }

    .nav-tabs-premium .nav-link {
        border: none;
        border-radius: 1rem;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        color: #64748b;
        background: white;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
    }

    .nav-tabs-premium .nav-link:hover {
        background: #f1f5f9;
        color: #1e293b;
    }

    .nav-tabs-premium .nav-link.active {
        background: var(--premium-accent);
        color: white;
        box-shadow: 0 10px 15px -3px rgba(59, 130, 246, 0.4);
    }

    .data-table-premium thead th {
        background: #f8fafc;
        color: #64748b;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.05em;
        padding: 1rem 1.5rem;
        border: none;
    }

    .data-table-premium tbody td {
        padding: 1.25rem 1.5rem;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
        color: #1e293b;
    }

    .document-preview-card {
        border-radius: 1rem;
        overflow: hidden;
        border: 2px solid #f1f5f9;
        transition: all 0.3s ease;
        background: white;
    }

    .document-preview-card:hover {
        border-color: var(--premium-accent);
        transform: scale(1.02);
    }

    .btn-premium {
        border-radius: 0.75rem;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-premium-primary {
        background: var(--premium-accent);
        border: none;
        box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.4);
    }

    .btn-premium-primary:hover {
        background: #2563eb;
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(59, 130, 246, 0.4);
    }

    .dashboard-photo-container {
        position: relative;
        width: 100px;
        height: 100px;
        border-radius: 1.5rem;
        padding: 4px;
        background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        box-shadow: 0 10px 25px -5px rgba(59, 130, 246, 0.5);
        transition: transform 0.3s ease;
    }

    .dashboard-photo-container:hover {
        transform: scale(1.05) rotate(2deg);
    }

    .dashboard-photo {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 1.25rem;
        background: white;
    }

    .photo-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f1f5f9;
        color: #64748b;
        border-radius: 1.25rem;
    }

    /* Profile Detail Tab Photo */
    .profile-photo-frame {
        width: 100%;
        max-width: 220px;
        aspect-ratio: 3/4;
        background: #f3f4f6;
        border-radius: 1rem;
        overflow: hidden;
        margin: 0 auto;
        border: 4px solid white;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        position: relative;
    }

    .profile-photo {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
</style>

<div class="welcome-banner">
    <div class="banner-content">
        <div class="row align-items-center g-4">
            <!-- Left Column: Greeting and ID -->
            <div class="col-md-5 text-center text-md-start">
                <div class="status-badge-floating mb-3">
                    <i class="fas fa-id-card"></i>
                    <span>ID: {{ $participant->nim }}</span>
                </div>
                <h1 class="display-5 fw-bold mb-2">Halo, {{ $participant->name }}!</h1>
                <p class="lead mb-0 opacity-75">Selamat datang di portal TOEFL Anda.</p>
            </div>

            <!-- Middle Column: Photo -->
            <div class="col-md-2 d-flex justify-content-center">
                <div class="dashboard-photo-container">
                    @if($participant->photo_path && (\Storage::disk('private')->exists($participant->photo_path) || \Storage::disk('public')->exists($participant->photo_path)))
                        <img src="{{ route('participant.file.download', ['id' => $participant->id, 'type' => 'photo']) }}" 
                             alt="{{ $participant->name }}" 
                             class="dashboard-photo shadow-sm">
                    @else
                        <div class="photo-placeholder">
                            <i class="fas fa-user fa-3x"></i>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Right Column: Logout -->
            <div class="col-md-5 d-flex justify-content-center justify-content-md-end">
                <div class="d-flex gap-2">
                    <a href="#"
                       id="logout-link"
                       class="btn btn-light btn-premium text-danger">
                        <i class="fas fa-sign-out-alt me-2"></i>Keluar
                    </a>
                    <form id="logout-form" action="{{ route('participant.logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script nonce="{{ $csp_nonce ?? '' }}">
    document.addEventListener('DOMContentLoaded', function() {
        // Logout handler
        const logoutLink = document.getElementById('logout-link');
        if (logoutLink) {
            logoutLink.addEventListener('click', function(e) {
                e.preventDefault();
                document.getElementById('logout-form').submit();
            });
        }
    });
</script>

<!-- Show success message with account details -->
@if(session('success') || session('account_details'))
<div class="alert alert-success premium-card border-0 p-4 mb-4 animate__animated animate__fadeInDown" role="alert">
    <div class="d-flex align-items-center mb-3">
        <div class="bg-success bg-opacity-10 p-3 rounded-circle me-3">
            <i class="fas fa-check-circle text-success fs-3"></i>
        </div>
        <div>
            <h4 class="alert-heading fw-bold mb-0 text-success">🎉 Pendaftaran Berhasil!</h4>
        </div>
    </div>
    
    @if(session('success'))
        <p class="mb-3">{{ session('success') }}</p>
    @endif

    @if(session('account_details'))
        <div class="bg-light p-4 rounded-3 mb-3 border border-dashed border-success">
            <h6 class="fw-bold text-dark mb-3"><i class="fas fa-key me-2"></i>Informasi Akun Anda:</h6>
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="d-flex align-items-center">
                        <span class="text-muted me-2 small">Username:</span>
                        <code class="fs-6 fw-bold text-primary">{{ session('account_details')['username'] }}</code>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex align-items-center">
                        <span class="text-muted me-2 small">Password:</span>
                        <code class="fs-6 fw-bold text-primary">{{ session('account_details')['password'] }}</code>
                    </div>
                </div>
            </div>
        </div>
        <p class="mb-0 small text-muted"><strong>Pesan:</strong> {{ session('account_details')['message'] }}</p>
    @endif
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="top: 1.5rem; right: 1.5rem;"></button>
</div>
@endif

<ul class="nav nav-pills nav-tabs-premium" id="dashboardTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="participant-detail-tab" data-bs-toggle="tab" data-bs-target="#participant-detail" type="button" role="tab">
            <i class="fas fa-user-circle me-2"></i>Data Pribadi
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="registration-tab" data-bs-toggle="tab" data-bs-target="#registration" type="button" role="tab">
            <i class="fas fa-file-invoice me-2"></i>Status Pendaftaran
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="test-history-tab" data-bs-toggle="tab" data-bs-target="#test-history" type="button" role="tab">
            <i class="fas fa-history me-2"></i>Riwayat Tes
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="documents-tab" data-bs-toggle="tab" data-bs-target="#documents" type="button" role="tab">
            <i class="fas fa-folder-open me-2"></i>Berkas Dokumen
        </button>
    </li>
</ul>

<div class="tab-content" id="dashboardTabContent">

    <!-- Participant Detail Tab (Now First & Active) -->
    <div class="tab-pane fade show active" id="participant-detail" role="tabpanel" aria-labelledby="participant-detail-tab">
         @include('participant.partials.detail_tab')
    </div>

    <!-- Registration Information Tab -->
    <div class="tab-pane fade" id="registration" role="tabpanel" aria-labelledby="registration-tab">
        <div class="row g-4">
            <div class="col-lg-12">
                <div class="card premium-card border-0">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold text-primary"><i class="fas fa-info-circle me-2"></i>Status & Detail Registrasi</h5>
                        @php
                            $statusClass = $participant->status === 'confirmed' ? 'success' : ($participant->status === 'pending' ? 'warning' : 'danger');
                            $statusLabel = $participant->status === 'confirmed' ? 'Diverifikasi' : ($participant->status === 'pending' ? 'Menunggu Verifikasi' : 'Ditolak');
                        @endphp
                        <span class="badge bg-{{ $statusClass }} px-3 py-2 rounded-pill">
                            <i class="fas {{ $participant->status === 'confirmed' ? 'fa-check-circle' : 'fa-hourglass-half' }} me-1"></i>
                            {{ $statusLabel }}
                        </span>
                    </div>
                    <div class="card-body p-4">
                        @if($participant->status === 'rejected' && $participant->rejection_message)
                            <div class="alert alert-danger border-0 bg-danger bg-opacity-10 mb-4 p-3 rounded-3 d-flex align-items-start">
                                <i class="fas fa-exclamation-triangle mt-1 me-3 fs-4"></i>
                                <div>
                                    <h6 class="fw-bold mb-1">Pendaftaran Ditolak</h6>
                                    <div class="small opacity-75">{{ $participant->rejection_message }}</div>
                                </div>
                            </div>
                        @endif

                        <div class="row g-4">
                            <div class="col-md-6 border-end-md">
                                <div class="mb-3">
                                    <label class="text-muted small fw-bold text-uppercase mb-1">Informasi Peserta</label>
                                    <div class="p-3 bg-light rounded-3">
                                        <div class="d-flex justify-content-between mb-2">
                                            <span class="text-muted">Nama Lengkap</span>
                                            <span class="fw-bold">{{ $participant->name }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span class="text-muted">ID/NIM</span>
                                            <span class="fw-bold">{{ $participant->nim }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span class="text-muted">Program Studi</span>
                                            <span class="fw-bold text-end ms-3">{{ $participant->major }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <span class="text-muted">Fakultas</span>
                                            <span class="fw-bold">{{ $participant->faculty }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <label class="text-muted small fw-bold text-uppercase mb-1">Informasi Tes</label>
                                    <div class="p-3 bg-light rounded-3">
                                        <div class="d-flex justify-content-between mb-2">
                                            <span class="text-muted">Kategori Tes</span>
                                            @php
                                                $isGraduate = \Str::contains(strtolower($participant->academic_level), ['s2', 's3', 'magister', 'doktor', 'master', 'doctor']);
                                                $displayCategory = $isGraduate ? 'TOEFL-EQUIVALENT' : 'TOEFL-LIKE';
                                            @endphp
                                            <span class="badge bg-primary rounded-pill">{{ $displayCategory }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span class="text-muted">Tanggal Tes</span>
                                            <span class="fw-bold">{{ $participant->schedule->date->format('d M Y') }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span class="text-muted">Waktu</span>
                                            <span class="fw-bold">{{ \Carbon\Carbon::parse($participant->schedule->time)->format('H:i') }} WIB</span>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <span class="text-muted">Ruangan</span>
                                            <span class="fw-bold">{{ $participant->schedule->room }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="text-muted small fw-bold text-uppercase mb-1">Nomor Kursi</label>
                                    <div class="p-4 bg-primary bg-opacity-10 rounded-4 text-center border border-primary border-opacity-25">
                                        @if($participant->isSeatConfirmed())
                                            <div class="display-4 fw-bold text-primary mb-1">{{ $participant->seat_number }}</div>
                                            <div class="small text-primary opacity-75">Silakan menempati kursi sesuai nomor di atas</div>
                                        @else
                                            <div class="h3 fw-bold text-secondary mb-1">BELUM TERBIT</div>
                                            <div class="small text-muted text-wrap">Nomor kursi akan terbit otomatis setelah pembayaran tervalidasi</div>
                                        @endif
                                    </div>
                                </div>
                                <div>
                                    <label class="text-muted small fw-bold text-uppercase mb-1">Status Kehadiran</label>
                                    <div class="p-3 bg-light rounded-3 d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center">
                                            @php
                                                $attnIcon = match($participant->attendance) {
                                                    'present' => 'fa-check-circle text-success',
                                                    'absent' => 'fa-times-circle text-danger',
                                                    'permission' => 'fa-clock text-warning',
                                                    default => 'fa-question-circle text-muted'
                                                };
                                                $attnLabel = match($participant->attendance) {
                                                    'present' => 'Hadir',
                                                    'absent' => 'Tidak Hadir',
                                                    'permission' => 'Izin',
                                                    default => 'Belum Absensi'
                                                };
                                            @endphp
                                            <i class="fas {{ $attnIcon }} fs-4 me-3"></i>
                                            <span class="fw-bold">{{ $attnLabel }}</span>
                                        </div>
                                        <span class="text-muted small">{{ $participant->attendance_marked_at ? $participant->attendance_marked_at->format('H:i') . ' WIB' : '-' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Absent/Permission Alerts -->
                        @if($participant->attendance === 'absent')
                            <div class="alert alert-danger shadow-sm rounded-4 mt-4 animate__animated animate__shakeX">
                                <h6 class="alert-heading fw-bold mb-2 text-danger"><i class="fas fa-times-circle me-2"></i>Anda Tidak Hadir</h6>
                                <p class="mb-0 small text-dark opacity-75">Sesuai peraturan, nilai otomatis menjadi <strong>0</strong>. Silakan mendaftar tes ulang jika diperlukan.</p>
                            </div>
                        @elseif($participant->attendance === 'permission')
                            <div class="alert alert-warning shadow-sm rounded-4 mt-4 animate__animated animate__fadeIn">
                                <h6 class="alert-heading fw-bold mb-2 text-warning"><i class="fas fa-clock me-2"></i>Status: Izin</h6>
                                <p class="mb-0 small text-dark opacity-75">Status kehadiran Anda adalah izin. Silakan hubungi admin UPA Bahasa untuk penjadwalan mandiri.</p>
                            </div>
                        @endif

                        <!-- Results Overlay if present and validated -->
                        @if(!is_null($participant->test_score) && $participant->is_score_validated)
                            <div class="mt-5 pt-4 border-top">
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <h5 class="mb-0 fw-bold"><i class="fas fa-award text-warning me-2"></i>Hasil Tes Terbaru</h5>
                                    <span class="badge bg-{{ $participant->passed ? 'success' : 'danger' }} px-4 py-2 fs-6">
                                        {{ $participant->passed ? 'LULUS (PASS)' : 'TIDAK LULUS (FAIL)' }}
                                    </span>
                                </div>
                                <div class="row g-4 align-items-center">
                                    <div class="col-md-4 text-center border-end-md">
                                        <div class="p-4 bg-primary bg-opacity-5 rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 140px; height: 140px;">
                                            <div class="display-4 fw-bold text-dark mb-0">{{ number_format($participant->test_score, 0, '', '') }}</div>
                                        </div>
                                        <h6 class="text-muted text-uppercase small fw-bold">Skor Total</h6>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="row g-3">
                                            @if(($participant->test_format ?? 'iBT') == 'PBT')
                                                <div class="col-4">
                                                    <div class="p-3 bg-light rounded-3 text-center">
                                                        <div class="h5 fw-bold text-dark mb-1">{{ $participant->listening_score_pbt ?: '0' }}</div>
                                                        <div class="small text-muted">Listening</div>
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="p-3 bg-light rounded-3 text-center">
                                                        <div class="h5 fw-bold text-dark mb-1">{{ $participant->structure_score_pbt ?: '0' }}</div>
                                                        <div class="small text-muted">Structure</div>
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="p-3 bg-light rounded-3 text-center">
                                                        <div class="h5 fw-bold text-dark mb-1">{{ $participant->reading_score_pbt ?: '0' }}</div>
                                                        <div class="small text-muted">Reading</div>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="col-3">
                                                    <div class="p-3 bg-light rounded-3 text-center">
                                                        <div class="h5 fw-bold text-dark mb-1">{{ $participant->reading_score ?: '0' }}</div>
                                                        <div class="small text-muted">Reading</div>
                                                    </div>
                                                </div>
                                                <div class="col-3">
                                                    <div class="p-3 bg-light rounded-3 text-center">
                                                        <div class="h5 fw-bold text-dark mb-1">{{ $participant->listening_score ?: '0' }}</div>
                                                        <div class="small text-muted">Listen</div>
                                                    </div>
                                                </div>
                                                <div class="col-3">
                                                    <div class="p-3 bg-light rounded-3 text-center">
                                                        <div class="h5 fw-bold text-dark mb-1">{{ $participant->speaking_score ?: '0' }}</div>
                                                        <div class="small text-muted">Speak</div>
                                                    </div>
                                                </div>
                                                <div class="col-3">
                                                    <div class="p-3 bg-light rounded-3 text-center">
                                                        <div class="h5 fw-bold text-dark mb-1">{{ $participant->writing_score ?: '0' }}</div>
                                                        <div class="small text-muted">Write</div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="mt-4 p-3 bg-{{ $participant->passed ? 'success' : 'warning' }} bg-opacity-10 rounded-3 border-start border-4 border-{{ $participant->passed ? 'success' : 'warning' }}">
                                            <p class="mb-0 small text-dark">
                                                <strong>Catatan Kelulusan:</strong> Berdasarkan Peraturan REKTOR UHO jenjang {{ $participant->academic_level_display }}, ambang batas nilai Anda adalah 
                                                <strong>
                                                    {{ ($participant->academic_level === 'undergraduate' || $participant->academic_level === 'bachelor' || $participant->academic_level === 'sarjana' || $participant->academic_level === 's1' || stripos($participant->academic_level, 's1') !== false || stripos($participant->academic_level, 'sarjana') !== false) ? '400' : 
                                                       (($participant->academic_level === 'master' || $participant->academic_level === 'magister' || $participant->academic_level === 's2' || stripos($participant->academic_level, 's2') !== false || stripos($participant->academic_level, 'magister') !== false) ? '450' : '500') }}
                                                </strong>.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

@if(!is_null($participant->test_score) && $participant->is_score_validated)
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card premium-card border-0 bg-light bg-opacity-50">
                <div class="card-body p-4 text-center">
                    <p class="text-muted mb-0 small"><i class="fas fa-info-circle me-1"></i>Detail hasil di atas adalah rangking penilaian terbaru Anda. Sertifikat resmi hanya dapat diunduh jika status Anda <strong>PASS</strong>.</p>
                </div>
            </div>
        </div>
    </div>
@endif

                <div class="d-flex justify-content-center gap-3 mt-5 flex-wrap">
                    @if($participant->status !== 'rejected')
                        @php
                            $isFail = $participant->test_score !== null && !$participant->passed;
                        @endphp

                        @if($isFail)
                            <div class="alert alert-warning border-0 bg-warning bg-opacity-10 w-100 text-center rounded-4 mb-4">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                Kartu ujian tidak lagi tersedia karena hasil tes Anda adalah <strong>FAIL</strong>. Silakan daftar tes ulang.
                            </div>
                        @endif

                        <a href="{{ route('participant.card.download', $participant->id) }}"
                           class="btn btn-premium btn-premium-primary text-white {{ ($participant->status === 'pending' || $isFail) ? 'disabled' : '' }}"
                           {{ $isFail ? 'onclick=return(false);' : '' }}>
                            <i class="fas fa-file-pdf me-2"></i>Unduh Kartu (PDF)
                        </a>
                        <button type="button"
                                class="btn btn-premium btn-outline-primary {{ ($participant->status === 'pending' || $isFail) ? 'disabled' : '' }}"
                                data-bs-toggle="modal"
                                data-bs-target="{{ $isFail ? '' : '#cardPreviewModal' }}">
                            <i class="fas fa-file-pdf me-2"></i>Preview & Unduh PDF
                        </button>
                    @endif

                    @if($participant->status === 'rejected')
                        <a href="{{ route('participant.resubmit.payment.form', $participant->id) }}" class="btn btn-premium btn-warning">
                            <i class="fas fa-cloud-upload-alt me-2"></i>Upload Ulang Pembayaran
                        </a>
                    @elseif($participant->attendance !== 'absent' && (is_null($participant->test_score) || !$participant->is_score_validated))
                        <button class="btn btn-premium btn-light text-muted" disabled>
                            <i class="fas fa-history me-2"></i>Belum Bisa Daftar Ulang
                        </button>
                    @else
                        @if($participant->passed)
                            <a href="{{ route('participant.certificate.download', $participant->id) }}" class="btn btn-premium btn-success">
                                <i class="fas fa-certificate me-2"></i>Unduh Sertifikat
                            </a>
                        @else
                            <a href="{{ route('participant.retake.form', $participant->id) }}" class="btn btn-premium btn-warning">
                                <i class="fas fa-redo-alt me-2"></i>Daftar Tes Ulang
                            </a>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

</div> <!-- Closing registration tab pane -->

<!-- Test History Tab -->
<div class="tab-pane fade" id="test-history" role="tabpanel" aria-labelledby="test-history-tab">
    <div class="row g-4">
        <div class="col-lg-12">
             @if($testHistory->count() > 0)
                <div class="card premium-card border-0 shadow-sm overflow-hidden">
                    <div class="card-header bg-white p-4 border-bottom border-light">
                        <h5 class="mb-0 fw-bold d-flex align-items-center">
                            <span class="bg-primary bg-opacity-10 p-2 rounded-circle me-3">
                                <i class="fas fa-history text-primary"></i>
                            </span>
                            Riwayat Tes Sebelumnya
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light text-muted small text-uppercase fw-bold">
                                    <tr>
                                        <th class="ps-4 py-3">Kategori</th>
                                        <th class="py-3">Tanggal Tes</th>
                                        <th class="py-3">Ruangan</th>
                                        <th class="py-3 text-center">Skor Akhir</th>
                                        <th class="py-3">Detail Section</th>
                                        <th class="pe-4 py-3 text-end">Terdaftar Pada</th>
                                    </tr>
                                </thead>
                                <tbody class="border-top-0">
                                    @foreach($testHistory as $history)
                                    <tr>
                                        <td class="ps-4">
                                            <span class="fw-bold text-dark">{{ $history->test_category }}</span>
                                        </td>
                                        <td>
                                            <div class="d-flex flex-column">
                                                <span class="fw-medium">{{ $history->test_date ? $history->test_date->format('d M Y') : ($history->schedule ? $history->schedule->date->format('d M Y') : '-') }}</span>
                                                <span class="small text-muted">{{ $history->test_date ? $history->test_date->format('H:i') : ($history->schedule ? \Carbon\Carbon::parse($history->schedule->time)->format('H:i') : '') }} WIB</span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark border fw-normal px-3 py-2">
                                                <i class="fas fa-map-marker-alt me-1 text-danger"></i> {{ $history->schedule->room ?? '-' }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            @if($history->test_score && $history->is_score_validated)
                                                <div class="d-inline-block text-center">
                                                    <div class="h5 mb-0 fw-bold text-dark">{{ number_format($history->test_score, 0, '', '') }}</div>
                                                    <span class="badge bg-{{ $history->passed ? 'success' : 'warning' }} bg-opacity-10 text-{{ $history->passed ? 'success' : 'warning' }} rounded-pill small px-2">
                                                        {{ $history->passed ? 'PASS' : 'FAIL' }}
                                                    </span>
                                                </div>
                                            @else
                                                <span class="badge bg-secondary bg-opacity-10 text-secondary">Belum Dinilai</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(($history->test_format ?? 'iBT') == 'PBT' && $history->is_score_validated)
                                                <div class="d-flex gap-2">
                                                    <span class="badge bg-light text-muted border" title="Listening">L: {{ $history->listening_score_pbt ?: '-' }}</span>
                                                    <span class="badge bg-light text-muted border" title="Structure">S: {{ $history->structure_score_pbt ?: '-' }}</span>
                                                    <span class="badge bg-light text-muted border" title="Reading">R: {{ $history->reading_score_pbt ?: '-' }}</span>
                                                </div>
                                            @else
                                                <div class="d-flex flex-wrap gap-2">
                                                    <span class="badge bg-light text-muted border" title="Reading">R: {{ $history->reading_score ?: '-' }}</span>
                                                    <span class="badge bg-light text-muted border" title="Listening">L: {{ $history->listening_score ?: '-' }}</span>
                                                    <span class="badge bg-light text-muted border" title="Speaking">S: {{ $history->speaking_score ?: '-' }}</span>
                                                    <span class="badge bg-light text-muted border" title="Writing">W: {{ $history->writing_score ?: '-' }}</span>
                                                </div>
                                            @endif
                                        </td>
                                        <td class="pe-4 text-end">
                                            <span class="text-muted small">{{ $history->created_at->format('d M Y') }}</span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @if($testHistory->count() > 5)
                    <div class="card-footer bg-white text-center py-3">
                        <small class="text-muted">Menampilkan 5 riwayat terakhir</small>
                    </div>
                    @endif
                </div>
            @else
                <div class="card premium-card border-0 shadow-sm">
                    <div class="card-body p-5 text-center">
                        <div class="bg-light rounded-circle d-inline-block p-4 mb-3">
                             <i class="fas fa-history fa-3x text-muted opacity-50"></i>
                        </div>
                        <h5 class="fw-bold text-dark">Belum Ada Riwayat Tes</h5>
                        <p class="text-muted">Data riwayat tes Anda akan muncul di sini setelah Anda mengikuti ujian.</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Document Tab -->
<div class="tab-pane fade" id="documents" role="tabpanel" aria-labelledby="documents-tab">
    <div class="row g-4">
        <div class="col-lg-12">
            <div class="card premium-card border-0">
                <div class="card-header p-4">
                    <h5 class="mb-0 fw-bold text-primary"><i class="fas fa-folder-open me-2"></i>Berkas Dokumen</h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-4">
                        <div class="col-md-4">
                            <div class="p-3 border rounded-3 text-center h-100 bg-light bg-opacity-50">
                                <div class="mb-3">
                                    <i class="fas fa-receipt fa-3x text-primary opacity-50"></i>
                                </div>
                                <h6 class="fw-bold">Bukti Pembayaran</h6>
                                <p class="small text-muted mb-3">File bukti pembayaran yang diunggah</p>
                                <a href="{{ route('participant.file.download', ['id' => $participant->id, 'type' => 'payment_proof']) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye me-1"></i>Lihat File
                                </a>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3 border rounded-3 text-center h-100 bg-light bg-opacity-50">
                                <div class="mb-3">
                                    <i class="fas fa-user-circle fa-3x text-primary opacity-50"></i>
                                </div>
                                <h6 class="fw-bold">Foto Peserta</h6>
                                <p class="small text-muted mb-3">Foto profil untuk kartu ujian</p>
                                <a href="{{ route('participant.file.download', ['id' => $participant->id, 'type' => 'photo']) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye me-1"></i>Lihat File
                                </a>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3 border rounded-3 text-center h-100 bg-light bg-opacity-50">
                                <div class="mb-3">
                                    <i class="fas fa-id-card fa-3x text-primary opacity-50"></i>
                                </div>
                                <h6 class="fw-bold">Kartu Identitas (KTP)</h6>
                                <p class="small text-muted mb-3">Scan KTP/Identitas yang diunggah</p>
                                <a href="{{ route('participant.file.download', ['id' => $participant->id, 'type' => 'ktp']) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye me-1"></i>Lihat File
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div> <!-- Closing tab-content -->
@endsection


<!-- Card Preview Modal -->
<div class="modal fade" id="cardPreviewModal" tabindex="-1" aria-labelledby="cardPreviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
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
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <a href="{{ route('participant.card.preview', $participant->id) }}" target="_blank" class="btn btn-primary">Buka di Tab Baru</a>
                <a href="{{ route('participant.card.download', $participant->id) }}" class="btn btn-success">Unduh PDF</a>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript to load card preview -->
<script nonce="{{ $csp_nonce ?? '' }}">
    // Function to load card preview when modal is shown
    document.getElementById('cardPreviewModal').addEventListener('shown.bs.modal', function () {
        const modalBody = this.querySelector('.modal-body');
        modalBody.innerHTML = `
            <div class="d-flex justify-content-center align-items-center" style="min-height: 500px;">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Memuat...</span>
                </div>
            </div>
        `;

        // Create iframe for the card preview
        const iframe = document.createElement('iframe');
        iframe.src = '{{ route('participant.card.preview', $participant->id) }}';
        iframe.style.width = '100%';
        iframe.style.height = '70vh';
        iframe.style.border = 'none';
        iframe.onload = function() {
            // Remove loading spinner and show iframe
            modalBody.innerHTML = '';
            modalBody.appendChild(iframe);
        };

        // Load iframe after a small delay to ensure modal is fully rendered
        setTimeout(() => {
            modalBody.innerHTML = '';
            modalBody.appendChild(iframe);
        }, 300);
    });

    // Reset iframe src when modal is closed to free resources
    document.getElementById('cardPreviewModal').addEventListener('hidden.bs.modal', function () {
        const iframe = this.querySelector('iframe');
        if (iframe) {
            iframe.src = 'about:blank'; // Free resources
        }
    });
</script>

<!-- Photo Modal -->
<div class="modal fade" id="photoModal" tabindex="-1" aria-labelledby="photoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold" id="photoModalLabel">Foto Peserta</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0 text-center bg-light">
                <img src="{{ route('participant.file.download', ['id' => $participant->id, 'type' => 'photo']) }}" 
                     alt="Foto Peserta Full" 
                     class="img-fluid" 
                     style="max-height: 80vh;">
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script nonce="{{ $csp_nonce ?? '' }}">
document.addEventListener('DOMContentLoaded', function() {
    const photoTrigger = document.getElementById('photo-frame-trigger');
    
    if (photoTrigger) {
        photoTrigger.addEventListener('click', function() {
            // Check if photo exists based on the presence of the img tag inside
            const img = this.querySelector('img');
            
            if (img) {
                // Show the modal
                var modal = new bootstrap.Modal(document.getElementById('photoModal'));
                modal.show();
            } else {
                // Alert if no photo available
                alert('Foto tidak tersedia');
            }
        });
    }
});
</script>
