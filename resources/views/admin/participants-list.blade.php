@extends('layouts.app')

@section('title', 'Daftar Peserta')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h1>Peserta untuk {{ $schedule->room }} - {{ $schedule->date->format('d M Y') }}</h1>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary mb-3">Kembali ke Dashboard</a>

            @if(request('status') === 'pending')
                <div class="alert alert-warning mt-3 border-start border-warning border-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <i class="fas fa-filter me-2"></i> Menampilkan <strong>Peserta Menunggu Validasi</strong>
                        </div>
                        <a href="{{ route('admin.participants.list', $schedule->id) }}"
                            class="btn btn-sm btn-outline-dark">Lihat Semua Data</a>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="mb-4">Detail Jadwal</h5>
                    <div class="table-responsive">
                        <table class="table table-borderless mb-0">
                            <tbody>
                                <tr>
                                    <td class="fw-bold w-20p">Tanggal</td>
                                    <td class="w-5p">:</td>
                                    <td>{{ $schedule->date->format('d M Y') }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold w-20p">Ruangan</td>
                                    <td class="w-5p">:</td>
                                    <td>{{ $schedule->room }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold w-20p">Kategori</td>
                                    <td class="w-5p">:</td>
                                    <td>{{ $schedule->category }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold w-20p">Kapasitas</td>
                                    <td class="w-5p">:</td>
                                    <td>{{ $schedule->used_capacity }} / {{ $schedule->capacity }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Pencarian NIM -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Cari Peserta berdasarkan NIM / Nama</h5>
                </div>
                <div class="card-body">
                    <form id="searchForm" method="GET" action="{{ route('admin.participants.list', $schedule->id) }}">
                        <div class="row align-items-end">
                            <div class="col-md-4">
                                <label class="form-label small fw-bold text-muted text-uppercase">Cari NIM / Nama</label>
                                <input type="text" name="search_nim" id="search_nim_input" class="form-control" 
                                    placeholder="Cari NIM atau Nama..."
                                    value="{{ request('search_nim') }}" 
                                    autofocus>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label small fw-bold text-muted text-uppercase">Urutkan</label>
                                <select name="sort" class="form-select" onchange="this.form.submit()">
                                    <option value="seat_asc" {{ $sort === 'seat_asc' ? 'selected' : '' }}>Kursi</option>
                                    <option value="name_asc" {{ $sort === 'name_asc' ? 'selected' : '' }}>Nama</option>
                                    <option value="score_desc" {{ $sort === 'score_desc' ? 'selected' : '' }}>Nilai</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label small fw-bold text-muted text-uppercase">Tampilkan</label>
                                <select name="per_page" class="form-select" onchange="this.form.submit()">
                                    <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                                    <option value="20" {{ $perPage == 20 ? 'selected' : '' }}>20</option>
                                    <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                                    <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label d-none d-md-block">&nbsp;</label>
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary flex-fill">
                                        <i class="fas fa-search me-1"></i> Cari
                                    </button>
                                    <a href="{{ route('admin.participants.list', $schedule->id) }}"
                                        class="btn btn-outline-secondary flex-fill">
                                        <i class="fas fa-undo me-1"></i> Reset
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3>Peserta Terdaftar</h3>
                @if(Auth::user()->isAdmin())
                        <button type="button" class="btn btn-primary d-none" id="bulkValidateBtn">
                            <i class="fas fa-check-double"></i> Validasi Nilai Terpilih
                        </button>
                        <button type="button" class="btn btn-outline-primary" id="toggleBulkInput">
                            <i class="fas fa-edit me-1"></i> Mode Input Nilai
                        </button>
                        <a href="{{ route('admin.schedule.participants.export', $schedule->id) }}" class="btn btn-success">
                            <i class="fas fa-download"></i> Download Seluruh Peserta
                        </a>
                        <a href="{{ route('admin.schedule.attendance.export', $schedule->id) }}" class="btn btn-warning text-dark">
                            <i class="fas fa-file-excel"></i> Daftar Hadir (Excel)
                        </a>
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteAllModal">
                            <i class="fas fa-trash-alt"></i> Hapus Seluruh Peserta
                        </button>
                    </div>

                    <form id="bulkValidateForm" action="{{ route('admin.participants.score.bulk-validate') }}" method="POST"
                        class="d-none">
                        @csrf
                    </form>
                @else
                <a href="{{ route('admin.schedule.participants.export', $schedule->id) }}" class="btn btn-success">
                    <i class="fas fa-download"></i> Download Seluruh Peserta
                </a>
            @endif
        </div>

        <!-- Delete All Participants Modal -->
        <div class="modal fade" id="deleteAllModal" tabindex="-1" aria-labelledby="deleteAllModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteAllModalLabel">Konfirmasi Penghapusan Seluruh Peserta</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Anda akan menghapus seluruh peserta pada jadwal:</p>
                        <p><strong>Ruangan:</strong> {{ $schedule->room }}<br>
                            <strong>Tanggal:</strong> {{ $schedule->date->format('d M Y') }}
                        </p>
                        <p><strong>Jumlah Peserta:</strong> {{ $totalParticipants }}</p>
                        <p class="text-danger">Tindakan ini tidak dapat dibatalkan. Semua peserta akan dihapus secara
                            permanen.</p>
                        <p>Mohon ketik <strong>"HAPUS {{ $totalParticipants }} PESERTA"</strong> untuk mengonfirmasi
                            penghapusan:</p>
                        <input type="text" class="form-control" id="confirmDeleteAll"
                            placeholder="Ketik perintah penghapusan">
                        <div id="confirmDeleteAllError" class="text-danger mt-1 d-none">Perintah penghapusan tidak sesuai
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <form action="{{ route('admin.schedule.clear-participants', $schedule->id) }}" method="POST"
                            class="d-inline d-none" id="clearAllParticipantsForm">
                            @csrf
                            <button type="submit" class="btn btn-danger" id="deleteAllConfirmedBtn">Konfirmasi Hapus
                                Semua</button>
                        </form>
                        <button type="button" class="btn btn-danger" id="verifyDeleteAllBtn">Verifikasi dan Hapus</button>
                    </div>
                </div>
            </div>
        </div>

        <form id="bulkScoreForm" action="{{ route('admin.participants.bulk-score.update', $schedule->id) }}" method="POST">
            @csrf
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th class="w-40px"><input type="checkbox" id="selectAll"></th>
                            <th class="w-40px">No</th>
                            <th>Nomor Kursi</th>
                            <th>NIM</th>
                            <th>Nama</th>
                            <th>Prodi</th>
                            <th id="scoreColumnTitle">Status Nilai</th>
                            <th>Status Verifikasi</th>
                            <th>Kehadiran</th>
                            <th>WhatsApp</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($participants as $participant)
                            <tr>
                                <td>
                                    @if($participant->test_score && !$participant->is_score_validated)
                                        <input type="checkbox" class="participant-checkbox" name="participant_ids[]"
                                            value="{{ $participant->id }}">
                                    @else
                                        <span class="text-muted opacity-25"><i class="fas fa-minus"></i></span>
                                    @endif
                                </td>
                                <td>
                                    {{ ($participants->currentPage() - 1) * $participants->perPage() + $loop->iteration }}
                                </td>
                                <td>{{ $participant->effective_seat_number }}</td>
                                <td>{{ $participant->nim }}</td>
                                <td>
                                    {{ $participant->name }}
                                    @if($participant->test_count > 0)
                                        <span class="badge bg-light text-dark shadow-sm border ms-1" title="Jumlah Riwayat Tes">
                                            {{ $participant->test_count }}x
                                        </span>
                                    @endif
                                </td>
                                <td>{{ $participant->studyProgram->name }} {{ $participant->studyProgram->level }}</td>
                                <td>
                                    <div class="score-display">
                                        @if($participant->test_score)
                                            @if($participant->is_score_validated)
                                                <span class="badge bg-primary"><i class="fas fa-check-circle me-1"></i>Tervalidasi</span>
                                            @else
                                                <span class="badge bg-secondary"><i class="fas fa-hourglass-half me-1"></i>Menunggu
                                                    Validasi</span>
                                            @endif
                                            <div class="small fw-bold text-dark mt-1">
                                                {{ number_format($participant->test_score, 0, '', '') }}
                                                ({{ $participant->passed ? 'PASS' : 'FAIL' }})</div>
                                        @else
                                            <span class="text-muted small">Belum dinput</span>
                                        @endif
                                    </div>
                                    <div class="score-input d-none">
                                        @if($participant->attendance === 'present')
                                            <div class="input-group input-group-sm mb-1">
                                                <span class="input-group-text p-1" style="width: 20px; font-size: 10px;">L</span>
                                                <input type="number" name="scores[{{ $participant->id }}][listening]" 
                                                    class="form-control raw-score px-1" data-session="1" data-id="{{ $participant->id }}"
                                                    value="{{ $participant->raw_listening_pbt }}" min="0" max="50">
                                            </div>
                                            <div class="input-group input-group-sm mb-1">
                                                <span class="input-group-text p-1" style="width: 20px; font-size: 10px;">S</span>
                                                <input type="number" name="scores[{{ $participant->id }}][structure]" 
                                                    class="form-control raw-score px-1" data-session="2" data-id="{{ $participant->id }}"
                                                    value="{{ $participant->raw_structure_pbt }}" min="0" max="40">
                                            </div>
                                            <div class="input-group input-group-sm mb-1">
                                                <span class="input-group-text p-1" style="width: 20px; font-size: 10px;">R</span>
                                                <input type="number" name="scores[{{ $participant->id }}][reading]" 
                                                    class="form-control raw-score px-1" data-session="3" data-id="{{ $participant->id }}"
                                                    value="{{ $participant->raw_reading_pbt }}" min="0" max="50">
                                            </div>
                                            <div class="mt-1 small" style="font-size: 11px;">
                                                <strong>Total: <span class="total-preview" id="total_{{ $participant->id }}">{{ $participant->test_score ?: '-' }}</span></strong>
                                                <span id="pass_fail_{{ $participant->id }}" class="fw-bold">{{ $participant->test_score ? ($participant->passed ? '(PASS)' : '(FAIL)') : '' }}</span>
                                            </div>
                                            <input type="hidden" class="academic-level" data-id="{{ $participant->id }}" value="{{ $participant->academic_level }}">
                                            <input type="hidden" class="passing-grade" data-id="{{ $participant->id }}" value="{{ $participant->studyProgram->passing_grade ?? '' }}">
                                        @else
                                            <span class="text-muted small">Tidak Hadir</span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <span
                                        class="badge bg-{{ $participant->status === 'confirmed' ? 'success' : ($participant->status === 'pending' ? 'warning' : ($participant->status === 'rejected' ? 'danger' : 'secondary')) }}">
                                        {{ $participant->status === 'confirmed' ? 'Terkonfirmasi' : ($participant->status === 'pending' ? 'Tertunda' : ($participant->status === 'rejected' ? 'Ditolak' : 'Dibatalkan')) }}
                                    </span>
                                </td>
                                <td>
                                    @if($participant->attendance === 'present')
                                        <span class="badge bg-success">Hadir</span>
                                    @elseif($participant->attendance === 'absent')
                                        <span class="badge bg-danger">Tidak Hadir</span>
                                    @elseif($participant->attendance === 'permission')
                                        <span class="badge bg-warning text-dark">Izin</span>
                                    @else
                                        <span class="badge bg-secondary">-</span>
                                    @endif
                                    <button type="button" class="btn btn-sm btn-link p-0 ms-1 btn-open-attendance"
                                        data-id="{{ $participant->id }}" data-name="{{ $participant->name }}"
                                        data-attendance="{{ $participant->attendance }}" {{ $participant->passed ? 'disabled title="Tidak dapat mengubah kehadiran peserta yang sudah Lulus"' : ($participant->status !== 'confirmed' ? 'disabled title="Peserta belum terkonfirmasi"' : '') }}>
                                        <i
                                            class="fas fa-edit {{ ($participant->passed || $participant->status !== 'confirmed') ? 'text-muted' : '' }}"></i>
                                    </button>
                                </td>
                                <td>
                                    @if($participant->phone)
                                        <a href="#" class="btn btn-sm btn-success btn-whatsapp-contact"
                                            data-phone="{{ $participant->phone }}">
                                            <i class="fab fa-whatsapp"></i> Chat
                                        </a>
                                    @else
                                        <span class="text-muted small">-</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.participant.details', $participant->id) }}"
                                        class="btn btn-info btn-sm me-1">Lihat Detail</a>
                                    @if(Auth::user()->isSuperAdmin())
                                        <button type="button" class="btn btn-primary btn-sm me-1 btn-reschedule" 
                                            data-id="{{ $participant->id }}">
                                            <i class="fas fa-calendar-alt"></i> Pindah
                                        </button>
                                    @endif
                                    @if(Auth::user()->isOperator())
                                        <button type="button" class="btn btn-warning btn-sm me-1 btn-reset-password"
                                            data-id="{{ $participant->id }}" data-name="{{ $participant->name }}"
                                            data-username="{{ $participant->username }}">
                                            <i class="fas fa-key"></i> Reset
                                        </button>
                                        <button type="button" class="btn btn-danger btn-sm btn-delete-participant" 
                                            data-id="{{ $participant->id }}" data-name="{{ $participant->name }}">Hapus</button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                @if($searchNim)
                                    <td colspan="11" class="text-center">Tidak ditemukan peserta dengan NIM: {{ $searchNim }}</td>
                                @else
                                    <td colspan="11" class="text-center">Tidak ada peserta terdaftar untuk jadwal ini</td>
                                @endif
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                @if($participants->hasPages())
                    <div class="d-flex justify-content-center">
                        {{ $participants->appends(['search_nim' => $searchNim, 'sort' => $sort])->links() }}
                    </div>
                @endif
            </div>
            <div class="card-footer d-none mt-3" id="bulkScoreFooter">
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary btn-lg px-5 shadow">
                        <i class="fas fa-save me-2"></i> Simpan Semua Nilai
                    </button>
                </div>
            </div>
        </form>
    </div>
    </div>

    <!-- Attendance Modal -->
    <div class="modal fade" id="attendanceModal" tabindex="-1" aria-labelledby="attendanceModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="attendanceModalLabel">Set Kehadiran - <span
                            id="attendanceParticipantName"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="attendanceForm" action="" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="d-grid gap-2">
                            <button type="button" class="btn btn-outline-success p-3 text-start btn-set-attendance"
                                data-status="present">
                                <i class="fas fa-check-circle me-2"></i> <strong>Hadir</strong>
                                <div class="small text-muted ms-4">Peserta hadir dan mengikuti ujian.</div>
                            </button>

                            <button type="button" class="btn btn-outline-danger p-3 text-start btn-set-attendance"
                                data-status="absent">
                                <i class="fas fa-times-circle me-2"></i> <strong>Tidak Hadir</strong>
                                <div class="small text-muted ms-4">Peserta tidak hadir (Otomatis Gagal).</div>
                            </button>

                            <button type="button" class="btn btn-outline-warning p-3 text-start btn-set-attendance"
                                data-status="permission" id="btn-izin-attendance">
                                <i class="fas fa-clock me-2"></i> <strong>Izin @if(Auth::user()->isSuperAdmin())(Reschedule)@endif</strong>
                                @if(Auth::user()->isSuperAdmin())
                                    <div class="small text-muted ms-4">Peserta minta pindah jadwal.</div>
                                @else
                                    <div class="small text-muted ms-4">Peserta tidak dapat hadir dengan alasan tertentu.</div>
                                @endif
                            </button>
                        </div>

                        @if(Auth::user()->isSuperAdmin())
                            <!-- Reschedule Selection (appears when Izin is clicked) -->
                            <div id="rescheduleSelection" class="mt-4 p-3 border rounded-3 bg-light d-none">
                                <h6 class="fw-bold mb-3">Pilih Jadwal Baru (Opsional)</h6>
                                <div class="mb-3">
                                    <select name="new_schedule_id" id="new_schedule_id_attendance" class="form-select">
                                        <option value="">-- Tetap di Jadwal Ini (Hanya Tandai Izin) --</option>
                                        @if(isset($allAvailableSchedules) && count($allAvailableSchedules) > 0)
                                            @foreach($allAvailableSchedules as $s)
                                                <option value="{{ $s->id }}">
                                                    {{ $s->date->format('d M Y') }} - {{ $s->room }} 
                                                    ({{ $s->used_capacity }}/{{ $s->capacity }})
                                                </option>
                                            @endforeach
                                        @else
                                            <option value="" disabled>Tidak ada jadwal tersedia lainnya</option>
                                        @endif
                                    </select>
                                    @if(!isset($allAvailableSchedules) || count($allAvailableSchedules) === 0)
                                        <div class="mt-2 text-warning small">
                                            <i class="fas fa-exclamation-triangle me-1"></i> Menunggu jadwal tersedia (tidak ada jadwal lain saat ini)
                                        </div>
                                    @endif
                                </div>
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-warning fw-bold">Konfirmasi & Pindahkan Peserta</button>
                                </div>
                            </div>
                        @else
                            <div id="rescheduleSelection" class="d-none">
                                <!-- Hidden for non-SuperAdmin -->
                                <div class="d-grid mt-4">
                                    <button type="submit" class="btn btn-warning fw-bold">Simpan Status Izin</button>
                                </div>
                            </div>
                        @endif

                        <input type="hidden" name="attendance" id="attendanceInput">
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if(Auth::user()->isSuperAdmin())
        @include('admin.partials.modal-reschedule')
    @endif

    <!-- Reset Password Modal (List) -->
    <div class="modal fade" id="resetPasswordListModal" tabindex="-1" aria-labelledby="resetPasswordListModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header bg-warning text-dark rounded-top-4">
                    <h5 class="modal-title fw-bold" id="resetPasswordListModalLabel">Reset Kata Sandi Peserta</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.reset.participant.password') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <input type="hidden" name="participant_id" id="resetParticipantId">
                        <div class="mb-4">
                            <label class="form-label fw-bold text-muted">Peserta:</label>
                            <div class="p-3 bg-light rounded-3">
                                <div class="fw-bold" id="resetParticipantName"></div>
                                <div class="small text-muted">Username: <span id="resetParticipantUsername"></span></div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="new_password" class="form-label fw-bold">Kata Sandi Baru</label>
                            <input type="password" class="form-control rounded-3" id="new_password" name="new_password"
                                required minlength="12">
                            <small class="text-muted">Min. 12 karakter, huruf besar, kecil, angka, spesial.</small>
                        </div>
                        <div class="mb-3">
                            <label for="new_password_confirmation" class="form-label fw-bold">Konfirmasi Kata Sandi
                                Baru</label>
                            <input type="password" class="form-control rounded-3" id="new_password_confirmation"
                                name="new_password_confirmation" required>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary rounded-pill px-4"
                            data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-warning rounded-pill px-4 fw-bold shadow-sm">Reset
                            Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    </div>

    <!-- Global forms to avoid nesting -->
    <form id="globalDeleteForm" method="POST" class="d-none">
        @csrf
        @method('DELETE')
    </form>

@endsection

@section('scripts')
    <script nonce="{{ $csp_nonce ?? '' }}">
        document.addEventListener('DOMContentLoaded', function () {
            // 1. Delete All Confirmation
            const verifyBtn = document.getElementById('verifyDeleteAllBtn');
            if (verifyBtn) {
                verifyBtn.addEventListener('click', function () {
                    const expectedText = "HAPUS {{ $totalParticipants }} PESERTA";
                    const inputText = document.getElementById('confirmDeleteAll').value;
                    const errorElement = document.getElementById('confirmDeleteAllError');
                    const confirmForm = document.getElementById('clearAllParticipantsForm');
                    const confirmBtn = document.getElementById('deleteAllConfirmedBtn');

                    if (inputText.trim() === expectedText) {
                        errorElement.classList.add('d-none');
                        confirmForm.classList.remove('d-none');
                        confirmBtn.classList.remove('d-none');
                        this.disabled = true;
                    } else {
                        errorElement.classList.remove('d-none');
                        confirmForm.classList.add('d-none');
                        confirmBtn.classList.add('d-none');
                    }
                });
            }

            const deleteModalEl = document.getElementById('deleteAllModal');
            if (deleteModalEl) {
                deleteModalEl.addEventListener('hidden.bs.modal', function () {
                    document.getElementById('confirmDeleteAll').value = '';
                    document.getElementById('confirmDeleteAllError').classList.add('d-none');
                    document.getElementById('clearAllParticipantsForm').classList.add('d-none');
                    if (verifyBtn) verifyBtn.disabled = false;
                });
            }

            // 2. Open Attendance Modal
            document.querySelectorAll('.btn-open-attendance').forEach(btn => {
                btn.addEventListener('click', function () {
                    const id = this.getAttribute('data-id');
                    const name = this.getAttribute('data-name');
                    openAttendanceModal(id, name);
                });
            });

            // 3. Set Attendance Buttons
            document.querySelectorAll('.btn-set-attendance').forEach(btn => {
                btn.addEventListener('click', function () {
                    setAttendance(this.getAttribute('data-status'));
                });
            });

            // 4. Submit Reschedule
            const btnSubmitReschedule = document.getElementById('btn-submit-reschedule');
            if (btnSubmitReschedule) {
                btnSubmitReschedule.addEventListener('click', function () {
                    document.getElementById('rescheduleForm').submit();
                });
            }

            // 5. Open Reset Password Modal
            document.querySelectorAll('.btn-reset-password').forEach(btn => {
                btn.addEventListener('click', function () {
                    const id = this.getAttribute('data-id');
                    const name = this.getAttribute('data-name');
                    const username = this.getAttribute('data-username');
                    openResetPasswordModal(id, name, username);
                });
            });

            // 6. Delete Participant Confirm
            document.querySelectorAll('.btn-delete-participant').forEach(btn => {
                btn.addEventListener('click', function () {
                    const id = this.getAttribute('data-id');
                    const name = this.getAttribute('data-name');
                    if (confirm(`Apakah Anda yakin ingin menghapus peserta ${name}?`)) {
                        const form = document.getElementById('globalDeleteForm');
                        form.action = `/admin/participant/${id}/delete`;
                        form.submit();
                    }
                });
            });

            // 7. Bulk Validation
            const selectAll = document.getElementById('selectAll');
            if (selectAll) {
                selectAll.addEventListener('change', function () {
                    const checkboxes = document.querySelectorAll('.participant-checkbox');
                    checkboxes.forEach(cb => cb.checked = this.checked);
                    toggleBulkButton();
                });
            }

            document.querySelectorAll('.participant-checkbox').forEach(cb => {
                cb.addEventListener('change', toggleBulkButton);
            });

            // 8. WhatsApp Contact Listener
            document.querySelectorAll('.btn-whatsapp-contact').forEach(btn => {
                btn.addEventListener('click', function (e) {
                    e.preventDefault();
                    const phone = this.getAttribute('data-phone');
                    if (phone) {
                        sendWhatsApp(phone);
                    }
                });
            });
        });

        // Live Search with Debounce
        let typingTimer;
        let doneTypingInterval = 500;
        const searchInput = document.getElementById('search_nim_input');
        const searchForm = document.getElementById('searchForm');

        if (searchInput && searchForm) {
            // Put cursor at the end of input
            const val = searchInput.value;
            searchInput.value = '';
            searchInput.value = val;
            searchInput.focus();

            searchInput.addEventListener('input', function() {
                clearTimeout(typingTimer);
                typingTimer = setTimeout(() => {
                    searchForm.submit();
                }, doneTypingInterval);
            });
        }

        // Modal Objects
        const attendanceModalEl = document.getElementById('attendanceModal');
        const attendanceModal = attendanceModalEl ? new bootstrap.Modal(attendanceModalEl) : null;
        let currentParticipantId = null;

        function openAttendanceModal(id, name) {
            currentParticipantId = id;
            document.getElementById('attendanceParticipantName').textContent = name;
            document.getElementById('attendanceForm').action = `/admin/participant/${id}/attendance`;
            if (attendanceModal) attendanceModal.show();
        }

        if (attendanceModalEl) {
            attendanceModalEl.addEventListener('hidden.bs.modal', function() {
                document.getElementById('rescheduleSelection').classList.add('d-none');
                document.getElementById('btn-izin-attendance').classList.remove('active');
                document.getElementById('new_schedule_id_attendance').value = '';
            });
        }

        function setAttendance(status) {
            if (status === 'permission') {
                const selection = document.getElementById('rescheduleSelection');
                document.getElementById('attendanceInput').value = 'permission';
                if (selection.classList.contains('d-none')) {
                    selection.classList.remove('d-none');
                    // highlight the button
                    document.getElementById('btn-izin-attendance').classList.add('active');
                    return; // Don't submit yet, let user choose schedule
                }
            }
            
            document.getElementById('attendanceInput').value = status;
            document.getElementById('attendanceForm').submit();
        }

        const rescheduleModalEl = document.getElementById('rescheduleModal');
        const rescheduleModal = rescheduleModalEl ? new bootstrap.Modal(rescheduleModalEl) : null;
        function openRescheduleModal(id) {
            document.getElementById('rescheduleForm').action = `/admin/participant/${id}/reschedule`;
            if (rescheduleModal) rescheduleModal.show();
        }

        const resetModalEl = document.getElementById('resetPasswordListModal');
        const resetModal = resetModalEl ? new bootstrap.Modal(resetModalEl) : null;
        function openResetPasswordModal(id, name, username) {
            document.getElementById('resetParticipantId').value = id;
            document.getElementById('resetParticipantName').textContent = name;
            document.getElementById('resetParticipantUsername').textContent = username;
            if (resetModal) resetModal.show();
        }

        function toggleBulkButton() {
            const btn = document.getElementById('bulkValidateBtn');
            const selected = document.querySelectorAll('.participant-checkbox:checked').length;
            if (selected > 0) {
                if (btn) btn.classList.remove('d-none');
            } else {
                if (btn) btn.classList.add('d-none');
            }

            const form = document.getElementById('bulkValidateForm');
            if (form) {
                // Clear existing
                form.querySelectorAll('input[name="participant_ids[]"]').forEach(el => el.remove());

                document.querySelectorAll('.participant-checkbox:checked').forEach(cb => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'participant_ids[]';
                    input.value = cb.value;
                    form.appendChild(input);
                });
            }
        }

        function sendWhatsApp(phoneNumber) {
            const cleanedNumber = phoneNumber.replace(/\D/g, '');
            let waNumber = cleanedNumber.startsWith('62') ? cleanedNumber : (cleanedNumber.startsWith('0') ? '62' + cleanedNumber.substring(1) : '62' + cleanedNumber);
            window.open('https://wa.me/' + waNumber, '_blank');
        }

        document.addEventListener('DOMContentLoaded', function () {
            // Reschedule Modal Logic (Open)
            document.querySelectorAll('.btn-reschedule').forEach(btn => {
                btn.addEventListener('click', function () {
                    const id = this.getAttribute('data-id');
                    openRescheduleModal(id);
                });
            });

            // Bulk Validate Button Listener
            const bulkBtn = document.getElementById('bulkValidateBtn');
            if (bulkBtn) {
                bulkBtn.addEventListener('click', function () {
                    const form = document.getElementById('bulkValidateForm');
                    if (form) form.submit();
                });
            }

            // Reschedule Modal Logic (Submit)
            const btnSubmitReschedule = document.getElementById('btn-submit-reschedule');
            if (btnSubmitReschedule) {
                btnSubmitReschedule.addEventListener('click', function () {
                    document.getElementById('rescheduleForm').submit();
                });
            }

            // Individual Validate & Publish Button Listener
            const validateBtn = document.getElementById('btn-validate-publish');
            if (validateBtn) {
                validateBtn.addEventListener('click', function () {
                    const form = document.getElementById('validate-form');
                    if (form) form.submit();
                });
            }

            // 9. Bulk Score Entry Toggle
            const toggleBulkBtn = document.getElementById('toggleBulkInput');
            if (toggleBulkBtn) {
                toggleBulkBtn.addEventListener('click', function() {
                    const displayElements = document.querySelectorAll('.score-display');
                    const inputElements = document.querySelectorAll('.score-input');
                    const footer = document.getElementById('bulkScoreFooter');
                    const title = document.getElementById('scoreColumnTitle');
                    
                    const isInputMode = inputElements[0].classList.contains('d-none');
                    
                    if (isInputMode) {
                        displayElements.forEach(el => el.classList.add('d-none'));
                        inputElements.forEach(el => el.classList.remove('d-none'));
                        footer.classList.remove('d-none');
                        title.innerText = "Input Nilai Raw (L/S/R)";
                        this.innerHTML = '<i class="fas fa-times me-1"></i> Batal Input';
                        this.classList.replace('btn-outline-primary', 'btn-outline-danger');
                    } else {
                        displayElements.forEach(el => el.classList.remove('d-none'));
                        inputElements.forEach(el => el.classList.add('d-none'));
                        footer.classList.add('d-none');
                        title.innerText = "Status Nilai";
                        this.innerHTML = '<i class="fas fa-edit me-1"></i> Mode Input Nilai';
                        this.classList.replace('btn-outline-danger', 'btn-outline-primary');
                    }
                });
            }

            // 10. Live Score Calculation
            const pbtConversionTable = {
                50: [68, 68, 67], 49: [67, 67, 66], 48: [66, 65, 65], 47: [65, 63, 63], 46: [63, 61, 61],
                45: [62, 60, 60], 44: [61, 58, 59], 43: [60, 57, 58], 42: [59, 56, 57], 41: [58, 55, 56],
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

            document.querySelectorAll('.raw-score').forEach(input => {
                input.addEventListener('input', function() {
                    const id = this.getAttribute('data-id');
                    const listening = parseInt(document.querySelector(`input[name="scores[${id}][listening]"]`).value) || 0;
                    const structure = parseInt(document.querySelector(`input[name="scores[${id}][structure]"]`).value) || 0;
                    const reading = parseInt(document.querySelector(`input[name="scores[${id}][reading]"]`).value) || 0;

                    // Convert to scaled
                    const lScaled = pbtConversionTable[Math.min(50, Math.max(0, listening))][0];
                    const sScaled = pbtConversionTable[Math.min(40, Math.max(0, structure))][1];
                    const rScaled = pbtConversionTable[Math.min(50, Math.max(0, reading))][2];

                    // Total
                    const total = Math.round((lScaled + sScaled + rScaled) * 10 / 3);
                    
                    const totalPreview = document.getElementById(`total_${id}`);
                    const statusPreview = document.getElementById(`pass_fail_${id}`);
                    
                    if (totalPreview) totalPreview.innerText = total;

                    // Calculate PASS/FAIL
                    if (statusPreview) {
                        const level = document.querySelector(`.academic-level[data-id="${id}"]`).value;
                        const customPassingGrade = document.querySelector(`.passing-grade[data-id="${id}"]`).value;
                        
                        let pass = false;
                        if (customPassingGrade) {
                            pass = total >= parseInt(customPassingGrade);
                        } else {
                            if (level === 'undergraduate') pass = total >= 410;
                            else if (level === 'master') pass = total >= 450;
                            else if (level === 'doctorate') pass = total >= 500;
                            else pass = total >= 410;
                        }

                        statusPreview.innerText = pass ? '(PASS)' : '(FAIL)';
                        statusPreview.className = pass ? 'fw-bold text-success' : 'fw-bold text-danger';
                    }
                });
            });
        });
    </script>
@endsection