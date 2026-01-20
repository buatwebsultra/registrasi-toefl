@extends('layouts.app')

@section('title', 'Detail Peserta - Premium View')

@section('content')
<style nonce="{{ $csp_nonce ?? '' }}">
    /* Premium Variables */
    :root {
        --premium-bg: #f3f4f6;
        --card-bg: #ffffff;
        --text-primary: #111827;
        --text-secondary: #6b7280;
        --accent-blue: #3b82f6;
        --accent-gold: #f59e0b;
        --accent-green: #10b981;
        --border-color: #e5e7eb;
    }

    body {
        background-color: var(--premium-bg);
        font-family: 'Inter', sans-serif;
    }

    .premium-container {
        max-width: 900px;
        margin: 0 auto;
        padding-bottom: 4rem;
    }

    /* Identity Card */
    .identity-card {
        background: var(--card-bg);
        border-radius: 24px;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        overflow: hidden;
        border: 1px solid rgba(255, 255, 255, 0.5);
    }

    .id-header {
        background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);
        padding: 1.5rem 2rem;
        color: white;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .university-brand {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .university-logo {
        width: 40px;
        height: 40px;
        background: white;
        border-radius: 50%;
        padding: 2px;
    }

    .id-body {
        padding: 2.5rem;
    }

    /* Layout Grid */
    .id-grid {
        display: grid;
        grid-template-columns: 1.5fr 1fr;
        gap: 3rem;
    }

    @media (max-width: 768px) {
        .id-grid {
            grid-template-columns: 1fr;
        }
        .photo-section {
            order: -1;
            margin-bottom: 2rem;
        }
    }

    /* Data Fields */
    .data-group {
        margin-bottom: 1.5rem;
    }

    .data-label {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--text-secondary);
        font-weight: 600;
        margin-bottom: 0.25rem;
    }

    .data-value {
        font-size: 1rem;
        color: var(--text-primary);
        font-weight: 600;
        line-height: 1.4;
    }

    .data-value.highlight {
        color: var(--accent-blue);
    }

    /* Photo Section */
    .photo-frame {
        width: 100%;
        max-width: 280px;
        aspect-ratio: 3/4;
        background: #f3f4f6;
        border-radius: 12px;
        overflow: hidden;
        margin: 0 auto;
        border: 4px solid white;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        position: relative;
    }

    .participant-photo {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    /* Status Badges */
    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.875rem;
        font-weight: 500;
    }

    .status-confirmed { background: #d1fae5; color: #065f46; }
    .status-pending { background: #fef3c7; color: #92400e; }
    .status-rejected { background: #fee2e2; color: #991b1b; }

    /* Action Bar */
    .action-bar {
        margin-top: 2rem;
        padding-top: 2rem;
        border-top: 1px solid var(--border-color);
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
        justify-content: flex-end;
    }

    .btn-action {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1.25rem;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.2s;
        border: none;
        cursor: pointer;
    }

    .btn-action:hover {
        transform: translateY(-1px);
    }

    /* Collapsible Sections (Docs/Scores) */
    .secondary-section {
        margin-top: 2rem;
        background: white;
        border-radius: 16px;
        border: 1px solid var(--border-color);
        overflow: hidden;
    }
    
    .secondary-header {
        padding: 1rem 1.5rem;
        background: #f9fafb;
        font-weight: 600;
        color: var(--text-primary);
        border-bottom: 1px solid var(--border-color);
        display: flex;
        justify-content: space-between;
        align-items: center;
        cursor: pointer;
    }

    .secondary-body {
        padding: 1.5rem;
    }
</style>

<div class="container-fluid premium-container animate__animated animate__fadeIn">
    
    <!-- Breadcrumb / Back Navigation -->
    <div class="mb-4">
        <a href="{{ url()->previous() }}" class="text-decoration-none text-muted fw-bold">
            <i class="fas fa-arrow-left me-2"></i>Kembali
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success rounded-3 shadow-sm mb-4 border-0 border-start border-4 border-success">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        </div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-danger rounded-3 shadow-sm mb-4 border-0 border-start border-4 border-danger">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
        </div>
    @endif

    <!-- MAIN IDENTITY CARD -->
    <div class="identity-card">
        <!-- Header -->
        <div class="id-header">
            <div class="university-brand">
            <img src="{{ asset('logo-uho-dan-diktisaintek-768x143nobg.png') }}" class="university-logo" alt="Logo" style="width: auto; height: 40px; border-radius: 0; background: transparent;">
            <div>
                    <div class="fw-bold text-uppercase" style="font-size: 0.8rem; opacity: 0.9;">Pusat Bahasa</div>
                    <div class="fw-bold" style="font-size: 1.1rem;">TOEFL Registration</div>
                </div>
            </div>
            <div>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-light btn-action text-primary">
                    <i class="fas fa-home"></i> Home
                </a>
            </div>
        </div>

        <!-- Body -->
        <div class="id-body">
            <div class="id-grid">
                <!-- Left Column: Data -->
                <div class="info-section">
                    <!-- Status Header -->
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <div>
                            @if($participant->status === 'confirmed')
                                <span class="status-badge status-confirmed"><i class="fas fa-check-circle me-1"></i> Terkonfirmasi</span>
                            @elseif($participant->status === 'pending')
                                <span class="status-badge status-pending"><i class="fas fa-clock me-1"></i> Menunggu Verifikasi</span>
                            @else
                                <span class="status-badge status-rejected"><i class="fas fa-times-circle me-1"></i> Ditolak</span>
                            @endif
                        </div>
                        <div class="text-end">
                            <div class="data-label">Nomor Kursi</div>
                            <div class="data-value highlight">{{ $participant->seat_number ?: 'TBA' }}</div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 data-group">
                            <div class="data-label">NIM</div>
                            <div class="data-value">{{ $participant->nim }}</div>
                        </div>
                        <div class="col-md-6 data-group">
                            <div class="data-label">Jenis Kelamin</div>
                            <div class="data-value">{{ $participant->gender == 'male' ? 'Laki-laki' : 'Perempuan' }}</div>
                        </div>
                        
                        <div class="col-12 data-group">
                            <div class="data-label">Nama Lengkap</div>
                            <div class="data-value fs-5">{{ $participant->name }}</div>
                        </div>

                        <div class="col-md-6 data-group">
                            <div class="data-label">Tempat & Tanggal Lahir</div>
                            <div class="data-value">{{ $participant->birth_place }}, {{ $participant->birth_date ? $participant->birth_date->format('d M Y') : '-' }}</div>
                        </div>
                        <div class="col-md-6 data-group">
                            <div class="data-label">Nomor WhatsApp</div>
                            <div class="data-value">
                                <a href="#" class="text-decoration-none text-success fw-bold btn-whatsapp-contact" data-phone="{{ $participant->phone }}">
                                    <i class="fab fa-whatsapp me-1"></i>{{ $participant->phone }} <sup><i class="fas fa-external-link-alt" style="font-size: 10px;"></i></sup>
                                </a>
                            </div>
                        </div>

                        <div class="col-12 data-group">
                            <div class="data-label">Email</div>
                            <div class="data-value">{{ $participant->email }}</div>
                        </div>

                        <div class="col-12"><hr class="text-muted opacity-25"></div>

                        <div class="col-md-6 data-group">
                            <div class="data-label">Jurusan / Prodi</div>
                            <div class="data-value">{{ optional($participant->studyProgram)->name ?? '-' }}</div>
                            <div class="small text-muted">{{ $participant->faculty }}</div>
                        </div>
                        
                        <div class="col-md-6 data-group">
                            <div class="data-label">Jadwal Tanggal Pembayaran</div>
                            <div class="data-value">{{ $participant->payment_date ? $participant->payment_date->format('d M Y H:i') : '-' }}</div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Visuals -->
                <div class="photo-section text-center">
                    <div class="photo-frame mb-3">
                        @if($participant->photo_url)
                            <img src="{{ $participant->photo_url }}" class="participant-photo" alt="Foto">
                        @else
                            <div class="d-flex align-items-center justify-content-center h-100 bg-light text-muted">
                                <i class="fas fa-user fa-3x"></i>
                            </div>
                        @endif
                    </div>
                    
                    <button type="button" class="btn btn-outline-primary btn-sm w-100 rounded-pill mb-2" data-bs-toggle="modal" data-bs-target="#testInfoModal">
                        <i class="fas fa-info-circle me-1"></i> Detail Jadwal Tes
                    </button>
                    
                    @if(Auth::user()->isOperator() && $participant->status === 'confirmed')
                    
                    @endif
                </div>
            </div>

            <!-- Footer Actions -->
            <div class="action-bar">
                 @if(Auth::user()->isAdmin())
                    <a href="{{ route('admin.participant.card.download', $participant->id) }}" class="btn-action bg-primary text-white text-decoration-none">
                        <i class="fas fa-file-pdf"></i> Unduh Kartu PDF
                    </a>
                 @endif
                 
                 @if($participant->test_score)
                    <a href="{{ route('admin.participant.certificate.download', $participant->id) }}" class="btn-action bg-success text-white text-decoration-none">
                        <i class="fas fa-certificate"></i> Unduh Sertifikat
                    </a>
                 @endif

                 @if(Auth::user()->isOperator())
                    <button class="btn-action bg-warning text-dark" data-bs-toggle="modal" data-bs-target="#resetPasswordModal">
                        <i class="fas fa-key"></i> Reset Password
                    </button>
                    <form action="{{ route('admin.participant.delete', $participant->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-action bg-danger text-white" onclick="return confirm('Apakah Anda yakin ingin menghapus peserta ini? data tidak dapat dikembalikan.')">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </form>
                 @endif
            </div>
        </div>
    </div>

    <!-- DOCUMENT & SCORE SECTION (Collapsible/Clean) -->
    <div class="row g-4">
        <!-- Documents -->
        <div class="col-lg-6">
            <div class="secondary-section">
                <div class="secondary-header" data-bs-toggle="collapse" data-bs-target="#docsCollapse">
                    <span><i class="fas fa-folder-open me-2 text-warning"></i>Berkas Dokumen</span>
                    <i class="fas fa-chevron-down text-muted"></i>
                </div>
                <div class="collapse show" id="docsCollapse">
                    <div class="secondary-body">
                        <div class="d-flex gap-3">
                            <div class="text-center w-50">
                                <div class="bg-light rounded p-3 mb-2 border">
                                    @if($participant->payment_proof_path)
                                        <a href="{{ route('participant.file.download', ['id' => $participant->id, 'type' => 'payment_proof']) }}" target="_blank">
                                            <i class="fas fa-receipt fa-2x text-primary"></i>
                                            <div class="small mt-2">Bukti Bayar</div>
                                        </a>
                                    @else
                                        <i class="fas fa-times text-muted"></i>
                                    @endif
                                </div>
                            </div>
                            <div class="text-center w-50">
                                <div class="bg-light rounded p-3 mb-2 border">
                                    @if($participant->ktp_path)
                                        <a href="{{ route('participant.file.download', ['id' => $participant->id, 'type' => 'ktp']) }}" target="_blank">
                                            <i class="fas fa-id-card fa-2x text-info"></i>
                                            <div class="small mt-2">KTP</div>
                                        </a>
                                    @else
                                        <i class="fas fa-times text-muted"></i>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Score Input (Admin Only) -->
        <div class="col-lg-6">
            @if(Auth::user()->isAdmin())
            <div class="secondary-section">
                <div class="secondary-header bg-success bg-opacity-10" data-bs-toggle="collapse" data-bs-target="#scoreCollapse">
                    <span class="text-success"><i class="fas fa-graduation-cap me-2"></i>Input Nilai / Penilaian</span>
                    <i class="fas fa-chevron-down text-muted"></i>
                </div>
                <div class="collapse show" id="scoreCollapse">
                    <div class="secondary-body">
                         @if($participant->attendance === 'present')
                            <form action="{{ route('admin.participant.score.update', $participant->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="test_date" value="{{ old('test_date', $participant->test_date ? $participant->test_date->format('Y-m-d') : (optional($participant->schedule)->date ? $participant->schedule->date->format('Y-m-d') : now()->format('Y-m-d'))) }}">
                                <input type="hidden" name="test_format" value="PBT">
                                
                                <div class="row g-2 mb-3">
                                    <div class="col-4">
                                        <label class="small fw-bold text-muted">Listening</label>
                                        <input type="number" class="form-control" name="listening_score_pbt" value="{{ $participant->listening_score_pbt }}" placeholder="0-68" min="0" max="68" oninput="calculateTotalScore()" id="listening_score_pbt">
                                    </div>
                                    <div class="col-4">
                                        <label class="small fw-bold text-muted">Structure</label>
                                        <input type="number" class="form-control" name="structure_score_pbt" value="{{ $participant->structure_score_pbt }}" placeholder="0-68" min="0" max="68" oninput="calculateTotalScore()" id="structure_score_pbt">
                                    </div>
                                    <div class="col-4">
                                        <label class="small fw-bold text-muted">Reading</label>
                                        <input type="number" class="form-control" name="reading_score_pbt" value="{{ $participant->reading_score_pbt }}" placeholder="0-67" min="0" max="67" oninput="calculateTotalScore()" id="reading_score_pbt">
                                    </div>
                                </div>
                                
                                <div class="p-3 bg-light rounded text-center mb-3">
                                    <div class="small text-muted text-uppercase ls-1">Total Score</div>
                                    <div class="h2 mb-0 fw-bold text-primary" id="total_score_display">{{ $participant->test_score ?? '-' }}</div>
                                </div>
                                
                                <button type="submit" class="btn btn-success w-100 fw-bold">Simpan Nilai</button>
                                
                                @if(!$participant->is_score_validated && $participant->test_score)
                                    <button type="button" id="btn-validate-publish" class="btn btn-outline-primary w-100 mt-2">Validasi & Publish</button>
                                @endif
                            </form>
                            @if(!$participant->is_score_validated)
                                <form id="validate-form" action="{{ route('admin.participant.score.validate', $participant->id) }}" method="POST" class="d-none">@csrf</form>
                            @endif
                         @else
                            <div class="alert alert-warning mb-0 small">
                                Peserta belum hadir atau tidak hadir. Update kehadiran di daftar peserta.
                            </div>
                         @endif
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Test History Section -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="secondary-section">
                <div class="secondary-header" data-bs-toggle="collapse" data-bs-target="#historyCollapse">
                    <span><i class="fas fa-history me-2 text-info"></i>Riwayat Tes Peserta</span>
                    <i class="fas fa-chevron-down text-muted"></i>
                </div>
                <div class="collapse show" id="historyCollapse">
                    <div class="secondary-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0 align-middle">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="px-4 py-3 text-muted small text-uppercase fw-bold">Tanggal Tes</th>
                                        <th class="px-4 py-3 text-muted small text-uppercase fw-bold">Jenis Tes</th>
                                        <th class="px-4 py-3 text-muted small text-uppercase fw-bold">Skor</th>
                                        <th class="px-4 py-3 text-muted small text-uppercase fw-bold">Status</th>
                                        <th class="px-4 py-3 text-muted small text-uppercase fw-bold text-end">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($testHistory as $history)
                                    <tr class="{{ $history->id == $participant->id ? 'bg-primary bg-opacity-10' : '' }}">
                                        <td class="px-4 py-3">
                                            <div class="fw-bold text-dark">
                                                {{ optional($history->schedule)->date ? $history->schedule->date->format('d M Y') : ($history->test_date ? $history->test_date->format('d M Y') : '-') }}
                                            </div>
                                            <div class="small text-muted">
                                                {{ optional($history->schedule)->time ? \Carbon\Carbon::parse($history->schedule->time)->format('H:i') . ' WITA' : '-' }}
                                            </div>
                                        </td>
                                        <td class="px-4 py-3">
                                            <span class="badge bg-light text-dark border">{{ $history->test_category }}</span>
                                            @if($history->id == $participant->id)
                                                <span class="badge bg-primary ms-1">Current</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3">
                                            @if($history->test_score)
                                                <span class="fw-bold {{ $history->passed ? 'text-success' : 'text-danger' }}">
                                                    {{ $history->test_score }}
                                                </span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3">
                                            @if($history->status == 'confirmed')
                                                <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3">Terkonfirmasi</span>
                                            @elseif($history->status == 'pending')
                                                <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill px-3">Pending</span>
                                            @else
                                                <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3">Ditolak</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-end">
                                            @if($history->id != $participant->id)
                                                <a href="{{ route('admin.participant.details', $history->id) }}" class="btn btn-sm btn-outline-primary rounded-pill">
                                                    Lihat
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5 text-muted">
                                            <i class="fas fa-history fa-2x mb-3 d-block opacity-50"></i>
                                            Belum ada riwayat tes lainnya.
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('admin.partials.modals-participant-details')

</div>

@endsection

@section('scripts')
<script nonce="{{ $csp_nonce ?? '' }}">
function sendWhatsApp(phoneNumber) {
    const cleanedNumber = phoneNumber.replace(/\D/g, '');
    let waNumber = cleanedNumber.startsWith('62') ? cleanedNumber : (cleanedNumber.startsWith('0') ? '62' + cleanedNumber.substring(1) : '62' + cleanedNumber);
    window.open('https://wa.me/' + waNumber, '_blank');
}

document.addEventListener('DOMContentLoaded', function() {
    // WhatsApp Contact Listener
    document.querySelectorAll('.btn-whatsapp-contact').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const phone = this.getAttribute('data-phone');
            if (phone) {
                sendWhatsApp(phone);
            }
        });
    });

    // Validate & Publish Button Listener
    const validateBtn = document.getElementById('btn-validate-publish');
    if (validateBtn) {
        validateBtn.addEventListener('click', function() {
            const form = document.getElementById('validate-form');
            if (form) form.submit();
        });
    }
});

