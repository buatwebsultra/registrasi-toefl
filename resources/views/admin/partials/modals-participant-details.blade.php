<!-- Test Information Modal -->
<div class="modal fade" id="testInfoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header bg-gradient-to-r from-blue-500 to-indigo-600 text-white border-0"
                style="background: linear-gradient(135deg, #3b82f6 0%, #4f46e5 100%); color: white;">
                <h5 class="modal-title fw-bold"><i class="fas fa-info-circle me-2"></i>Informasi Tes</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 bg-light">
                <div class="bg-white p-3 rounded-3 shadow-sm mb-3">
                    <label class="small text-muted fw-bold text-uppercase">Kategori Tes</label>
                    @php
                        $isGraduate = \Str::contains(strtolower($participant->academic_level), ['s2', 's3', 'magister', 'doktor', 'master', 'doctor']);
                        $displayCategory = $isGraduate ? 'TOEFL-EQUIVALENT' : 'TOEFL-LIKE';
                    @endphp
                    <div class="fs-5 fw-bold text-dark">{{ $displayCategory }}</div>
                </div>
                <div class="bg-white p-3 rounded-3 shadow-sm mb-3">
                    <label class="small text-muted fw-bold text-uppercase">Jadwal & Ruangan</label>
                    <div class="fs-5 fw-bold text-dark">
                        {{ optional($participant->schedule)->room ?? 'Jadwal Tidak Tersedia' }}</div>
                    <div class="text-primary">
                        {{ optional($participant->schedule)->date ? $participant->schedule->date->format('d F Y') : '-' }}
                        •
                        {{ optional($participant->schedule)->time ? \Carbon\Carbon::parse($participant->schedule->time)->format('H:i') . ' WITA' : '-' }}
                    </div>
                </div>
                <div class="bg-white p-3 rounded-3 shadow-sm">
                    <label class="small text-muted fw-bold text-uppercase">Status Pendaftaran</label>
                    <div class="mt-1">
                        @if($participant->status === 'confirmed')
                            <span class="badge bg-success rounded-pill px-3">Terkonfirmasi</span>
                        @elseif($participant->status === 'pending')
                            <span class="badge bg-warning text-dark rounded-pill px-3">Menunggu</span>
                        @else
                            <span class="badge bg-danger rounded-pill px-3">Ditolak</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 bg-light">
                <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Card Preview Modal -->
<div class="modal fade" id="cardPreviewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 h-100">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold">Preview & Unduh Kartu PDF</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4" style="min-height: 500px">
                <div id="cardPreviewContainer"
                    class="w-100 h-100 d-flex justify-content-center align-items-center bg-light rounded-3">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
                <iframe id="cardPreviewFrame" class="d-none w-100 h-100 border-0 rounded-3"
                    style="min-height: 500px;"></iframe>
            </div>
        </div>
    </div>
</div>

<!-- Reset Password Modal -->
<div class="modal fade" id="resetPasswordModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-warning text-dark border-0">
                <h5 class="modal-title fw-bold"><i class="fas fa-key me-2"></i>Reset Password</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.reset.participant.password') }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="participant_id" value="{{ $participant->id }}">
                <div class="modal-body p-4">
                    <div class="alert alert-warning border-0 bg-warning bg-opacity-10 mb-4">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Anda akan mereset password untuk peserta <strong>{{ $participant->name }}</strong> dengan
                        Username <strong>{{ $participant->username }}</strong>.
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Password Baru</label>
                        <input type="password" name="new_password" class="form-control rounded-3" required
                            minlength="12">
                        <div class="form-text">Minimal 12 karakter.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Konfirmasi Password Baru</label>
                        <input type="password" name="new_password_confirmation" class="form-control rounded-3" required>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light rounded-pill" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning rounded-pill px-4 fw-bold">Reset Password</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Confirm Payment Modal -->
<div class="modal fade" id="confirmPaymentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-success text-white border-0">
                <h5 class="modal-title fw-bold"><i class="fas fa-check-circle me-2"></i>Konfirmasi Pembayaran</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.participant.confirm', $participant->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body p-4">
                    <div class="card border-warning shadow-sm mb-3">
                        <div class="card-header bg-warning bg-opacity-10 border-warning py-2">
                            <h6 class="mb-0 text-dark small fw-bold">
                                <i class="fas fa-calendar-edit me-2"></i>Sesuaikan Waktu Pembayaran
                            </h6>
                        </div>
                        <div class="card-body p-3">
                            <div class="row g-2 align-items-end">
                                <div class="col-6">
                                    <label class="form-label small fw-bold text-muted mb-1">Tanggal</label>
                                    <input type="date" class="form-control form-control-sm" name="payment_date"
                                        value="{{ $participant->payment_date ? $participant->payment_date->format('Y-m-d') : date('Y-m-d') }}"
                                        required>
                                </div>
                                <div class="col-2">
                                    <label class="form-label small fw-bold text-muted mb-1">Jam</label>
                                    <input type="number" class="form-control form-control-sm text-center"
                                        name="payment_hour" min="0" max="23"
                                        value="{{ $participant->payment_date ? $participant->payment_date->format('H') : '00' }}"
                                        required>
                                </div>
                                <div class="col-2">
                                    <label class="form-label small fw-bold text-muted mb-1">Menit</label>
                                    <input type="number" class="form-control form-control-sm text-center"
                                        name="payment_minute" min="0" max="59"
                                        value="{{ $participant->payment_date ? $participant->payment_date->format('i') : '00' }}"
                                        required>
                                </div>
                                <div class="col-2">
                                    <label class="form-label small fw-bold text-muted mb-1">Detik</label>
                                    <input type="number" class="form-control form-control-sm text-center"
                                        name="payment_second" min="0" max="59"
                                        value="{{ $participant->payment_date ? $participant->payment_date->format('s') : '00' }}"
                                        required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-success border-0 bg-success bg-opacity-10 mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        Mengonfirmasi pendaftaran akan menetapkan <strong>Nomor Kursi</strong> secara otomatis dan
                        mengirimkan notifikasi status ke dashboard peserta.
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light rounded-pill" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success rounded-pill px-4 fw-bold">Konfirmasi & Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal{{ $participant->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-danger text-white border-0">
                <h5 class="modal-title fw-bold"><i class="fas fa-times-circle me-2"></i>Tolak Pendaftaran</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.participant.reject', $participant->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body p-4">
                    <p class="text-muted mb-3">Apakah Anda yakin ingin menolak pendaftaran
                        <strong>{{ $participant->name }}</strong>? Peserta akan diizinkan untuk mengunggah ulang bukti
                        pembayaran.</p>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Alasan Penolakan</label>
                        <textarea class="form-control rounded-3" name="rejection_message" rows="3"
                            placeholder="Contoh: Bukti transfer tidak terbaca atau nominal tidak sesuai..."
                            required></textarea>
                    </div>

                    <div class="alert alert-danger border-0 bg-danger bg-opacity-10 mb-0">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Pesan ini akan langsung tampil di dashboard peserta sebagai panduan perbaikan.
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light rounded-pill" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger rounded-pill px-4 fw-bold">Ya, Tolak
                        Pendaftaran</button>
                </div>
            </form>
        </div>
    </div>
</div>