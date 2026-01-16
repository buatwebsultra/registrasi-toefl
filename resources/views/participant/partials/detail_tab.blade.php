<div class="row g-4">
    <div class="col-lg-12">
        <div class="card premium-card border-0">
            <div class="card-header p-4">
                <h5 class="mb-0 fw-bold text-primary"><i class="fas fa-user-circle me-2"></i>Profil Lengkap Peserta</h5>
            </div>
            <div class="card-body p-4">
                <div class="row g-4">
                    <div class="col-md-8 border-end-md">
                        <div class="p-4 bg-light rounded-4">
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label class="text-muted small fw-bold text-uppercase d-block mb-1">Nama Lengkap</label>
                                    <div class="h6 fw-bold mb-0">{{ $participant->name }}</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="text-muted small fw-bold text-uppercase d-block mb-1">Username</label>
                                    <div class="h6 fw-bold mb-0 text-primary">{{ $participant->username }}</div>
                                </div>
                            </div>
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label class="text-muted small fw-bold text-uppercase d-block mb-1">NIM / ID</label>
                                    <div class="h6 fw-bold mb-0">{{ $participant->nim }}</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="text-muted small fw-bold text-uppercase d-block mb-1">E-mail</label>
                                    <div class="h6 fw-bold mb-0">{{ $participant->email }}</div>
                                </div>
                            </div>
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label class="text-muted small fw-bold text-uppercase d-block mb-1">Program Studi</label>
                                    <div class="h6 fw-bold mb-0">{{ $participant->academic_level_display }} {{ $participant->major }}</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="text-muted small fw-bold text-uppercase d-block mb-1">Fakultas</label>
                                    <div class="h6 fw-bold mb-0">{{ $participant->faculty }}</div>
                                </div>
                            </div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="text-muted small fw-bold text-uppercase d-block mb-1">TTL</label>
                                    <div class="h6 fw-bold mb-0">{{ $participant->birth_place }}, {{ $participant->birth_date->format('d M Y') }}</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="text-muted small fw-bold text-uppercase d-block mb-1">Kontak</label>
                                    <div class="h6 fw-bold mb-0">{{ $participant->phone }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center p-3">
                            <label class="text-muted small fw-bold text-uppercase d-block mb-3">Foto Identitas</label>
                            <div class="premium-card d-inline-block p-2 bg-white" style="cursor: pointer; box-shadow: 0 10px 25px rgba(0,0,0,0.1);" onclick="showPhotoModal()">
                                @if($participant->photo_path && (\Storage::disk('private')->exists($participant->photo_path) || \Storage::disk('public')->exists($participant->photo_path)))
                                    <img src="{{ route('participant.file.download', ['id' => $participant->id, 'type' => 'photo']) }}"
                                         alt="Foto Peserta"
                                         class="rounded-3"
                                         style="width: 200px; height: 300px; object-fit: cover;">
                                @else
                                    <div class="bg-secondary bg-opacity-10 d-flex align-items-center justify-content-center border rounded-3" style="width: 200px; height: 300px;">
                                        <span class="text-muted">Foto tidak tersedia</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