function calculateTotalScore() {
    const listening = parseFloat(document.getElementById('listening_score_pbt')?.value) || 0;
    const structure = parseFloat(document.getElementById('structure_score_pbt')?.value) || 0;
    const reading = parseFloat(document.getElementById('reading_score_pbt')?.value) || 0;
    const total = Math.round(((listening + structure + reading) / 3) * 10);
    
    const display = document.getElementById('total_score_display');
    if(display) display.textContent = (listening+structure+reading) > 0 ? total : '-';
}

// Card Preview Modal Logic
const previewModal = document.getElementById('cardPreviewModal');
if (previewModal) {
    previewModal.addEventListener('show.bs.modal', function () {
        const iframe = document.getElementById('cardPreviewFrame');
        const container = document.getElementById('cardPreviewContainer');
        
        // Reset state
        iframe.classList.add('d-none');
        container.classList.remove('d-none');
        
        // Set src
        iframe.src = "{{ route('admin.participant.card.preview', $participant->id) }}";
        
        iframe.onload = function() {
            container.classList.add('d-none');
            iframe.classList.remove('d-none');
        };
    });

    previewModal.addEventListener('hidden.bs.modal', function () {
        const iframe = document.getElementById('cardPreviewFrame');
        iframe.src = 'about:blank';
    });
}
</script>
@endsection
