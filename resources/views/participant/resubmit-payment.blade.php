@extends('layouts.app')

@section('title', 'Unggah Ulang Bukti Pembayaran')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1>Unggah Ulang Bukti Pembayaran</h1>
            <a href="{{ route('participant.dashboard', $participant->id) }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
            </a>
        </div>
        <p class="lead">Silakan unggah ulang bukti pembayaran Anda untuk melanjutkan proses pendaftaran.</p>

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
</div>

<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Detail Pendaftaran Sebelumnya</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless" style="margin-bottom: 0;">
                            <tr>
                                <td style="width: 40%; vertical-align: top;"><strong>Nama</strong></td>
                                <td style="width: 5%; vertical-align: top;"><strong>:</strong></td>
                                <td style="width: 55%; vertical-align: top;">{{ $participant->name }}</td>
                            </tr>
                            <tr>
                                <td style="vertical-align: top;"><strong>NIM</strong></td>
                                <td style="vertical-align: top;"><strong>:</strong></td>
                                <td style="vertical-align: top;">{{ $participant->nim }}</td>
                            </tr>
                            <tr>
                                <td style="vertical-align: top;"><strong>Email</strong></td>
                                <td style="vertical-align: top;"><strong>:</strong></td>
                                <td style="vertical-align: top;">{{ $participant->email }}</td>
                            </tr>
                            <tr>
                                <td style="vertical-align: top;"><strong>Jurusan</strong></td>
                                <td style="vertical-align: top;"><strong>:</strong></td>
                                <td style="vertical-align: top;">{{ $participant->major }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless" style="margin-bottom: 0;">
                            <tr>
                                <td style="width: 40%; vertical-align: top;"><strong>Fakultas</strong></td>
                                <td style="width: 5%; vertical-align: top;"><strong>:</strong></td>
                                <td style="width: 55%; vertical-align: top;">{{ $participant->faculty }}</td>
                            </tr>
                            <tr>
                                <td style="vertical-align: top;"><strong>Kategori</strong></td>
                                <td style="vertical-align: top;"><strong>:</strong></td>
                                <td style="vertical-align: top;">{{ $participant->test_category }}</td>
                            </tr>
                            <tr>
                                <td style="vertical-align: top;"><strong>Status</strong></td>
                                <td style="vertical-align: top;"><strong>:</strong></td>
                                <td style="vertical-align: top;">
                                    <span class="badge bg-secondary">Ditolak</span>
                                    @if($participant->rejection_message)
                                        <div class="alert alert-light mt-2 p-2">
                                            <strong>Pesan Penolakan:</strong>
                                            <div class="text-muted">{{ $participant->rejection_message }}</div>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">Formulir Penggantian Bukti Pembayaran</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('participant.resubmit.payment', $participant->id) }}" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label for="schedule_id" class="form-label">Pilih Jadwal <span class="text-danger">*</span></label>
                        <select name="schedule_id" id="schedule_id" class="form-select" required>
                            <option value="">Pilih jadwal</option>
                            @foreach($schedules as $schedule)
                                <option value="{{ $schedule->id }}" 
                                    {{ old('schedule_id') == $schedule->id ? 'selected' : '' }}>
                                    {{ $schedule->date->format('d M Y') }} - {{ $schedule->time }} | {{ $schedule->room }} ({{ $schedule->category }})
                                    ({{ $schedule->used_capacity }}/{{ $schedule->capacity }})
                                </option>
                            @endforeach
                        </select>
                        @error('schedule_id')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label d-block">Waktu Pembayaran (Sesuai Slip) <span class="text-danger">*</span></label>
                        <div class="row g-2">
                            <div class="col-md-6">
                                <input type="date" name="payment_date" id="payment_date" class="form-control" 
                                       value="{{ old('payment_date', $participant->payment_date ? $participant->payment_date->format('Y-m-d') : date('Y-m-d')) }}" required>
                                <div class="form-text">Tanggal pembayaran</div>
                            </div>
                            <div class="col-md-2">
                                <select class="form-select" name="payment_hour" required>
                                    <option value="">Jam</option>
                                    @for($i = 0; $i < 24; $i++)
                                        @php $val = sprintf('%02d', $i); @endphp
                                        <option value="{{ $val }}" {{ old('payment_hour', $participant->payment_date ? $participant->payment_date->format('H') : '') == $val ? 'selected' : '' }}>{{ $val }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select class="form-select" name="payment_minute" required>
                                    <option value="">Menit</option>
                                    @for($i = 0; $i < 60; $i++)
                                        @php $val = sprintf('%02d', $i); @endphp
                                        <option value="{{ $val }}" {{ old('payment_minute', $participant->payment_date ? $participant->payment_date->format('i') : '') == $val ? 'selected' : '' }}>{{ $val }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select class="form-select" name="payment_second" required>
                                    <option value="">Detik</option>
                                    @for($i = 0; $i < 60; $i++)
                                        @php $val = sprintf('%02d', $i); @endphp
                                        <option value="{{ $val }}" {{ old('payment_second', $participant->payment_date ? $participant->payment_date->format('s') : '') == $val ? 'selected' : '' }}>{{ $val }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        <div class="form-text mt-1">Masukkan Tanggal, Jam, Menit, dan Detik persis seperti yang tertera pada struk/bukti transfer Anda.</div>
                        @error('payment_date')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="payment_proof" class="form-label fw-bold">Bukti Pembayaran Baru <span class="text-danger">*</span></label>
                        <input type="file" name="payment_proof" id="payment_proof" class="form-control @error('payment_proof') is-invalid @enderror" accept="image/jpeg,image/jpg,image/png" required>
                        @error('payment_proof')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            <strong>Hanya File JPG, PNG</strong>. Ukuran maksimal: 2MB.
                        </div>
                    </div>     @if($participant->payment_proof_path)
                            <div class="mt-2">
                                <label class="form-label">Bukti Pembayaran Sebelumnya:</label>
                                <div>
                                    <a href="{{ asset('storage/' . $participant->payment_proof_path) }}" target="_blank" class="btn btn-outline-info btn-sm">
                                        <i class="fas fa-file-image"></i> Lihat Bukti Pembayaran
                                    </a>
                                </div>
                            </div>
                        @endif

                        @error('payment_proof')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-upload"></i> Upload Ulang Bukti Pembayaran
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Payment Error Modal -->
<div class="modal fade" id="paymentErrorModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-body p-5 text-center">
                <div class="mb-4">
                    <i class="fas fa-exclamation-circle fa-4x text-danger"></i>
                </div>
                <h4 class="fw-bold mb-3 text-danger">Slip Pembayaran Sudah Digunakan!</h4>
                <p class="text-muted mb-4" id="paymentErrorMessage"></p>
                <button type="button" class="btn btn-primary px-5 py-2 rounded-pill" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
@if(session('payment_error'))
    document.addEventListener('DOMContentLoaded', function() {
        // Ensure bootstrap is available (it should be via layouts.app)
        const modalElement = document.getElementById('paymentErrorModal');
        const modal = new bootstrap.Modal(modalElement);
        document.getElementById('paymentErrorMessage').textContent = '{{ session('payment_error') }}';
        modal.show();
    });
@endif

document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form[action*="resubmit"]');
    const paymentProof = document.getElementById('payment_proof');

    if (form) {
        form.addEventListener('submit', function(e) {
            let isValid = true;
            
            // Clear previous error states
            paymentProof.classList.remove('is-invalid');
            const existingErrors = form.querySelectorAll('.invalid-feedback');
            existingErrors.forEach(error => {
                if (error.id !== 'paymentErrorMessage') error.remove();
            });

            // Validate payment proof if a file is selected
            if (paymentProof.files.length > 0) {
                const file = paymentProof.files[0];
                const fileSize = file.size / 1024 / 1024; // MB
                const allowedExtensions = ['jpg', 'jpeg', 'png'];
                const fileExtension = file.name.split('.').pop().toLowerCase();

                if (fileSize > 2) {
                    paymentProof.classList.add('is-invalid');
                    const errorDiv = document.createElement('div');
                    errorDiv.className = 'invalid-feedback';
                    errorDiv.textContent = 'Ukuran file bukti pembayaran melebihi kapasitas maksimal (2MB).';
                    paymentProof.parentNode.appendChild(errorDiv);
                    isValid = false;
                } else if (!allowedExtensions.includes(fileExtension)) {
                    paymentProof.classList.add('is-invalid');
                    const errorDiv = document.createElement('div');
                    errorDiv.className = 'invalid-feedback';
                    errorDiv.textContent = 'Format file tidak didukung. Gunakan JPG atau PNG.';
                    paymentProof.parentNode.appendChild(errorDiv);
                    isValid = false;
                }
            }

            if (!isValid) {
                e.preventDefault();
                paymentProof.focus();
            }
        });
    }
});
</script>
@endsection