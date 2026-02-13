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

        .status-confirmed {
            background: #d1fae5;
            color: #065f46;
        }

        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }

        .status-rejected {
            background: #fee2e2;
            color: #991b1b;
        }

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
                    <img src="{{ asset('logo-uho-dan-diktisaintek-768x143nobg.png') }}" class="university-logo" alt="Logo"
                        style="width: auto; height: 40px; border-radius: 0; background: transparent;">
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
                                    <span class="status-badge status-confirmed"><i class="fas fa-check-circle me-1"></i>
                                        Terkonfirmasi</span>
                                @elseif($participant->status === 'pending')
                                    <span class="status-badge status-pending"><i class="fas fa-clock me-1"></i> Menunggu
                                        Verifikasi</span>
                                @else
                                    <span class="status-badge status-rejected"><i class="fas fa-times-circle me-1"></i>
                                        Ditolak</span>
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
                                <div class="data-value">{{ $participant->gender == 'male' ? 'Laki-laki' : 'Perempuan' }}
                                </div>
                            </div>

                            <div class="col-12 data-group">
                                <div class="data-label">Nama Lengkap</div>
                                <div class="data-value fs-5">{{ $participant->name }}</div>
                            </div>

                            <div class="col-md-6 data-group">
                                <div class="data-label">Tempat & Tanggal Lahir</div>
                                <div class="data-value">{{ $participant->birth_place }},
                                    {{ $participant->birth_date ? $participant->birth_date->format('d M Y') : '-' }}
                                </div>
                            </div>
                            <div class="col-md-6 data-group">
                                <div class="data-label">Nomor WhatsApp</div>
                                <div class="data-value">
                                    <a href="#" class="text-decoration-none text-success fw-bold btn-whatsapp-contact"
                                        data-phone="{{ $participant->phone }}">
                                        <i class="fab fa-whatsapp me-1"></i>{{ $participant->phone }} <sup><i
                                                class="fas fa-external-link-alt" style="font-size: 10px;"></i></sup>
                                    </a>
                                </div>
                            </div>

                            <div class="col-12 data-group">
                                <div class="data-label">Email</div>
                                <div class="data-value">{{ $participant->email }}</div>
                            </div>

                            <div class="col-12">
                                <hr class="text-muted opacity-25">
                            </div>

                            <div class="col-md-6 data-group">
                                <div class="data-label">Jurusan / Prodi</div>
                                <div class="data-value">{{ optional($participant->studyProgram)->name ?? '-' }}</div>
                                <div class="small text-muted">{{ $participant->faculty }}</div>
                            </div>

                            <div class="col-md-6 data-group">
                                <div class="data-label">Jadwal Tanggal Pembayaran</div>
                                <div class="data-value">
                                    {{ $participant->payment_date ? $participant->payment_date->format('d M Y H:i') : '-' }}
                                </div>
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

                        <button type="button" class="btn btn-outline-primary btn-sm w-100 rounded-pill mb-2"
                            data-bs-toggle="modal" data-bs-target="#testInfoModal">
                            <i class="fas fa-info-circle me-1"></i> Detail Jadwal Tes
                        </button>

                        @if(Auth::user()->isOperator() && $participant->status === 'confirmed')

                        @endif
                    </div>
                </div>

                <!-- Footer Actions -->
                <div class="action-bar">
                    @if(Auth::user()->isOperator())
                        <a href="{{ route('admin.participant.card.download', $participant->id) }}"
                            class="btn-action bg-primary text-white text-decoration-none">
                            <i class="fas fa-file-pdf"></i> Unduh Kartu PDF
                        </a>
                    @endif

                    @if($participant->test_score)
                        <a href="{{ route('admin.participant.certificate.download', $participant->id) }}"
                            class="btn-action bg-success text-white text-decoration-none">
                            <i class="fas fa-certificate"></i> Unduh Sertifikat
                        </a>
                    @endif

                    @if($participant->status === 'pending')
                        <button class="btn-action bg-success text-white" data-bs-toggle="modal"
                            data-bs-target="#confirmPaymentModal">
                            <i class="fas fa-check"></i> Konfirmasi Bayar
                        </button>
                        <button class="btn-action bg-danger text-white" data-bs-toggle="modal"
                            data-bs-target="#rejectModal{{ $participant->id }}">
                            <i class="fas fa-times"></i> Tolak Pendaftaran
                        </button>
                    @endif

                    @if(Auth::user()->isOperator())
                        <button type="button" class="btn-action bg-info text-white" data-bs-toggle="modal"
                            data-bs-target="#editParticipantDataModal">
                            <i class="fas fa-user-edit"></i> Edit Data
                        </button>
                        <button class="btn-action bg-warning text-dark" data-bs-toggle="modal"
                            data-bs-target="#resetPasswordModal">
                            <i class="fas fa-key"></i> Reset Password
                        </button>
                        <form action="{{ route('admin.participant.delete', $participant->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-action bg-danger text-white"
                                onclick="return confirm('Apakah Anda yakin ingin menghapus peserta ini? data tidak dapat dikembalikan.')">
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
                            @php
                                $historyRecords = \App\Models\Participant::where('nim', $participant->nim)
                                    ->orderBy('created_at', 'desc')
                                    ->get();
                                $allProofs = collect();
                                foreach ($historyRecords as $rec) {
                                    if ($rec->payment_proof_path) {
                                        $allProofs->push([
                                            'path' => $rec->payment_proof_path,
                                            'date' => $rec->payment_date ?: $rec->created_at,
                                            'type' => 'payment_proof',
                                            'participant_id' => $rec->id,
                                            'origin' => $rec->id == $participant->id ? 'Current Proof' : 'History'
                                        ]);
                                    }
                                    if ($rec->previous_payment_proof_path) {
                                        $allProofs->push([
                                            'path' => $rec->previous_payment_proof_path,
                                            'date' => $rec->created_at,
                                            'type' => 'previous_payment_proof',
                                            'participant_id' => $rec->id,
                                            'origin' => 'Archive'
                                        ]);
                                    }
                                }
                                $allProofs = $allProofs->unique('path')->values();
                            @endphp

                            <div class="row g-4">
                                <!-- KTP (Static) -->
                                <div class="col-md-4">
                                    <div class="data-label mb-2">Kartu Tanda Penduduk (KTP)</div>
                                    <div
                                        class="bg-light rounded p-3 text-center border h-100 d-flex flex-column align-items-center justify-content-center">
                                        @if($participant->ktp_path)
                                            <a href="{{ route('participant.file.download', ['id' => $participant->id, 'type' => 'ktp']) }}"
                                                target="_blank">
                                                <i class="fas fa-id-card fa-3x text-info mb-2"></i>
                                                <div class="small fw-bold">View KTP</div>
                                            </a>
                                        @else
                                            <i class="fas fa-times-circle fa-2x text-muted mb-2"></i>
                                            <div class="small">Tidak ada KTP</div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Payment Proof Carousel -->
                                <div class="col-md-8">
                                    <div class="data-label mb-2">Riwayat Bukti Pembayaran ({{ $allProofs->count() }})</div>
                                    <div class="bg-white rounded border shadow-sm overflow-hidden"
                                        style="min-height: 200px;">
                                        @if($allProofs->isEmpty())
                                            <div class="d-flex flex-column align-items-center justify-content-center py-5">
                                                <i class="fas fa-history text-muted fa-2x mb-2"></i>
                                                <div class="small text-muted">Belum ada riwayat pembayaran</div>
                                            </div>
                                        @else
                                            <div id="paymentHistoryCarousel" class="carousel slide" data-bs-ride="false">
                                                <div class="carousel-inner">
                                                    @foreach($allProofs as $index => $proof)
                                                        <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                                                            <div class="p-3 text-center">
                                                                <div class="mb-2">
                                                                    <span
                                                                        class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-3 rounded-pill">
                                                                        {{ $proof['origin'] }} |
                                                                        {{ $proof['date'] instanceof \Carbon\Carbon ? $proof['date']->format('d M Y H:i') : $proof['date'] }}
                                                                    </span>
                                                                </div>
                                                                @php
                                                                    $downloadUrl = route('participant.file.download', ['id' => $proof['participant_id'], 'type' => $proof['type']]);
                                                                @endphp
                                                                <a href="{{ $downloadUrl }}" target="_blank">
                                                                    <img src="{{ $downloadUrl }}"
                                                                        class="img-fluid rounded shadow-sm border"
                                                                        style="max-height: 180px;" alt="Bukti Bayar">
                                                                </a>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                                @if($allProofs->count() > 1)
                                                    <button class="carousel-control-prev" type="button"
                                                        data-bs-target="#paymentHistoryCarousel" data-bs-slide="prev"
                                                        style="filter: invert(1); width: 10%;">
                                                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                        <span class="visually-hidden">Previous</span>
                                                    </button>
                                                    <button class="carousel-control-next" type="button"
                                                        data-bs-target="#paymentHistoryCarousel" data-bs-slide="next"
                                                        style="filter: invert(1); width: 10%;">
                                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                        <span class="visually-hidden">Next</span>
                                                    </button>
                                                @endif
                                            </div>
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
                        <div class="secondary-header bg-success bg-opacity-10" data-bs-toggle="collapse"
                            data-bs-target="#scoreCollapse">
                            <span class="text-success"><i class="fas fa-graduation-cap me-2"></i>Input Nilai</span>
                            <i class="fas fa-chevron-down text-muted"></i>
                        </div>
                        <div class="collapse show" id="scoreCollapse">
                            <div class="secondary-body">
                                @if($participant->attendance === 'present')
                                    <form action="{{ route('admin.participant.score.update', $participant->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="test_date"
                                            value="{{ old('test_date', $participant->test_date ? $participant->test_date->format('Y-m-d') : (optional($participant->schedule)->date ? $participant->schedule->date->format('Y-m-d') : now()->format('Y-m-d'))) }}">
                                        <input type="hidden" name="test_format" value="PBT">

                                        <div class="row g-2 mb-3">
                                            <div class="col-4">
                                                <label class="small fw-bold text-muted">L: Jumlah Benar (0-50)</label>
                                                <input type="number" class="form-control" name="raw_listening_pbt"
                                                    value="{{ $participant->raw_listening_pbt }}" placeholder="0-50" min="0"
                                                    max="50" oninput="updateConvertedScores()" id="raw_listening_pbt">
                                                <div class="small mt-1 text-primary fw-bold">Konversi: <span
                                                        id="listening_converted">{{ $participant->listening_score_pbt ?? '-' }}</span>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <label class="small fw-bold text-muted">S: Jumlah Benar (0-40)</label>
                                                <input type="number" class="form-control" name="raw_structure_pbt"
                                                    value="{{ $participant->raw_structure_pbt }}" placeholder="0-40" min="0"
                                                    max="40" oninput="updateConvertedScores()" id="raw_structure_pbt">
                                                <div class="small mt-1 text-primary fw-bold">Konversi: <span
                                                        id="structure_converted">{{ $participant->structure_score_pbt ?? '-' }}</span>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <label class="small fw-bold text-muted">R: Jumlah Benar (0-50)</label>
                                                <input type="number" class="form-control" name="raw_reading_pbt"
                                                    value="{{ $participant->raw_reading_pbt }}" placeholder="0-50" min="0" max="50"
                                                    oninput="updateConvertedScores()" id="raw_reading_pbt">
                                                <div class="small mt-1 text-primary fw-bold">Konversi: <span
                                                        id="reading_converted">{{ $participant->reading_score_pbt ?? '-' }}</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="p-3 bg-light rounded text-center mb-3">
                                            <div class="small text-muted text-uppercase ls-1">Total Score (Konversi)</div>
                                            <div class="h2 mb-0 fw-bold text-primary" id="total_score_display">
                                                {{ $participant->test_score ?? '-' }}
                                            </div>
                                        </div>

                                        <button type="submit" class="btn btn-success w-100 fw-bold">Simpan Nilai</button>

                                        @if(!$participant->is_score_validated && $participant->test_score)
                                            <button type="button" id="btn-validate-publish"
                                                class="btn btn-outline-primary w-100 mt-2">Validasi & Publish</button>
                                        @endif
                                    </form>
                                    @if(!$participant->is_score_validated)
                                        <form id="validate-form"
                                            action="{{ route('admin.participant.score.validate', $participant->id) }}" method="POST"
                                            class="d-none">@csrf</form>
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
                                            <tr
                                                class="{{ $history->id == $participant->id ? 'bg-primary bg-opacity-10' : '' }}">
                                                <td class="px-4 py-3">
                                                    <div class="fw-bold text-dark">
                                                        {{ optional($history->schedule)->date ? $history->schedule->date->format('d M Y') : ($history->test_date ? $history->test_date->format('d M Y') : '-') }}
                                                    </div>
                                                    <div class="small text-muted">
                                                        {{ optional($history->schedule)->time ? \Carbon\Carbon::parse($history->schedule->time)->format('H:i') . ' WITA' : '-' }}
                                                    </div>
                                                </td>
                                                <td class="px-4 py-3">
                                                    <span
                                                        class="badge bg-light text-dark border">{{ $history->test_category }}</span>
                                                    @if($history->id == $participant->id)
                                                        <span class="badge bg-primary ms-1">Current</span>
                                                    @endif
                                                </td>
                                                <td class="px-4 py-3">
                                                    @if($history->test_score)
                                                        <span
                                                            class="fw-bold {{ $history->passed ? 'text-success' : 'text-danger' }}">
                                                            {{ $history->test_score }}
                                                        </span>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td class="px-4 py-3">
                                                    @if($history->status == 'confirmed')
                                                        <span
                                                            class="badge bg-success bg-opacity-10 text-success rounded-pill px-3">Terkonfirmasi</span>
                                                    @elseif($history->status == 'pending')
                                                        <span
                                                            class="badge bg-warning bg-opacity-10 text-warning rounded-pill px-3">Pending</span>
                                                    @else
                                                        <span
                                                            class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3">Ditolak</span>
                                                    @endif
                                                </td>
                                                <td class="px-4 py-3 text-end">
                                                    @if($history->id != $participant->id)
                                                        <a href="{{ route('admin.participant.details', $history->id) }}"
                                                            class="btn btn-sm btn-outline-primary rounded-pill">
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
        <!-- Edit Participant Data Modal -->
        <div class="modal fade" id="editParticipantDataModal" tabindex="-1" aria-labelledby="editParticipantDataModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
                    <div class="modal-header bg-gradient-premium border-0 text-white"
                        style="border-radius: 20px 20px 0 0; background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);">
                        <h5 class="modal-title fw-bold" id="editParticipantDataModalLabel">
                            <i class="fas fa-user-edit me-2"></i>Edit Data Peserta
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <form action="{{ route('admin.participant.update-data', $participant->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body p-4">
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label class="form-label fw-bold text-muted small text-uppercase">Nama Lengkap</label>
                                    <input type="text" class="form-control rounded-3" name="name"
                                        value="{{ $participant->name }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-muted small text-uppercase">Jenis Kelamin</label>
                                    <select class="form-select rounded-3" name="gender" required>
                                        <option value="male" {{ $participant->gender == 'male' ? 'selected' : '' }}>Laki-laki
                                        </option>
                                        <option value="female" {{ $participant->gender == 'female' ? 'selected' : '' }}>
                                            Perempuan</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-muted small text-uppercase">Nomor WhatsApp</label>
                                    <input type="text" class="form-control rounded-3" name="phone"
                                        value="{{ $participant->phone }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-muted small text-uppercase">Tempat Lahir</label>
                                    <input type="text" class="form-control rounded-3" name="birth_place"
                                        value="{{ $participant->birth_place }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-muted small text-uppercase">Tanggal Lahir</label>
                                    <input type="date" class="form-control rounded-3" name="birth_date"
                                        value="{{ $participant->birth_date ? $participant->birth_date->format('Y-m-d') : '' }}"
                                        required>
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label fw-bold text-muted small text-uppercase">Email</label>
                                    <input type="email" class="form-control rounded-3" name="email"
                                        value="{{ $participant->email }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer border-0 p-4">
                            <button type="button" class="btn btn-light rounded-pill px-4"
                                data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary rounded-pill px-4 shadow">Simpan Perubahan</button>
                        </div>
                    </form>
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

        document.addEventListener('DOMContentLoaded', function () {
            // WhatsApp Contact Listener
            document.querySelectorAll('.btn-whatsapp-contact').forEach(btn => {
                btn.addEventListener('click', function (e) {
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
                validateBtn.addEventListener('click', function () {
                    const form = document.getElementById('validate-form');
                    if (form) form.submit();
                });
            }
        });

        function updateConvertedScores() {
            const rawL = parseInt(document.getElementById('raw_listening_pbt')?.value) || 0;
            const rawS = parseInt(document.getElementById('raw_structure_pbt')?.value) || 0;
            const rawR = parseInt(document.getElementById('raw_reading_pbt')?.value) || 0;

            // TOEFL PBT Conversion Mapping (from image)
            const conversion = {
                50: [68, null, 67], 49: [67, null, 66], 48: [66, null, 65], 47: [65, null, 63], 46: [63, null, 61],
                45: [62, null, 60], 44: [61, null, 59], 43: [60, null, 58], 42: [59, null, 57], 41: [58, null, 56],
                40: [57, 68, 55], 39: [57, 67, 54], 38: [56, 65, 54], 37: [55, 63, 53], 36: [54, 61, 52],
                35: [54, 60, 52], 34: [53, 58, 51], 33: [52, 57, 50], 32: [52, 56, 49], 31: [51, 55, 48],
                30: [51, 54, 48], 29: [50, 53, 47], 28: [49, 52, 46], 27: [49, 51, 46], 26: [48, 50, 45],
                25: [48, 49, 44], 24: [47, 48, 43], 23: [47, 47, 43], 22: [46, 46, 42], 21: [45, 45, 41],
                20: [45, 44, 40], 19: [44, 43, 39], 18: [43, 43, 38], 17: [42, 41, 37], 16: [41, 40, 36],
                15: [41, 40, 35], 14: [39, 38, 34], 13: [38, 37, 32], 12: [37, 36, 31], 11: [35, 35, 30],
                10: [33, 33, 29], 9: [32, 31, 28], 8: [32, 29, 28], 7: [31, 27, 27], 6: [30, 26, 26],
                5: [29, 25, 25], 4: [28, 23, 24], 3: [27, 22, 23], 2: [26, 21, 23], 1: [25, 20, 22],
                0: [24, 20, 21]
            };

            const getScaled = (session, raw) => {
                const val = raw > 50 ? 50 : (raw < 0 ? 0 : raw);
                const idx = session - 1;
                // Adjust for structure (max 40)
                if (session === 2) {
                    const rawSFixed = raw > 40 ? 40 : raw;
                    return conversion[rawSFixed][idx] || 20;
                }
                return conversion[val][idx] || 20;
            };

            const scaledL = getScaled(1, rawL);
            const scaledS = getScaled(2, rawS);
            const scaledR = getScaled(3, rawR);

            const total = Math.round(((scaledL + scaledS + scaledR) / 3) * 10);

            // Update UI
            if (document.getElementById('listening_converted')) document.getElementById('listening_converted').textContent = scaledL;
            if (document.getElementById('structure_converted')) document.getElementById('structure_converted').textContent = scaledS;
            if (document.getElementById('reading_converted')) document.getElementById('reading_converted').textContent = scaledR;

            const display = document.getElementById('total_score_display');
            if (display) display.textContent = (rawL + rawS + rawR) > 0 ? total : '-';
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

                iframe.onload = function () {
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