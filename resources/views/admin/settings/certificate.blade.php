@extends('layouts.app')

@section('title', 'Pengaturan Sertifikat & Tanda Tangan Digital')

@section('content')
<style nonce="{{ $csp_nonce ?? '' }}">
    :root {
        --premium-blue: #1e3a8a;
        --premium-indigo: #4338ca;
        --premium-slate: #0f172a;
    }

    .settings-header {
        background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 100%);
        border-radius: 1.5rem;
        padding: 2.25rem;
        color: white;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.25);
    }

    .premium-card {
        border: none;
        border-radius: 1.25rem;
        background: white;
        box-shadow: 0 10px 20px -3px rgba(0, 0, 0, 0.05);
        overflow: hidden;
        border: 1px solid rgba(226, 232, 240, 0.8);
        margin-bottom: 1.5rem;
    }

    .premium-card .card-header {
        background: #f8fafc;
        border-bottom: 1px solid #f1f5f9;
        padding: 1.25rem 1.5rem;
    }

    .form-control-premium {
        border-radius: 0.75rem;
        padding: 0.75rem 1rem;
        border: 1px solid #cbd5e1;
        background: #f8fafc;
        transition: all 0.2s ease-in-out;
    }

    .form-control-premium:focus {
        background: white;
        border-color: #3b82f6;
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.15);
    }

    .signature-preview-box {
        background-color: #ffffff;
        background-image: 
            linear-gradient(45deg, #f1f5f9 25%, transparent 25%), 
            linear-gradient(-45deg, #f1f5f9 25%, transparent 25%), 
            linear-gradient(45deg, transparent 75%, #f1f5f9 75%), 
            linear-gradient(-45deg, transparent 75%, #f1f5f9 75%);
        background-size: 16px 16px;
        background-position: 0 0, 0 8px, 8px -8px, -8px 0px;
        border: 2px dashed #cbd5e1;
        border-radius: 1rem;
        padding: 1.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 180px;
        position: relative;
    }

    .mockup-certificate-box {
        background: #ffffff;
        border: 2px solid #e2e8f0;
        border-radius: 1rem;
        padding: 2rem;
        text-align: center;
        box-shadow: inset 0 2px 4px rgba(0,0,0,0.02);
    }

    .mockup-title {
        font-size: 0.9rem;
        font-weight: 600;
        color: #334155;
        margin-bottom: 0.5rem;
    }

    .mockup-signature-img-container {
        height: 100px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0.5rem 0;
    }

    .mockup-signature-img {
        max-height: 95px;
        max-width: 180px;
        object-fit: contain;
    }

    .mockup-name {
        font-weight: 700;
        font-size: 0.95rem;
        color: #0f172a;
        text-decoration: underline;
        margin-bottom: 0.2rem;
    }

    .mockup-nip {
        font-size: 0.825rem;
        color: #475569;
    }
</style>

<div class="container-fluid px-0">
    <!-- Header -->
    <div class="settings-header">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-outline-light rounded-pill px-3 mb-3">
                    <i class="fas fa-arrow-left me-2"></i>Kembali ke Dashboard
                </a>
                <h2 class="fw-bold mb-2">Pengaturan Sertifikat & TTD Digital</h2>
                <p class="text-white-50 mb-0">
                    Kelola data pejabat penandatangan dan upload tanda tangan digital & stempel resmi UPA Bahasa UHO untuk penerbitan sertifikat TOEFL.
                </p>
            </div>
            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <span class="badge bg-primary bg-opacity-75 px-3 py-2 rounded-pill fs-6">
                    <i class="fas fa-shield-alt me-2"></i>Hak Akses: Administrator
                </span>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm border-0 mb-4" role="alert">
            <div class="d-flex align-items-center">
                <i class="fas fa-check-circle fa-lg me-3"></i>
                <div>{{ session('success') }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4">
        <!-- Form Settings -->
        <div class="col-lg-7">
            <div class="premium-card">
                <div class="card-header">
                    <h5 class="mb-0 fw-bold text-dark">
                        <i class="fas fa-pen-nib me-2 text-primary"></i>Form Data Pejabat & Tanda Tangan
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.settings.certificate.update') }}" method="POST" enctype="multipart/form-data" id="certificate-settings-form">
                        @csrf

                        <!-- Jabatan Penandatangan -->
                        <div class="mb-4">
                            <label class="form-label fw-bold text-secondary small text-uppercase" for="signer_title">
                                Jabatan Penandatangan (Bahasa Inggris) <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   id="signer_title" 
                                   name="signer_title" 
                                   class="form-control form-control-premium @error('signer_title') is-invalid @enderror" 
                                   value="{{ old('signer_title', $signerTitle) }}" 
                                   placeholder="Contoh: Head of UPA Bahasa UHO," 
                                   required>
                            <div class="form-text text-muted">
                                Tulisan jabatan di atas tanda tangan pada lembar sertifikat resmi.
                            </div>
                            @error('signer_title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Nama Lengkap & Gelar -->
                        <div class="mb-4">
                            <label class="form-label fw-bold text-secondary small text-uppercase" for="signer_name">
                                Nama Lengkap & Gelar Pejabat <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   id="signer_name" 
                                   name="signer_name" 
                                   class="form-control form-control-premium @error('signer_name') is-invalid @enderror" 
                                   value="{{ old('signer_name', $signerName) }}" 
                                   placeholder="Contoh: Ir. Uniadi Mangidi, S.T., M.T., M.Eng.Sc" 
                                   required>
                            <div class="form-text text-muted">
                                Nama ini akan dicetak tebal di bawah tanda tangan sertifikat.
                            </div>
                            @error('signer_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- NIP -->
                        <div class="mb-4">
                            <label class="form-label fw-bold text-secondary small text-uppercase" for="signer_nip">
                                NIP Pejabat <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   id="signer_nip" 
                                   name="signer_nip" 
                                   class="form-control form-control-premium @error('signer_nip') is-invalid @enderror" 
                                   value="{{ old('signer_nip', $signerNip) }}" 
                                   placeholder="Contoh: 19750614 200212 1 002" 
                                   required>
                            <div class="form-text text-muted">
                                Nomor Induk Pegawai penandatangan sertifikat.
                            </div>
                            @error('signer_nip')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Upload File Tanda Tangan & Stempel -->
                        <div class="mb-4">
                            <label class="form-label fw-bold text-secondary small text-uppercase" for="signature_image">
                                Upload File Tanda Tangan Digital & Stempel
                            </label>
                            <input type="file" 
                                   id="signature_image" 
                                   name="signature_image" 
                                   class="form-control form-control-premium @error('signature_image') is-invalid @enderror" 
                                   accept="image/png,image/jpeg,image/jpg">
                            <div class="form-text text-muted">
                                <i class="fas fa-info-circle me-1 text-primary"></i>
                                Format: <strong>PNG (Direkomendasikan latar transparan)</strong> atau JPG. Maksimal ukuran: <strong>2 MB</strong>.
                                Biarkan kosong jika tidak ingin mengubah gambar tanda tangan yang aktif.
                            </div>
                            @error('signature_image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                            <button type="submit" class="btn btn-primary px-4 py-2 rounded-3 fw-bold shadow-sm">
                                <i class="fas fa-save me-2"></i>Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Right Column: Status & Live Mockup -->
        <div class="col-lg-5">
            <!-- Active Signature Status Card -->
            <div class="premium-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold text-dark">
                        <i class="fas fa-image me-2 text-indigo"></i>Gambar TTD Saat Ini
                    </h5>
                    @if($isCustomSignature)
                        <span class="badge bg-success rounded-pill px-3 py-1">Kustom Aktif</span>
                    @else
                        <span class="badge bg-secondary rounded-pill px-3 py-1">Default Sistem</span>
                    @endif
                </div>
                <div class="card-body p-4 text-center">
                    <div class="signature-preview-box mb-3">
                        @if($signatureUrl)
                            <img src="{{ $signatureUrl }}" 
                                 id="current-signature-img" 
                                 alt="Signature Preview" 
                                 style="max-height: 120px; max-width: 100%; object-fit: contain;">
                        @else
                            <div class="text-muted small">
                                <i class="fas fa-signature fa-3x mb-2 d-block opacity-25"></i>
                                Belum ada gambar tanda tangan
                            </div>
                        @endif
                    </div>

                    @if($isCustomSignature)
                        <form action="{{ route('admin.settings.certificate.delete-signature') }}" method="POST" class="d-inline" id="form-reset-signature">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-outline-danger btn-sm rounded-pill px-3" id="btn-reset-signature">
                                <i class="fas fa-undo me-1"></i>Kembalikan ke Tanda Tangan Default
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <!-- Live Mockup Preview Card -->
            <div class="premium-card">
                <div class="card-header">
                    <h5 class="mb-0 fw-bold text-dark">
                        <i class="fas fa-eye me-2 text-teal"></i>Pratinjau Blok Sertifikat
                    </h5>
                </div>
                <div class="card-body p-4">
                    <p class="text-muted small mb-3">
                        Berikut simulasi tampilan tanda tangan pada lembar sertifikat secara real-time saat Anda mengetik atau memilih gambar baru:
                    </p>

                    <div class="mockup-certificate-box">
                        <div class="mockup-title" id="preview-title">
                            {{ $signerTitle }}
                        </div>
                        <div class="mockup-signature-img-container">
                            <img src="{{ $signatureUrl ?? asset('signature.png') }}" 
                                 id="mockup-img" 
                                 class="mockup-signature-img" 
                                 alt="Signature Mockup">
                        </div>
                        <div class="mockup-name" id="preview-name">
                            {{ $signerName }}
                        </div>
                        <div class="mockup-nip" id="preview-nip">
                            NIP. {{ $signerNip }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script nonce="{{ $csp_nonce ?? '' }}">
document.addEventListener('DOMContentLoaded', function () {
    const inputTitle = document.getElementById('signer_title');
    const inputName = document.getElementById('signer_name');
    const inputNip = document.getElementById('signer_nip');
    const inputFile = document.getElementById('signature_image');

    const previewTitle = document.getElementById('preview-title');
    const previewName = document.getElementById('preview-name');
    const previewNip = document.getElementById('preview-nip');
    const mockupImg = document.getElementById('mockup-img');
    const currentSignatureImg = document.getElementById('current-signature-img');

    // Live update for title
    if (inputTitle && previewTitle) {
        inputTitle.addEventListener('input', function () {
            previewTitle.textContent = this.value.trim() || 'Head of UPA Bahasa UHO,';
        });
    }

    // Live update for name
    if (inputName && previewName) {
        inputName.addEventListener('input', function () {
            previewName.textContent = this.value.trim() || 'Nama Pejabat';
        });
    }

    // Live update for NIP
    if (inputNip && previewNip) {
        inputNip.addEventListener('input', function () {
            const val = this.value.trim();
            previewNip.textContent = val ? (val.startsWith('NIP') ? val : 'NIP. ' + val) : 'NIP. -';
        });
    }

    // Live preview for uploaded signature image
    if (inputFile) {
        inputFile.addEventListener('change', function () {
            const file = this.files[0];
            if (file) {
                // Validate size (< 2MB)
                if (file.size > 2 * 1024 * 1024) {
                    alert('Ukuran file melebihi 2MB. Silakan pilih file yang lebih kecil.');
                    this.value = '';
                    return;
                }

                const reader = new FileReader();
                reader.onload = function (e) {
                    if (mockupImg) {
                        mockupImg.src = e.target.result;
                    }
                    if (currentSignatureImg) {
                        currentSignatureImg.src = e.target.result;
                    }
                };
                reader.readAsDataURL(file);
            }
        });
    }

    // Reset confirmation handler
    const btnResetSignature = document.getElementById('btn-reset-signature');
    const formResetSignature = document.getElementById('form-reset-signature');
    if (btnResetSignature && formResetSignature) {
        btnResetSignature.addEventListener('click', function () {
            if (confirm('Apakah Anda yakin ingin mengembalikan tanda tangan ke gambar default sistem?')) {
                formResetSignature.submit();
            }
        });
    }
});
</script>
@endsection
