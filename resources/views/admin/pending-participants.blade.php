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
                    <div class="col-md-6">
                        <h6>Detail Peserta</h6>
                        <table class="table table-sm border-0">
                            <tr>
                                <td width="40%"><strong>NIM</strong></td>
                                <td width="5%">:</td>
                                <td>{{ $participant->nim }}</td>
                            </tr>
                            <tr>
                                <td><strong>Nama</strong></td>
                                <td>:</td>
                                <td>{{ $participant->name }}</td>
                            </tr>
                            <tr>
                                <td><strong>Email</strong></td>
                                <td>:</td>
                                <td>{{ $participant->email }}</td>
                            </tr>
                            <tr>
                                <td><strong>Jurusan</strong></td>
                                <td>:</td>
                                <td>{{ $participant->studyProgram->name }} {{ $participant->studyProgram->level }}</td>
                            </tr>
                            <tr>
                                <td><strong>Fakultas</strong></td>
                                <td>:</td>
                                <td>{{ $participant->faculty }}</td>
                            </tr>
                            <tr>
                                <td><strong>Kategori Tes</strong></td>
                                <td>:</td>
                                <td>{{ $participant->test_category }}</td>
                            </tr>
                            <tr>
                                <td><strong>Jadwal</strong></td>
                                <td>:</td>
                                <td>{{ $participant->schedule->room }} - {{ $participant->schedule->date->format('d M Y') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Tanggal Pembayaran</strong></td>
                                <td>:</td>
                                <td>{{ $participant->payment_date ? $participant->payment_date->format('d M Y H:i:s') : '-' }}</td>
                            </tr>
                            <tr>
                                <td><strong>WhatsApp</strong></td>
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
                    <div class="col-md-6">
                        <h6>Bukti Pembayaran</h6>
                        <div class="text-center mb-3">
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
                                <div class="row">
                                    <div class="col-6">
                                        <div class="card h-100 border-primary">
                                            <div class="card-header bg-primary text-white py-1">
                                                <small>Baru (Saat Ini)</small>
                                                <br>
                                                <small class="fs-07rem">
                                                    Input: {{ $participant->payment_date ? $participant->payment_date->format('d M Y H:i:s') : '-' }}
                                                </small>
                                            </div>
                                            <div class="card-body p-2 d-flex align-items-center justify-content-center">
                                                <a href="{{ route('participant.file.download', ['id' => $participant->id, 'type' => 'payment_proof']) }}" target="_blank">
                                                    <img src="{{ route('participant.file.download', ['id' => $participant->id, 'type' => 'payment_proof']) }}" 
                                                          alt="Bukti Baru" 
                                                          class="img-fluid rounded shadow-sm mh-150px">
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="card h-100 border-secondary">
                                            <div class="card-header bg-secondary text-white py-1">
                                                <small>Lama (Sebelumnya)</small>
                                                <br>
                                                <small class="fs-07rem">
                                                    @if($isDuplicate && isset($prevParticipant))
                                                        Input: {{ $prevParticipant->payment_date ? $prevParticipant->payment_date->format('d M Y H:i:s') : '-' }}
                                                    @elseif($participant->previous_payment_proof_path)
                                                        {{-- For same-ID retakes, we lost the old payment_date, show created_at as approx --}}
                                                        Reg: {{ $participant->created_at->format('d M Y H:i:s') }}
                                                    @else
                                                        -
                                                    @endif
                                                </small>
                                            </div>
                                            <div class="card-body p-2 d-flex align-items-center justify-content-center">
                                                <a href="{{ $prevProofRoute }}" target="_blank">
                                                    <img src="{{ $prevProofRoute }}" 
                                                          alt="Bukti Lama" 
                                                          class="img-fluid rounded shadow-sm opacity-75 mh-150px">
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="alert alert-info mt-2 py-1 px-2" role="alert">
                                    <small><i class="fas fa-info-circle"></i> 
                                        @php
                                            // Count how many times this NIM has appeared BEFORE this current record
                                            $retakeCount = \App\Models\Participant::where('nim', $participant->nim)
                                                ->where('created_at', '<', $participant->created_at)
                                                ->count();
                                        @endphp
                                        Peserta ini Mendaftar Ulang Ke {{ $retakeCount }}
                                    </small>
                                </div>
                            @else
                                <a href="{{ route('participant.file.download', ['id' => $participant->id, 'type' => 'payment_proof']) }}" target="_blank">
                                    <img src="{{ route('participant.file.download', ['id' => $participant->id, 'type' => 'payment_proof']) }}" 
                                          alt="Bukti Pembayaran" 
                                          class="img-fluid rounded shadow-sm border mh-200px">
                                </a>
                            @endif
                            <small class="text-muted d-block mt-2">
                                Klik gambar untuk memperbesar
                            </small>
                        </div>
                        
                        <h6>Informasi Tambahan</h6>
                        <table class="table table-sm border-0">
                            <tr>
                                <td width="40%"><span class="text-muted">Tanggal Daftar</span></td>
                                <td width="5%">:</td>
                                <td><strong>{{ $participant->created_at->format('d M Y H:i') }}</strong></td>
                            </tr>
                            <tr>
                                <td><span class="text-muted">Status Saat Ini</span></td>
                                <td>:</td>
                                <td><span class="badge bg-warning">{{ $participant->status }}</span></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <!-- Tolak Modal -->
                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $participant->id }}">
                    <i class="fas fa-times"></i> Tolak
                </button>

                <form action="{{ route('admin.participant.confirm', $participant->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check"></i> Konfirmasi
                    </button>
                </form>
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