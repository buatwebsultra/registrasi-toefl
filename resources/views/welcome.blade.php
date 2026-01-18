@extends('layouts.app')

@section('body-class', 'welcome-page')

@section('title', 'Sistem Informasi Pelayanan Bahasa UHO (SIPENA)')

@section('content')
<div class="registration-container">
    <div class="row mb-5">
        <div class="col-md-12 text-center">
            <!-- University Logo -->
            <div class="mb-4">
                <img src="{{ asset('logo-uho-dan-diktisaintek-768x143.png') }}"
                     alt="Logo UHO dan Diktisaintek"
                     class="img-fluid shadow rounded welcome-logo">
            </div>

            <h1 class="display-4 fw-bold text-primary">Sistem Informasi Pelayanan Bahasa UHO (SIPENA)</h1>
            <p class="lead text-muted">Daftar ujian TOEFL Anda dengan mudah dan cepat</p>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-lg h-100 card-hover bg-light">
                <div class="card-body d-flex flex-column text-center p-5">
                    <div class="mb-4">
                        <div class="d-inline-flex align-items-center justify-content-center bg-white rounded-circle p-4 shadow-sm" style="width: 100px; height: 100px;">
                            <i class="fas fa-user-graduate fa-2x text-primary"></i>
                        </div>
                    </div>
                    <h5 class="card-title fw-bold fs-4">Untuk Peserta</h5>
                    <p class="card-text text-muted mb-4">Daftar ujian TOEFL mendatang, lihat status pendaftaran Anda, dan unduh kartu ujian Anda.</p>
                    <div class="d-grid gap-3 mt-auto">
                        <a href="{{ route('participant.register.form') }}" class="btn btn-primary btn-lg rounded-pill px-4 py-3">Daftar Sekarang</a>
                        <a href="{{ route('participant.login') }}" class="btn btn-outline-primary btn-lg rounded-pill px-4 py-3">Login Peserta</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-lg h-100 card-hover bg-light">
                <div class="card-body d-flex flex-column p-5">
                    <div class="d-flex align-items-center mb-4">
                        <div class="d-inline-flex align-items-center justify-content-center bg-white rounded-circle p-3 shadow-sm me-3" style="width: 60px; height: 60px;">
                            <i class="fas fa-calendar-alt fa-lg text-success"></i>
                        </div>
                        <h5 class="card-title fw-bold fs-4 mb-0">Jadwal TOEFL Terbaru</h5>
                    </div>
                    <div class="flex-grow-1">
                        @if($latestSchedules && count($latestSchedules) > 0)
                            <div id="scheduleTableContainer" class="schedule-list-container">
                                @foreach($latestSchedules as $schedule)
                                    <div class="schedule-card mb-3 p-3 bg-white rounded border shadow-sm">
                                        <div class="row align-items-center">
                                            <div class="col-3">
                                                <div class="schedule-date text-center">
                                                    <div class="fw-bold text-primary">{{ $schedule->date->format('d') }}</div>
                                                    <div class="text-muted small">{{ $schedule->date->format('M Y') }}</div>
                                                </div>
                                            </div>
                                            <div class="col-2">
                                                <div class="schedule-time text-center">
                                                    <div class="fw-bold">{{ $schedule->time ? \Carbon\Carbon::parse($schedule->time)->format('H:i') : '' }}</div>
                                                </div>
                                            </div>
                                            <div class="col-2">
                                                <div class="schedule-room">
                                                    <div class="text-muted small">Ruang</div>
                                                    <div>{{ $schedule->room }}</div>
                                                </div>
                                            </div>
                                            <div class="col-3">
                                                <div class="schedule-capacity">
                                                    <div class="text-muted small">Kapasitas</div>
                                                    <div>
                                                        <span class="fw-bold">{{ $schedule->used_capacity }}</span>/
                                                        <span>{{ $schedule->capacity }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-2">
                                                <div class="schedule-status text-center">
                                                    @if($schedule->status === 'available' && $schedule->used_capacity >= $schedule->capacity * 0.8)
                                                        <span class="badge bg-warning text-dark px-3 py-2">Hampir Penuh</span>
                                                    @elseif($schedule->status === 'available')
                                                        <span class="badge bg-success px-3 py-2">Tersedia</span>
                                                    @else
                                                        <span class="badge bg-danger px-3 py-2">Penuh</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-center text-muted my-auto">Belum ada jadwal TOEFL yang tersedia</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-md-12">
            <div class="card border-0 shadow-lg bg-light">
                <div class="card-body p-5">
                    <h5 class="card-title text-center fw-bold mb-5 fs-3">Cara Pendaftaran</h5>
                    <div class="row g-4">
                        <div class="col-md-4 text-center">
                            <div class="p-4">
                                <div class="rounded-circle d-inline-flex align-items-center justify-content-center bg-primary text-white mb-4 mx-auto" style="width: 80px; height: 80px; font-size: 2rem; font-weight: bold;">
                                    1
                                </div>
                                <h6 class="fw-bold">Pilih Jadwal</h6>
                                <p class="mb-0 text-muted">Pilih jadwal ujian TOEFL yang tersedia sesuai ketersediaan</p>
                            </div>
                        </div>
                        <div class="col-md-4 text-center">
                            <div class="p-4">
                                <div class="rounded-circle d-inline-flex align-items-center justify-content-center bg-primary text-white mb-4 mx-auto" style="width: 80px; height: 80px; font-size: 2rem; font-weight: bold;">
                                    2
                                </div>
                                <h6 class="fw-bold">Isi Data</h6>
                                <p class="mb-0 text-muted">Lengkapi formulir pendaftaran dengan data diri yang benar</p>
                            </div>
                        </div>
                        <div class="col-md-4 text-center">
                            <div class="p-4">
                                <div class="rounded-circle d-inline-flex align-items-center justify-content-center bg-primary text-white mb-4 mx-auto" style="width: 80px; height: 80px; font-size: 2rem; font-weight: bold;">
                                    3
                                </div>
                                <h6 class="fw-bold">Selesai</h6>
                                <p class="mb-0 text-muted">Unggah dokumen yang diperlukan dan selesaikan proses pendaftaran</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Gallery Section -->
    @if(isset($galleryItems) && count($galleryItems) > 0)
    <div class="row mt-5 mb-5">
        <div class="col-md-12">
            <h5 class="text-center fw-bold mb-4 fs-3 text-primary">Galeri Kegiatan</h5>
            <div id="galleryCarousel" class="carousel slide shadow-lg rounded-4 overflow-hidden bg-dark" data-bs-ride="carousel">
                <div class="carousel-indicators">
                    @foreach($galleryItems as $key => $item)
                        <button type="button" data-bs-target="#galleryCarousel" data-bs-slide-to="{{ $key }}" class="{{ $key == 0 ? 'active' : '' }}" aria-current="{{ $key == 0 ? 'true' : 'false' }}" aria-label="Slide {{ $key + 1 }}"></button>
                    @endforeach
                </div>
                <div class="carousel-inner">
                    @foreach($galleryItems as $key => $item)
                        <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                            <div class="ratio ratio-16x9 position-relative overflow-hidden" style="max-height: 500px;">
                                <!-- Blurred Background Layer -->
                                <div class="position-absolute top-0 start-0 w-100 h-100" style="z-index: 1;">
                                    @if($item->media_type == 'image')
                                        <img src="{{ $item->url }}" 
                                             alt="Background" 
                                             style="width: 100%; height: 100%; object-fit: cover; filter: blur(100px) brightness(0.4); transform: scale(1.1);">
                                    @else
                                        <video src="{{ $item->url }}" 
                                               style="width: 100%; height: 100%; object-fit: cover; filter: blur(100px) brightness(0.4); transform: scale(1.1);" 
                                               muted loop autoplay playsinline></video>
                                    @endif
                                </div>

                                <!-- Main Content Layer -->
                                <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center" style="z-index: 2;">
                                    @if($item->media_type == 'image')
                                        <img src="{{ $item->url }}" 
                                             class="d-block gallery-item-trigger" 
                                             alt="{{ $item->title ?? 'Gallery Image' }}" 
                                             style="max-width: 100%; max-height: 100%; width: auto; height: auto; object-fit: contain; cursor: pointer; box-shadow: 0 0 20px rgba(0,0,0,0.5);"
                                             data-bs-toggle="modal" 
                                             data-bs-target="#galleryModal"
                                             data-type="image"
                                             data-src="{{ $item->url }}"
                                             data-title="{{ $item->title }}">
                                    @else
                                        <video src="{{ $item->url }}" 
                                               class="d-block gallery-item-trigger" 
                                               controls 
                                               style="max-width: 100%; max-height: 100%; width: auto; height: auto; object-fit: contain; cursor: pointer; box-shadow: 0 0 20px rgba(0,0,0,0.5);"
                                               data-bs-toggle="modal" 
                                               data-bs-target="#galleryModal"
                                               data-type="video"
                                               data-src="{{ $item->url }}"
                                               data-title="{{ $item->title }}"></video>
                                    @endif
                                </div>
                            </div>
                            
                            @if($item->title)
                                <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-75 rounded-3 py-2 px-3 mb-4 mx-auto" style="max-width: 80%; z-index: 3;">
                                    <h5 class="fw-bold mb-1 text-white">{{ $item->title }}</h5>
                                    @if($item->description)
                                        <p class="mb-0 small text-white-50">{{ $item->description }}</p>
                                    @endif
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#galleryCarousel" data-bs-slide="prev" style="z-index: 3;">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#galleryCarousel" data-bs-slide="next" style="z-index: 3;">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Gallery Modal -->
<div class="modal fade" id="galleryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content bg-transparent border-0 shadow-none">
            <div class="modal-body p-0 text-center position-relative">
                <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3 z-3" data-bs-dismiss="modal" aria-label="Close"></button>
                <div id="modalContentWrapper" class="d-flex align-items-center justify-content-center" style="min-height: 80vh;">
                    <!-- Content injected via JS -->
                </div>
                <div id="modalCaption" class="text-white mt-3 fw-bold fs-5"></div>
            </div>
        </div>
    </div>
</div>

<script nonce="{{ $csp_nonce ?? '' }}">
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('scheduleTableContainer');
    if (container) {
        const schedules = @json($latestSchedules ?? []);
        // Only show scrollbar if there are more than 5 schedules
        if (schedules.length > 5) {
            // Set fixed height to enable scrolling
            container.style.maxHeight = '300px';
            container.style.overflowY = 'auto';
            // Add custom scrollbar styling for modern look
            container.style.cssText += 'scrollbar-width: thin; scrollbar-color: #0d6efd #f0f0f0;';
        }
    }

    // Gallery Modal Logic
    const galleryModal = document.getElementById('galleryModal');
    if (galleryModal) {
        galleryModal.addEventListener('show.bs.modal', function (event) {
            const triggerEl = event.relatedTarget;
            const type = triggerEl.getAttribute('data-type');
            const src = triggerEl.getAttribute('data-src');
            const title = triggerEl.getAttribute('data-title') || '';
            const wrapper = document.getElementById('modalContentWrapper');
            const caption = document.getElementById('modalCaption');

            wrapper.innerHTML = '';
            caption.textContent = title;

            if (type === 'image') {
                const img = document.createElement('img');
                img.src = src;
                img.className = 'img-fluid rounded shadow-lg';
                img.style.maxHeight = '85vh';
                img.style.objectFit = 'contain';
                wrapper.appendChild(img);
            } else if (type === 'video') {
                const video = document.createElement('video');
                video.src = src;
                video.controls = true;
                video.autoplay = true;
                video.className = 'img-fluid rounded shadow-lg';
                video.style.maxHeight = '85vh';
                video.style.maxWidth = '100%';
                wrapper.appendChild(video);
            }
        });

        // Stop video when modal closes
        galleryModal.addEventListener('hidden.bs.modal', function () {
            const wrapper = document.getElementById('modalContentWrapper');
            wrapper.innerHTML = ''; 
        });
    }
});
</script>
@endsection