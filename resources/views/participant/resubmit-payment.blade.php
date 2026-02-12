@extends('layouts.app')

@section('title', 'Unggah Ulang Bukti Pembayaran')

@section('styles')
    <style>
        .hover-elevate {
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        }

        .hover-elevate:hover {
            transform: translateY(-5px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
        }

        .cursor-pointer {
            cursor: pointer;
        }

        .fs-07rem {
            font-size: 0.7rem;
        }
    </style>
@endsection

@section('content')
    @php
        $historyRecords = \App\Models\Participant::where('nim', $participant->nim)
            ->orderBy('created_at', 'desc')
            ->get();

        $allRejectedProofs = collect();
        foreach ($historyRecords as $rec) {
            if ($rec->payment_proof_path) {
                $allRejectedProofs->push([
                    'path' => $rec->payment_proof_path,
                    'date' => $rec->payment_date ?: $rec->created_at,
                    'type' => 'payment_proof',
                    'participant_id' => $rec->id,
                ]);
            }
            if ($rec->previous_payment_proof_path) {
                $allRejectedProofs->push([
                    'path' => $rec->previous_payment_proof_path,
                    'date' => $rec->created_at,
                    'type' => 'previous_payment_proof',
                    'participant_id' => $rec->id,
                ]);
            }
        }
        $allRejectedProofs = $allRejectedProofs->unique('path')->values();
    @endphp
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
                    <form method="POST" action="{{ route('participant.resubmit.payment', $participant->id) }}"
                        enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label for="schedule_id" class="form-label">Pilih Jadwal <span
                                    class="text-danger">*</span></label>
                            <select name="schedule_id" id="schedule_id" class="form-select" required>
                                <option value="">Pilih jadwal</option>
                                @foreach($schedules as $schedule)
                                    <option value="{{ $schedule->id }}" {{ old('schedule_id') == $schedule->id ? 'selected' : '' }}>
                                        {{ $schedule->date->format('d M Y') }} - {{ $schedule->time }} | {{ $schedule->room }}
                                        ({{ $schedule->category }})
                                        ({{ $schedule->used_capacity }}/{{ $schedule->capacity }})
                                    </option>
                                @endforeach
                            </select>
                            @error('schedule_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label d-block">Waktu Pembayaran (Sesuai Slip) <span
                                    class="text-danger">*</span></label>
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <input type="date" name="payment_date" id="payment_date" class="form-control"
                                        value="{{ old('payment_date', $participant->payment_date ? $participant->payment_date->format('Y-m-d') : date('Y-m-d')) }}"
                                        required>
                                    <div class="form-text">Tanggal pembayaran</div>
                                </div>
                                <div class="col-md-2">
                                    <select class="form-select" name="payment_hour" required>
                                        <option value="">Jam</option>
                                        @for($i = 0; $i < 24; $i++)
                                            @php $val = sprintf('%02d', $i); @endphp
                                            <option value="{{ $val }}" {{ old('payment_hour', $participant->payment_date ? $participant->payment_date->format('H') : '') == $val ? 'selected' : '' }}>
                                                {{ $val }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <select class="form-select" name="payment_minute" required>
                                        <option value="">Menit</option>
                                        @for($i = 0; $i < 60; $i++)
                                            @php $val = sprintf('%02d', $i); @endphp
                                            <option value="{{ $val }}" {{ old('payment_minute', $participant->payment_date ? $participant->payment_date->format('i') : '') == $val ? 'selected' : '' }}>
                                                {{ $val }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <select class="form-select" name="payment_second" required>
                                        <option value="">Detik</option>
                                        @for($i = 0; $i < 60; $i++)
                                            @php $val = sprintf('%02d', $i); @endphp
                                            <option value="{{ $val }}" {{ old('payment_second', $participant->payment_date ? $participant->payment_date->format('s') : '') == $val ? 'selected' : '' }}>
                                                {{ $val }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            <div class="form-text mt-1">Masukkan Tanggal, Jam, Menit, dan Detik persis seperti yang tertera
                                pada struk/bukti transfer Anda.</div>
                            @error('payment_date')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="payment_proof" class="form-label fw-bold">Bukti Pembayaran Baru <span
                                    class="text-danger">*</span></label>
                            <input type="file" name="payment_proof" id="payment_proof"
                                class="form-control @error('payment_proof') is-invalid @enderror"
                                accept="image/jpeg,image/jpg,image/png" required>
                            @error('payment_proof')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                <strong>Hanya File JPG, PNG</strong>. Ukuran maksimal: 1MB.
                            </div>
                        </div>

                        @if($allRejectedProofs->isNotEmpty())
                            <div class="mt-4 pt-3 border-top">
                                <label class="form-label fw-bold mb-3"><i class="fas fa-history me-1"></i> Riwayat Bukti
                                    Pembayaran (Ditolak):</label>
                                <div class="row g-3">
                                    @foreach($allRejectedProofs as $index => $proof)
                                        <div class="col-6 col-md-4 col-lg-3">
                                            <div class="card h-100 border shadow-sm hover-elevate cursor-pointer overflow-hidden"
                                                data-bs-toggle="modal" data-bs-target="#proofModal{{ $index }}">
                                                <div class="position-relative">
                                                    <img src="{{ route('participant.file.download', ['id' => $proof['participant_id'], 'type' => $proof['type']]) }}"
                                                        class="card-img-top"
                                                        style="height: 120px; object-fit: cover; background-color: #f8f9fa;"
                                                        alt="Bukti Ditolak">
                                                    <div class="position-absolute top-0 end-0 p-1">
                                                        <span
                                                            class="badge bg-dark bg-opacity-75 fs-07rem">#{{ $allRejectedProofs->count() - $index }}</span>
                                                    </div>
                                                </div>
                                                <div class="card-footer p-1 text-center bg-white border-top-0">
                                                    <small class="text-muted d-block fs-07rem fw-bold">
                                                        {{ $proof['date'] instanceof \Carbon\Carbon ? $proof['date']->format('d M Y') : 'Arsip' }}
                                                    </small>
                                                    <small class="text-muted fs-07rem">
                                                        {{ $proof['date'] instanceof \Carbon\Carbon ? $proof['date']->format('H:i') : '' }}
                                                    </small>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Modal for this specific proof -->
                                        <div class="modal fade" id="proofModal{{ $index }}" tabindex="-1" aria-hidden="true"
                                            style="z-index: 1060;">
                                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                                <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                                                    <div class="modal-header bg-danger text-white border-0">
                                                        <h5 class="modal-title fw-bold"><i class="fas fa-receipt me-2"></i>Bukti
                                                            Pembayaran (Ditolak)</h5>
                                                        <button type="button" class="btn-close btn-close-white"
                                                            data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body text-center p-2 bg-light">
                                                        <div class="p-2 bg-white rounded-3 shadow-sm mb-2 text-dark">
                                                            <small class="fw-bold">Waktu Upload:</small>
                                                            {{ $proof['date'] instanceof \Carbon\Carbon ? $proof['date']->format('d F Y, H:i:s') : 'Tidak tersedia' }}
                                                        </div>
                                                        <div class="rounded-3 overflow-hidden border shadow-sm mx-auto"
                                                            style="max-height: 70vh; display: inline-block;">
                                                            <img src="{{ route('participant.file.download', ['id' => $proof['participant_id'], 'type' => $proof['type']]) }}"
                                                                class="img-fluid" alt="Full Proof">
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer border-0 bg-light justify-content-center">
                                                        <a href="{{ route('participant.file.download', ['id' => $proof['participant_id'], 'type' => $proof['type']]) }}"
                                                            target="_blank" class="btn btn-outline-danger btn-sm rounded-pill px-4">
                                                            <i class="fas fa-external-link-alt me-1"></i> Buka Original
                                                        </a>
                                                        <button type="button" class="btn btn-secondary btn-sm rounded-pill px-4"
                                                            data-bs-dismiss="modal">Tutup</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

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
                    <button type="button" class="btn btn-primary px-5 py-2 rounded-pill"
                        data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        @if(session('payment_error'))
            document.addEventListener('DOMContentLoaded', function () {
                // Ensure bootstrap is available (it should be via layouts.app)
                const modalElement = document.getElementById('paymentErrorModal');
                const modal = new bootstrap.Modal(modalElement);
                document.getElementById('paymentErrorMessage').textContent = '{{ session('payment_error') }}';
                modal.show();
            });
        @endif

        document.addEventListener('DOMContentLoaded', function () {
            const form = document.querySelector('form[action*="resubmit"]');
            const paymentProof = document.getElementById('payment_proof');

            if (paymentProof) {
                paymentProof.addEventListener('change', function () {
                    if (this.files && this.files[0]) {
                        const file = this.files[0];
                        const fileSize = file.size / 1024 / 1024; // MB
                        const allowedExtensions = ['jpg', 'jpeg', 'png'];
                        const fileExtension = file.name.split('.').pop().toLowerCase();

                        // Clear existing error
                        this.classList.remove('is-invalid');
                        const existingError = this.parentNode.querySelector('.invalid-feedback');
                        if (existingError) existingError.remove();

                        let errorMsg = '';
                        if (fileSize > 1) {
                            errorMsg = 'Ukuran file terlalu besar (' + fileSize.toFixed(2) + 'MB). Maksimal 1MB.';
                        } else if (!allowedExtensions.includes(fileExtension)) {
                            errorMsg = 'Format file tidak didukung. Gunakan JPG atau PNG.';
                        }

                        if (errorMsg) {
                            this.classList.add('is-invalid');
                            const errorDiv = document.createElement('div');
                            errorDiv.className = 'invalid-feedback';
                            errorDiv.textContent = errorMsg;
                            this.parentNode.appendChild(errorDiv);
                        }
                    }
                });
            }

            if (form) {
                form.addEventListener('submit', function (e) {
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

                        if (fileSize > 1) {
                            paymentProof.classList.add('is-invalid');
                            let errorDiv = paymentProof.parentNode.querySelector('.invalid-feedback');
                            if (!errorDiv) {
                                errorDiv = document.createElement('div');
                                errorDiv.className = 'invalid-feedback';
                                paymentProof.parentNode.appendChild(errorDiv);
                            }
                            errorDiv.textContent = 'Ukuran file bukti pembayaran terlalu besar (' + fileSize.toFixed(2) + 'MB). Maksimal 1MB.';
                            isValid = false;
                        } else if (!allowedExtensions.includes(fileExtension)) {
                            paymentProof.classList.add('is-invalid');
                            let errorDiv = paymentProof.parentNode.querySelector('.invalid-feedback');
                            if (!errorDiv) {
                                errorDiv = document.createElement('div');
                                errorDiv.className = 'invalid-feedback';
                                paymentProof.parentNode.appendChild(errorDiv);
                            }
                            errorDiv.textContent = 'Format file tidak didukung. Gunakan JPG atau PNG.';
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