@extends('layouts.app')

@section('title', 'Peserta Tertunda')

@section('content')
<div class="row">
    <div class="col-md-12">
        <h1>Validasi Peserta Tertunda</h1>
        <p class="lead">Daftar peserta yang menunggu konfirmasi pembayaran</p>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary mb-3">Kembali ke Dashboard</a>
    </div>
</div>
@if(session('success'))
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        </div>
    </div>
@endif

@if(session('error'))
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        </div>
    </div>
@endif
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.participants.pending') }}" method="GET" class="row g-3 align-items-center">
                    <div class="col-auto">
                        <label for="schedule_id" class="col-form-label fw-bold">Filter Berdasarkan Jadwal:</label>
                    </div>
                    <div class="col-auto flex-grow-1">
                        <select name="schedule_id" id="schedule_id" class="form-select">
                            <option value="">-- Tampilkan Semua Jadwal --</option>
                            @foreach($schedules as $schedule)
                                <option value="{{ $schedule->id }}" {{ request('schedule_id') == $schedule->id ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::parse($schedule->date)->format('d M Y') }} - {{ $schedule->room }} ({{ \Carbon\Carbon::parse($schedule->time)->format('H:i') }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-auto">
                        @if(request('schedule_id'))
                            <a href="{{ route('admin.participants.pending') }}" class="btn btn-outline-secondary">Reset Filter</a>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5>Daftar Peserta Tertunda ({{ $pendingParticipants->count() }})</h5>
            </div>
            <div class="card-body">
                @if($pendingParticipants->isEmpty())
                    <div class="text-center py-5">
                        <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                        <h5>Tidak ada peserta tertunda</h5>
                        <p class="text-muted">Semua pendaftaran telah diproses</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>NIM</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Jurusan</th>
                                    <th>Fakultas</th>
                                    <th>Kategori Tes</th>
                                    <th>Jadwal</th>
                                    <th>Tanggal Daftar</th>
                                    <th>Aksi</th>
                                    <th>WhatsApp</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pendingParticipants as $participant)
                                <tr>
                                    <td>{{ $participant->nim }}</td>
                                    <td>{{ $participant->name }}</td>
                                    <td>{{ $participant->email }}</td>
                                    <td>{{ $participant->studyProgram->name }} {{ $participant->studyProgram->level }}</td>
                                    <td>{{ $participant->faculty }}</td>
                                    <td>{{ $participant->test_category }}</td>
                                    <td>
                                        {{ $participant->schedule->room }} - 
                                        {{ $participant->schedule->date->format('d M Y') }}
                                    </td>
                                    <td>{{ $participant->created_at->format('d M Y H:i') }}</td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#validationModal{{ $participant->id }}">
                                            <i class="fas fa-eye"></i> Validasi
                                        </button>
                                    </td>
                                    <td>
                                        @if($participant->phone)
                                            <a href="#" class="btn btn-sm btn-success btn-whatsapp-contact" data-phone="{{ $participant->phone }}">
                                                <i class="fab fa-whatsapp"></i> Chat
                                            </a>
                                        @else
                                            <span class="text-muted small">-</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>


<!-- Validation Modals -->
@foreach($pendingParticipants as $participant)
<div class="modal fade" id="validationModal{{ $participant->id }}" tabindex="-1" aria-labelledby="validationModalLabel{{ $participant->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="validationModalLabel{{ $participant->id }}">Validasi Pembayaran - {{ $participant->name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <!-- Left Column: Participant Details -->
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-header bg-primary text-white">
                                <h6 class="mb-0"><i class="fas fa-user me-2"></i>Detail Peserta</h6>
                            </div>
                            <div class="card-body">
                                <table class="table table-sm table-borderless mb-0">
                                    <tr>
                                        <td width="40%" class="text-muted"><small>NIM</small></td>
                                        <td width="5%">:</td>
                                        <td><strong>{{ $participant->nim }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted"><small>Nama</small></td>
                                        <td>:</td>
                                        <td><strong>{{ $participant->name }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted"><small>Email</small></td>
                                        <td>:</td>
                                        <td>{{ $participant->email }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted"><small>Jurusan</small></td>
                                        <td>:</td>
                                        <td>{{ $participant->studyProgram->name }} {{ $participant->studyProgram->level }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted"><small>Fakultas</small></td>
                                        <td>:</td>
                                        <td>{{ $participant->faculty }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted"><small>Kategori Tes</small></td>
                                        <td>:</td>
                                        <td><span class="badge bg-info">{{ $participant->test_category }}</span></td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted"><small>Jadwal</small></td>
                                        <td>:</td>
                                        <td>{{ $participant->schedule->room }} - {{ $participant->schedule->date->format('d M Y') }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted"><small>Tanggal Pembayaran</small></td>
                                        <td>:</td>
                                        <td><span class="badge bg-secondary">{{ $participant->payment_date ? $participant->payment_date->format('d M Y H:i:s') : '-' }}</span></td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted"><small>WhatsApp</small></td>
                                        <td>:</td>
                                        <td>
                                            @if($participant->phone)
                                                <a href="#" class="text-success fw-bold text-decoration-none btn-whatsapp-contact" data-phone="{{ $participant->phone }}">
                                                    <i class="fab fa-whatsapp me-1"></i> {{ $participant->phone }}
                                                </a>
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Payment Proof -->
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-header bg-success text-white">
                                <h6 class="mb-0"><i class="fas fa-receipt me-2"></i>Bukti Pembayaran</h6>
                            </div>
                            <div class="card-body text-center">
                                @php
                                    $prevProofRoute = null;
                                    $isDuplicate = false;
                                    
                                    // Case 1: Proper Retake (Same ID, proof archived)
                                    if ($participant->previous_payment_proof_path) {
                                        $prevProofRoute = route('participant.file.download', ['id' => $participant->id, 'type' => 'previous_payment_proof']);
                                    } 
                                    // Case 2: Duplicate Registration (Different ID, same NIM)
                                    elseif ($prevParticipant = $participant->previous_participation) {
                                        if ($prevParticipant->payment_proof_path) {
                                            $prevProofRoute = route('participant.file.download', ['id' => $prevParticipant->id, 'type' => 'payment_proof']);
                                            $isDuplicate = true;
                                        }
                                    }
                                @endphp

                                @if($prevProofRoute)
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <div class="card h-100 border-primary">
                                                <div class="card-header bg-primary text-white py-1">
                                                    <small class="fw-bold">Baru (Saat Ini)</small>
                                                    <br>
                                                    <small class="fs-07rem">
                                                        {{ $participant->payment_date ? $participant->payment_date->format('d M Y H:i:s') : '-' }}
                                                    </small>
                                                </div>
                                                <div class="card-body p-2 d-flex align-items-center justify-content-center" style="min-height: 150px;">
                                                    <a href="{{ route('participant.file.download', ['id' => $participant->id, 'type' => 'payment_proof']) }}" target="_blank">
                                                        <img src="{{ route('participant.file.download', ['id' => $participant->id, 'type' => 'payment_proof']) }}" 
                                                              alt="Bukti Baru" 
                                                              class="img-fluid rounded shadow-sm"
                                                              style="max-height: 150px;">
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="card h-100 border-secondary">
                                                <div class="card-header bg-secondary text-white py-1">
                                                    <small class="fw-bold">Lama (Sebelumnya)</small>
                                                    <br>
                                                    <small class="fs-07rem">
                                                        @if($isDuplicate && isset($prevParticipant))
                                                            {{ $prevParticipant->payment_date ? $prevParticipant->payment_date->format('d M Y H:i:s') : '-' }}
                                                        @elseif($participant->previous_payment_proof_path)
                                                            {{ $participant->created_at->format('d M Y H:i:s') }}
                                                        @else
                                                            -
                                                        @endif
                                                    </small>
                                                </div>
                                                <div class="card-body p-2 d-flex align-items-center justify-content-center" style="min-height: 150px;">
                                                    <a href="{{ $prevProofRoute }}" target="_blank">
                                                        <img src="{{ $prevProofRoute }}" 
                                                              alt="Bukti Lama" 
                                                              class="img-fluid rounded shadow-sm opacity-75"
                                                              style="max-height: 150px;">
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="alert alert-info mt-2 py-2 mb-0" role="alert">
                                        <small><i class="fas fa-info-circle"></i> 
                                            @php
                                                $retakeCount = \App\Models\Participant::where('nim', $participant->nim)
                                                    ->where('created_at', '<', $participant->created_at)
                                                    ->count();
                                            @endphp
                                            <strong>Pendaftaran Ulang Ke-{{ $retakeCount }}</strong>
                                        </small>
                                    </div>
                                @else
                                    <a href="{{ route('participant.file.download', ['id' => $participant->id, 'type' => 'payment_proof']) }}" target="_blank">
                                        <img src="{{ route('participant.file.download', ['id' => $participant->id, 'type' => 'payment_proof']) }}" 
                                              alt="Bukti Pembayaran" 
                                              class="img-fluid rounded shadow border"
                                              style="max-height: 250px;">
                                    </a>
                                    <small class="text-muted d-block mt-2">
                                        <i class="fas fa-search-plus"></i> Klik gambar untuk memperbesar
                                    </small>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Date Editor Card -->
                <div class="row mt-3">
                    <div class="col-12">
                        <div class="card border-warning shadow-sm">
                            <div class="card-header bg-warning bg-opacity-10 border-warning">
                                <h6 class="mb-0 text-dark">
                                    <i class="fas fa-calendar-edit me-2"></i>Edit Tanggal & Waktu Pembayaran
                                </h6>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('admin.participant.confirm', $participant->id) }}" method="POST" id="confirmForm{{ $participant->id }}">
                                    @csrf
                                    @method('PUT')
                                    
                                    <div class="row g-3 align-items-end">
                                        <div class="col-md-5">
                                            <label class="form-label fw-semibold text-muted mb-1">
                                                <i class="fas fa-calendar me-1"></i> Tanggal
                                            </label>
                                            <input type="date" 
                                                   class="form-control" 
                                                   name="payment_date" 
                                                   value="{{ $participant->payment_date ? $participant->payment_date->format('Y-m-d') : date('Y-m-d') }}"
                                                   required>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label fw-semibold text-muted mb-1">
                                                <i class="fas fa-clock me-1"></i> Jam
                                            </label>
                                            <input type="number" 
                                                   class="form-control text-center" 
                                                   name="payment_hour" 
                                                   min="0" 
                                                   max="23" 
                                                   value="{{ $participant->payment_date ? $participant->payment_date->format('H') : '00' }}"
                                                   placeholder="00"
                                                   required>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label fw-semibold text-muted mb-1">Menit</label>
                                            <input type="number" 
                                                   class="form-control text-center" 
                                                   name="payment_minute" 
                                                   min="0" 
                                                   max="59" 
                                                   value="{{ $participant->payment_date ? $participant->payment_date->format('i') : '00' }}"
                                                   placeholder="00"
                                                   required>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label fw-semibold text-muted mb-1">Detik</label>
                                            <input type="number" 
                                                   class="form-control text-center" 
                                                   name="payment_second" 
                                                   min="0" 
                                                   max="59" 
                                                   value="{{ $participant->payment_date ? $participant->payment_date->format('s') : '00' }}"
                                                   placeholder="00"
                                                   required>
                                        </div>
                                    </div>
                                    
                                    <div class="alert alert-info mt-3 mb-0 py-2">
                                        <small>
                                            <i class="fas fa-info-circle me-1"></i>
                                            <strong>Petunjuk:</strong> Sesuaikan tanggal dan waktu dengan yang tertera pada bukti pembayaran di atas
                                        </small>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Additional Info -->
                <div class="row mt-3">
                    <div class="col-12">
                        <div class="card border-0 bg-light">
                            <div class="card-body py-2">
                                <div class="row text-center">
                                    <div class="col-6">
                                        <small class="text-muted d-block">Tanggal Daftar</small>
                                        <strong>{{ $participant->created_at->format('d M Y H:i') }}</strong>
                                    </div>
                                    <div class="col-6">
                                        <small class="text-muted d-block">Status Saat Ini</small>
                                        <span class="badge bg-warning">{{ $participant->status }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $participant->id }}">
                    <i class="fas fa-times me-1"></i> Tolak Pendaftaran
                </button>
                <button type="submit" form="confirmForm{{ $participant->id }}" class="btn btn-success btn-lg px-4">
                    <i class="fas fa-check me-1"></i> Konfirmasi & Simpan
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Reject Confirmation Modal -->
<div class="modal fade" id="rejectModal{{ $participant->id }}" tabindex="-1" aria-labelledby="rejectModalLabel{{ $participant->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="rejectModalLabel{{ $participant->id }}">Konfirmasi Penolakan</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.participant.reject', $participant->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menolak pendaftaran ini?</p>
                    <div class="mb-3">
                        <label for="rejection_message_{{ $participant->id }}" class="form-label">Pesan Penolakan (opsional):</label>
                        <textarea class="form-control" id="rejection_message_{{ $participant->id }}" name="rejection_message" rows="3" placeholder="Masukkan alasan penolakan...">{{ old('rejection_message') }}</textarea>
                        <div class="form-text">Pesan ini akan ditampilkan ke peserta di dashboard mereka.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Tolak Pendaftaran</button>
                </div>
            </form>
        </div>
    </div>
</div>
        </div>
    </div>
</div>
@endforeach

@endsection

@section('scripts')
<script nonce="{{ $csp_nonce ?? '' }}">
    function sendWhatsApp(phoneNumber) {
        const cleanedNumber = phoneNumber.replace(/\D/g, '');
        let waNumber = cleanedNumber.startsWith('62') ? cleanedNumber : (cleanedNumber.startsWith('0') ? '62' + cleanedNumber.substring(1) : '62' + cleanedNumber);
        window.open('https://wa.me/' + waNumber, '_blank');
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Schedule Filter Listener
        const scheduleSelect = document.getElementById('schedule_id');
        if (scheduleSelect) {
            scheduleSelect.addEventListener('change', function() {
                this.form.submit();
            });
        }

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
    });
</script>
@endsection