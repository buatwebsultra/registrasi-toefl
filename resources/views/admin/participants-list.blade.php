@extends('layouts.app')

@section('title', 'Daftar Peserta')

@section('content')
<div class="row">
    <div class="col-md-12">
        <h1>Peserta untuk {{ $schedule->room }} - {{ $schedule->date->format('d M Y') }}</h1>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary mb-3">Kembali ke Dashboard</a>
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
                <h5 class="mb-0">Cari Peserta berdasarkan NIM</h5>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('admin.participants.list', $schedule->id) }}">
                    <div class="row">
                        <div class="col-md-8">
                            <input type="text"
                                   name="search_nim"
                                   class="form-control"
                                   placeholder="Masukkan NIM peserta..."
                                   value="{{ request('search_nim') }}">
                        </div>
                        <div class="col-md-4">
                            <div class="d-grid gap-2 d-md-flex">
                                <button type="submit" class="btn btn-primary flex-fill">
                                    <i class="fas fa-search"></i> Cari
                                </button>
                                <a href="{{ route('admin.participants.list', $schedule->id) }}" class="btn btn-secondary flex-fill">
                                    <i class="fas fa-times"></i> Reset
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
                <a href="{{ route('admin.schedule.participants.export', $schedule->id) }}" class="btn btn-success">
                    <i class="fas fa-download"></i> Download Seluruh Peserta
                </a>
                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteAllModal">
                    <i class="fas fa-trash-alt"></i> Hapus Seluruh Peserta
                </button>
            </div>
            
            <form id="bulkValidateForm" action="{{ route('admin.participants.score.bulk-validate') }}" method="POST" class="d-none">
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
                        <strong>Tanggal:</strong> {{ $schedule->date->format('d M Y') }}</p>
                        <p><strong>Jumlah Peserta:</strong> {{ $totalParticipants }}</p>
                        <p class="text-danger">Tindakan ini tidak dapat dibatalkan. Semua peserta akan dihapus secara permanen.</p>
                        <p>Mohon ketik <strong>"HAPUS {{ $totalParticipants }} PESERTA"</strong> untuk mengonfirmasi penghapusan:</p>
                        <input type="text" class="form-control" id="confirmDeleteAll" placeholder="Ketik perintah penghapusan">
                        <div id="confirmDeleteAllError" class="text-danger mt-1 d-none">Perintah penghapusan tidak sesuai</div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <form action="{{ route('admin.schedule.clear-participants', $schedule->id) }}" method="POST" class="d-inline d-none" id="clearAllParticipantsForm">
                            @csrf
                            <button type="submit" class="btn btn-danger" id="deleteAllConfirmedBtn">Konfirmasi Hapus Semua</button>
                        </form>
                        <button type="button" class="btn btn-danger" id="verifyDeleteAllBtn">Verifikasi dan Hapus</button>
                    </div>
                </div>
            </div>
        </div>

        <script nonce="{{ $csp_nonce ?? '' }}">
        document.addEventListener('DOMContentLoaded', function() {
            const verifyBtn = document.getElementById('verifyDeleteAllBtn');
            if(verifyBtn) {
                verifyBtn.addEventListener('click', function() {
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

            const deleteModal = document.getElementById('deleteAllModal');
            if(deleteModal) {
                deleteModal.addEventListener('hidden.bs.modal', function() {
                    document.getElementById('confirmDeleteAll').value = '';
                    document.getElementById('confirmDeleteAllError').classList.add('d-none');
                    document.getElementById('clearAllParticipantsForm').classList.add('d-none');
                    if(verifyBtn) verifyBtn.disabled = false;
                });
            }
        });
        </script>
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th class="w-40px"><input type="checkbox" id="selectAll"></th>
                        <th>Nomor Kursi</th>
                        <th>NIM</th>
                        <th>Nama</th>
                        <th>Prodi</th>
                        <th>Status Nilai</th>
                        <th>Status Verifikasi</th>
                        <th>Kehadiran</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($participants as $participant)
                    <tr>
                        <td>
                            @if($participant->test_score && !$participant->is_score_validated)
                                <input type="checkbox" class="participant-checkbox" name="participant_ids[]" value="{{ $participant->id }}">
                            @else
                                <span class="text-muted opacity-25"><i class="fas fa-minus"></i></span>
                            @endif
                        </td>
                        <td>{{ $participant->effective_seat_number }}</td>
                        <td>{{ $participant->nim }}</td>
                        <td>{{ $participant->name }}</td>
                        <td>{{ $participant->studyProgram->name }} {{ $participant->studyProgram->level }}</td>
                        <td>
                            @if($participant->test_score)
                                @if($participant->is_score_validated)
                                    <span class="badge bg-primary"><i class="fas fa-check-circle me-1"></i>Tervalidasi</span>
                                @else
                                    <span class="badge bg-secondary"><i class="fas fa-hourglass-half me-1"></i>Menunggu Validasi</span>
                                @endif
                                <div class="small fw-bold text-dark mt-1">{{ number_format($participant->test_score, 0, '', '') }} ({{ $participant->passed ? 'PASS' : 'FAIL' }})</div>
                            @else
                                <span class="text-muted small">Belum dinput</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-{{ $participant->status === 'confirmed' ? 'success' : ($participant->status === 'pending' ? 'warning' : ($participant->status === 'rejected' ? 'danger' : 'secondary')) }}">
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
                            <button type="button" 
                                    class="btn btn-sm btn-link p-0 ms-1 btn-open-attendance" 
                                    data-id="{{ $participant->id }}"
                                    data-name="{{ $participant->name }}"
                                    data-attendance="{{ $participant->attendance }}"
                                    {{ $participant->passed ? 'disabled title="Tidak dapat mengubah kehadiran peserta yang sudah Lulus"' : ($participant->status !== 'confirmed' ? 'disabled title="Peserta belum terkonfirmasi"' : '') }}>
                                <i class="fas fa-edit {{ ($participant->passed || $participant->status !== 'confirmed') ? 'text-muted' : '' }}"></i>
                            </button>
                        </td>
                        <td>
                            <a href="{{ route('admin.participant.details', $participant->id) }}" class="btn btn-info btn-sm me-1">Lihat Detail</a>
                            @if(Auth::user()->isOperator())
                            <button type="button" 
                                    class="btn btn-warning btn-sm me-1 btn-reset-password" 
                                    data-id="{{ $participant->id }}"
                                    data-name="{{ $participant->name }}"
                                    data-username="{{ $participant->username }}">
                                <i class="fas fa-key"></i> Reset
                            </button>
                            <form action="{{ route('admin.participant.delete', $participant->id) }}" method="POST" class="d-inline form-delete-participant">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        @if($searchNim)
                            <td colspan="9" class="text-center">Tidak ditemukan peserta dengan NIM: {{ $searchNim }}</td>
                        @else
                            <td colspan="9" class="text-center">Tidak ada peserta terdaftar untuk jadwal ini</td>
                        @endif
                    </tr>
                    @endforelse
                </tbody>
            </table>
            @if($participants->hasPages())
                <div class="d-flex justify-content-center">
                    {{ $participants->appends(['search_nim' => $searchNim])->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Attendance Modal -->
<div class="modal fade" id="attendanceModal" tabindex="-1" aria-labelledby="attendanceModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="attendanceModalLabel">Set Kehadiran - <span id="attendanceParticipantName"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="attendanceForm" action="" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-outline-success p-3 text-start btn-set-attendance" data-status="present">
                            <i class="fas fa-check-circle me-2"></i> <strong>Hadir</strong>
                            <div class="small text-muted ms-4">Peserta hadir dan mengikuti ujian.</div>
                        </button>
                        
                        <button type="button" class="btn btn-outline-danger p-3 text-start btn-set-attendance" data-status="absent">
                            <i class="fas fa-times-circle me-2"></i> <strong>Tidak Hadir</strong>
                            <div class="small text-muted ms-4">Peserta tidak hadir (Otomatis Gagal).</div>
                        </button>
                        
                        <button type="button" class="btn btn-outline-warning p-3 text-start btn-set-attendance" data-status="permission">
                            <i class="fas fa-clock me-2"></i> <strong>Izin (Reschedule)</strong>
                            <div class="small text-muted ms-4">Peserta minta pindah jadwal.</div>
                        </button>
                    </div>
                    
                    <input type="hidden" name="attendance" id="attendanceInput">
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Reschedule Modal -->
<div class="modal fade" id="rescheduleModal" tabindex="-1" aria-labelledby="rescheduleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rescheduleModalLabel">Pilih Jadwal Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Silakan pilih jadwal tes pengganti untuk peserta ini:</p>
                <form id="rescheduleForm" action="" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="new_schedule_id" class="form-label">Jadwal Tersedia</label>
                        <select class="form-select" name="new_schedule_id" id="new_schedule_id" required>
                            <option value="">-- Pilih Jadwal --</option>
                            @php
                                // Fetch available schedules logic should ideally be passed from controller, 
                                // but for now we can use a direct query or View Composer. 
                                // TO AVOID N+1 or logic in view, a better way is to pass it from controller.
                                // For this prompt, I'll assume we can pass $availableSchedules from controller or fetch here for simplicity.
                                $availableSchedules = \App\Models\Schedule::where('status', 'available')
                                    ->where('id', '!=', $schedule->id)
                                    ->whereColumn('used_capacity', '<', 'capacity')
                                    ->where('date', '>=', now())
                                    ->orderBy('date')
                                    ->get();
                            @endphp
                            @foreach($availableSchedules as $s)
                                <option value="{{ $s->id }}">
                                    {{ $s->date->format('d M Y') }} - {{ $s->room }} ({{ $s->used_capacity }}/{{ $s->capacity }})
                                </option>
                            @endforeach
                        </select>
                        @if($availableSchedules->isEmpty())
                            <div class="text-danger mt-2 small">
                                <i class="fas fa-exclamation-circle"></i> Tidak ada jadwal lain yang tersedia. Silakan buat jadwal baru terlebih dahulu.
                            </div>
                        @endif
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="btn-submit-reschedule">Pindahkan Peserta</button>
            </div>
        </div>
    </div>
</div>

<script nonce="{{ $csp_nonce ?? '' }}">
    document.addEventListener('DOMContentLoaded', function() {
        
        // 2. Open Attendance Modal
        document.querySelectorAll('.btn-open-attendance').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const name = this.getAttribute('data-name');
                openAttendanceModal(id, name);
            });
        });

        // 3. Set Attendance Buttons
        document.querySelectorAll('.btn-set-attendance').forEach(btn => {
            btn.addEventListener('click', function() {
                setAttendance(this.getAttribute('data-status'));
            });
        });

        // 4. Submit Reschedule
        const btnSubmitReschedule = document.getElementById('btn-submit-reschedule');
        if(btnSubmitReschedule) {
            btnSubmitReschedule.addEventListener('click', function() {
                document.getElementById('rescheduleForm').submit();
            });
        }

        // 5. Open Reset Password Modal
        document.querySelectorAll('.btn-reset-password').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const name = this.getAttribute('data-name');
                const username = this.getAttribute('data-username');
                openResetPasswordModal(id, name, username);
            });
        });

        // 6. Delete Participant Confirm
        document.querySelectorAll('.form-delete-participant').forEach(form => {
            form.addEventListener('submit', function(e) {
                if(!confirm('Apakah Anda yakin ingin menghapus peserta ini?')) {
                    e.preventDefault();
                }
            });
        });

        // 7. Bulk Validation
        const selectAll = document.getElementById('selectAll');
        if(selectAll) {
            selectAll.addEventListener('change', function() {
                const checkboxes = document.querySelectorAll('.participant-checkbox');
                checkboxes.forEach(cb => cb.checked = this.checked);
                toggleBulkButton();
            });
        }

        document.querySelectorAll('.participant-checkbox').forEach(cb => {
            cb.addEventListener('change', toggleBulkButton);
        });

        const bulkValidateBtn = document.getElementById('bulkValidateBtn');
        if(bulkValidateBtn) {
            bulkValidateBtn.addEventListener('click', function() {
                if (confirm('Apakah Anda yakin ingin memvalidasi semua nilai yang dipilih?')) {
                    document.getElementById('bulkValidateForm').submit();
                }
            });
        }

    }); // End DOMContentLoaded

    let currentParticipantId = null;

    function openAttendanceModal(id, name) {
        currentParticipantId = id;
        document.getElementById('attendanceParticipantName').textContent = name;
        new bootstrap.Modal(document.getElementById('attendanceModal')).show();
    }

    function setAttendance(status) {
        const modalEl = document.getElementById('attendanceModal');
        const modal = bootstrap.Modal.getInstance(modalEl);
        
        if (status === 'permission') {
            modal.hide();
            // Open Reschedule Modal
            // We need to set the action of reschedule form?
            // The original code set it to /participant/{id}/reschedule
            const form = document.getElementById('rescheduleForm');
            form.action = `/admin/participant/${currentParticipantId}/reschedule`;
            
            new bootstrap.Modal(document.getElementById('rescheduleModal')).show();
            return;
        }

        const form = document.getElementById('attendanceForm');
        form.action = `/admin/participant/${currentParticipantId}/attendance`;
        document.getElementById('attendanceInput').value = status;
        form.submit();
    }

    function openResetPasswordModal(id, name, username) {
        document.getElementById('resetParticipantId').value = id;
        document.getElementById('resetParticipantName').textContent = name;
        document.getElementById('resetParticipantUsername').textContent = username;
        new bootstrap.Modal(document.getElementById('resetPasswordListModal')).show();
    }

    function toggleBulkButton() {
        const btn = document.getElementById('bulkValidateBtn');
        const selected = document.querySelectorAll('.participant-checkbox:checked').length;
        if (selected > 0) {
            btn.classList.remove('d-none'); // This relies on d-none class being available
        } else {
            btn.classList.add('d-none');
        }
        
        const form = document.getElementById('bulkValidateForm');
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
</script>

<!-- Reset Password Modal (List) -->
<div class="modal fade" id="resetPasswordListModal" tabindex="-1" aria-labelledby="resetPasswordListModalLabel" aria-hidden="true">
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
                        <input type="password" class="form-control rounded-3" id="new_password" name="new_password" required minlength="12">
                        <small class="text-muted">Min. 12 karakter, huruf besar, kecil, angka, spesial.</small>
                    </div>
                    <div class="mb-3">
                        <label for="new_password_confirmation" class="form-label fw-bold">Konfirmasi Kata Sandi Baru</label>
                        <input type="password" class="form-control rounded-3" id="new_password_confirmation" name="new_password_confirmation" required>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning rounded-pill px-4 fw-bold shadow-sm">Reset Password</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection