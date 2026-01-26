@extends('layouts.app')

@section('title', 'Pendaftaran Ulang TOEFL')

@section('content')
<div class="row">
    <div class="col-md-12">
        <h1>Formulir Pendaftaran Ulang TOEFL</h1>
        <p class="lead">Silakan lengkapi informasi berikut untuk mendaftar tes ulang</p>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Informasi Pendaftaran Ulang</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('participant.retake.process', $participant->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="schedule_id" class="form-label">Pilih Jadwal Tes <span class="text-danger">*</span></label>
                                <select class="form-select" id="schedule_id" name="schedule_id" required>
                                    <option value="">Pilih jadwal</option>
                                    @foreach($schedules as $schedule)
                                        <option value="{{ $schedule->id }}">
                                            {{ $schedule->date->format('d M Y') }} - Ruangan: {{ $schedule->room }} ({{ $schedule->used_capacity }}/{{ $schedule->capacity }} terisi)
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="test_category" class="form-label">Kategori Tes <span class="text-danger">*</span></label>
                                <select class="form-select" id="test_category" name="test_category" required>
                                    <option value="">Pilih Kategori Tes</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category }}" {{ old('test_category') == $category ? 'selected' : '' }}>
                                            {{ $category }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label d-block">Waktu Pembayaran (Sesuai Slip) <span class="text-danger">*</span></label>
                                <div class="row g-2">
                                    <div class="col-md-6">
                                        <input type="date" class="form-control" id="payment_date" name="payment_date" value="{{ old('payment_date') }}" required>
                                        <div class="form-text">Tanggal pembayaran</div>
                                    </div>
                                    <div class="col-md-2">
                                        <select class="form-select" name="payment_hour" required>
                                            <option value="">Jam</option>
                                            @for($i = 0; $i < 24; $i++)
                                                <option value="{{ sprintf('%02d', $i) }}" {{ old('payment_hour') == sprintf('%02d', $i) ? 'selected' : '' }}>{{ sprintf('%02d', $i) }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <select class="form-select" name="payment_minute" required>
                                            <option value="">Menit</option>
                                            @for($i = 0; $i < 60; $i++)
                                                <option value="{{ sprintf('%02d', $i) }}" {{ old('payment_minute') == sprintf('%02d', $i) ? 'selected' : '' }}>{{ sprintf('%02d', $i) }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <select class="form-select" name="payment_second" required>
                                            <option value="">Detik</option>
                                            @for($i = 0; $i < 60; $i++)
                                                <option value="{{ sprintf('%02d', $i) }}" {{ old('payment_second') == sprintf('%02d', $i) ? 'selected' : '' }}>{{ sprintf('%02d', $i) }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                                <div class="form-text">Masukkan Tanggal, Jam, Menit, dan Detik persis seperti yang tertera pada struk/bukti transfer Anda.</div>
                            </div>
                        </div>
                    </div>

                    <h5 class="mt-4">Unggah Bukti Pembayaran Baru</h5>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="payment_proof" class="form-label">Bukti Pembayaran <span class="text-danger">*</span></label>
                                <input type="file" class="form-control @error('payment_proof') is-invalid @enderror" id="payment_proof" name="payment_proof" accept="image/jpeg,image/jpg,image/png" required>
                                @error('payment_proof')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Hanya File JPG, PNG (maks 2MB)</div>
                            </div>
                        </div>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('participant.dashboard', $participant->id) }}" class="btn btn-secondary me-md-2">Batal</a>
                        <button type="submit" class="btn btn-primary btn-lg" id="submitBtn">Kirim Pendaftaran Ulang</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script nonce="{{ $csp_nonce ?? '' }}">
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const paymentProof = document.getElementById('payment_proof');

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
});
</script>


</script>

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

<script nonce="{{ $csp_nonce ?? '' }}">
@if(session('payment_error'))
    document.addEventListener('DOMContentLoaded', function() {
        const modal = new bootstrap.Modal(document.getElementById('paymentErrorModal'));
        document.getElementById('paymentErrorMessage').textContent = '{{ session('payment_error') }}';
        modal.show();
    });
@endif
</script>
@endsection