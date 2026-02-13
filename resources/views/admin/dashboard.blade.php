@extends('layouts.app')

@section('title', 'Dashboard ' . (Auth::user()->role === 'superadmin' ? 'Super Admin' : (Auth::user()->role === 'admin' ? 'Admin' : 'Operator')))

@section('content')
    <style nonce="{{ $csp_nonce ?? '' }}">
        :root {
            --premium-blue: #1e3a8a;
            --premium-indigo: #4338ca;
            --premium-slate: #0f172a;
            --glass-bg: rgba(255, 255, 255, 0.85);
        }

        .dashboard-header {
            background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 100%);
            border-radius: 1.5rem;
            padding: 2.5rem;
            color: white;
            margin-bottom: 2.5rem;
            position: relative;
            overflow: hidden;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.3);
        }

        .dashboard-header::after {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 300px;
            height: 100%;
            background: url('https://www.transparenttextures.com/patterns/cubes.png');
            opacity: 0.05;
            pointer-events: none;
        }

        .metric-card {
            border: none;
            border-radius: 1.25rem;
            background: white;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid rgba(226, 232, 240, 0.8);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }

        .metric-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }

        .metric-icon {
            width: 60px;
            height: 60px;
            border-radius: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .premium-card {
            border: none;
            border-radius: 1.5rem;
            background: white;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            margin-bottom: 2rem;
        }

        .premium-card .card-header {
            background: #f8fafc;
            border-bottom: 1px solid #f1f5f9;
            padding: 1.5rem;
        }

        .premium-card .card-header h5 {
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 0;
        }

        .form-control-premium {
            border-radius: 0.75rem;
            padding: 0.75rem 1rem;
            border: 1px solid #e2e8f0;
            background: #f8fafc;
            transition: all 0.2s;
        }

        .form-control-premium:focus {
            background: white;
            border-color: var(--premium-indigo);
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
        }

        .btn-premium-success {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            border: none;
            font-weight: 600;
            transition: all 0.2s;
        }

        .btn-premium-success:hover {
            transform: scale(1.02);
            box-shadow: 0 10px 15px -3px rgba(16, 185, 129, 0.2);
            color: white;
        }

        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 9999px;
            font-weight: 600;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .table-premium thead th {
            background: #f8fafc;
            color: #64748b;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #e2e8f0;
        }

        .table-premium tbody td {
            padding: 1rem 1.5rem;
            vertical-align: middle;
            color: #1e293b;
        }

        .table-premium tbody tr {
            transition: all 0.2s;
        }

        .table-premium tbody tr:hover {
            background: #f1f5f9;
        }

        .action-btn {
            width: 32px;
            height: 32px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 0.5rem;
            transition: all 0.2s;
            margin: 0 2px;
        }

        .action-btn:hover {
            transform: scale(1.1);
        }

        .admin-role-badge {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.875rem;
        }

        .avatar-wrapper {
            width: 100px;
            height: 100px;
            position: relative;
        }

        .avatar-img {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid rgba(255, 255, 255, 0.2);
        }

        .avatar-placeholder {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            border: 4px solid rgba(255, 255, 255, 0.2);
        }

        .bg-metric-blue {
            background: #eff6ff !important;
            color: #2563eb !important;
        }

        .bg-metric-amber {
            background: #fffbeb !important;
            color: #d97706 !important;
        }

        .bg-metric-emerald {
            background: #ecfdf5 !important;
            color: #059669 !important;
        }

        .bg-metric-indigo {
            background: #eef2ff !important;
            color: #4f46e5 !important;
        }

        .bg-metric-rose {
            background: #fff1f2 !important;
            color: #be123c !important;
        }

        .bg-metric-rose-dark {
            background: #fff1f2 !important;
            color: #e11d48 !important;
        }

        .min-w-60 {
            min-width: 60px !important;
        }

        .h-4px {
            height: 4px !important;
        }

        .w-150px {
            width: 150px !important;
        }

        .opacity-50 {
            opacity: 0.5 !important;
        }

        .progress-bar-indigo {
            background: #6366f1 !important;
        }
    </style>

    <div class="py-4 px-4 px-lg-5">
        <!-- Premium Header -->
        <div class="dashboard-header">
            <div class="row align-items-center">
                <div class="col-lg-auto mb-4 mb-lg-0 text-center">
                    <div class="avatar-wrapper mx-auto">
                        @if(Auth::user()->photo_url)
                            <img src="{{ Auth::user()->photo_url }}" alt="Profile Photo" class="avatar-img shadow-sm">
                        @else
                            <div class="avatar-placeholder">
                                <i class="fas fa-user"></i>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="col-lg">
                    <h1 class="display-5 fw-bold mb-2">Selamat Datang, {{ Auth::user()->name }}</h1>
                    <p class="lead opacity-75 mb-4">Kelola jadwal dan pendaftaran tes TOEFL dari dashboard terintegrasi
                        Anda.</p>
                    <div class="d-flex flex-wrap gap-2">
                        <span class="admin-role-badge">
                            <i class="fas fa-user-shield me-2"></i>
                            {{ Auth::user()->role === 'superadmin' ? 'Super Admin' : (Auth::user()->role === 'admin' ? 'Admin' : 'Operator') }}
                        </span>
                        <span class="admin-role-badge">
                            <i class="fas fa-calendar-check me-2"></i> {{ date('l, d F Y') }}
                        </span>
                    </div>
                </div>
                <div class="col-lg-4 text-center text-lg-end mt-4 mt-lg-0">
                    <div class="btn-group shadow-sm">
                        @if(Auth::user()->isSuperAdmin())
                            <a href="{{ route('admin.users.index') }}"
                                class="btn btn-light px-4 py-2 border-end border-light-subtle">
                                <i class="fas fa-users-cog me-2"></i> Manajemen
                            </a>
                            <a href="{{ route('admin.logs.index') }}"
                                class="btn btn-light px-4 py-2 border-end border-light-subtle">
                                <i class="fas fa-history me-2"></i> History
                            </a>
                        @endif
                        <a href="{{ route('admin.profile') }}"
                            class="btn btn-light px-4 py-2 border-end border-light-subtle">
                            <i class="fas fa-user-circle me-2"></i> Profil
                        </a>
                        <a href="#" id="btn-logout-dashboard" class="btn btn-light px-4 py-2 text-danger">
                            <i class="fas fa-sign-out-alt me-2"></i> Keluar
                        </a>
                    </div>
                    <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="row g-4 mb-5">
            <div class="col-xl-3 col-md-6">
                <div class="metric-card p-4">
                    <div class="metric-icon bg-metric-blue">
                        <i class="fas fa-users"></i>
                    </div>
                    <h6 class="text-muted fw-bold mb-1">Total Peserta</h6>
                    <h3 class="fw-extrabold mb-0">{{ number_format($totalParticipants) }}</h3>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="metric-card p-4">
                    <div class="metric-icon bg-metric-amber">
                        @php
                            $pendingCount = \App\Models\Participant::where('status', 'pending')->count();
                        @endphp
                        <i class="fas fa-clock"></i>
                    </div>
                    <h6 class="text-muted fw-bold mb-1">Validasi Pembayaran</h6>
                    <div class="d-flex align-items-center justify-content-between">
                        <h3 class="fw-extrabold mb-0">{{ $pendingCount }}</h3>
                        <a href="{{ route('admin.participants.pending') }}"
                            class="btn btn-sm btn-outline-warning rounded-pill px-3">Detail</a>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="metric-card p-4">
                    <div class="metric-icon bg-metric-emerald">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <h6 class="text-muted fw-bold mb-1">Jadwal Aktif</h6>
                    @php
                        $activeSchedulesCount = \App\Models\Schedule::where('status', 'available')->count();
                    @endphp
                    <h3 class="fw-extrabold mb-0">{{ $activeSchedulesCount }}</h3>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="metric-card p-4">
                    <div class="metric-icon bg-metric-indigo">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h6 class="text-muted fw-bold mb-1">Tingkat Kelulusan</h6>
                    <h3 class="fw-extrabold mb-0">
                        @php
                            $graduatedCount = \App\Models\Participant::where('passed', true)->count();
                            $rate = $totalParticipants > 0 ? round(($graduatedCount / $totalParticipants) * 100) : 0;
                        @endphp
                        {{ $rate }}%
                    </h3>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Sidebar Tasks / Actions -->
            <div class="col-lg-4 mb-4 mb-lg-0">
                @if(Auth::user()->isAdmin())
                    <div class="premium-card">
                        <div class="card-header bg-white">
                            <h5 class="mb-0"><i class="fas fa-plus-circle me-2 text-primary"></i>Buat Jadwal Baru</h5>
                        </div>
                        <div class="card-body p-4">
                            <form action="{{ route('admin.schedule.create') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label fw-bold small text-muted text-uppercase">Tanggal Tes</label>
                                    <input type="date" class="form-control-premium w-100" name="date" min="{{ date('Y-m-d') }}"
                                        required>
                                </div>
                                <div class="row g-3 mb-3">
                                    <div class="col-6">
                                        <label class="form-label fw-bold small text-muted text-uppercase">Waktu</label>
                                        <input type="time" class="form-control-premium w-100" name="time" value="08:00"
                                            required>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label fw-bold small text-muted text-uppercase">Kapasitas</label>
                                        <input type="number" class="form-control-premium w-100" name="capacity" min="1"
                                            required>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold small text-muted text-uppercase">Ruangan</label>
                                    <select class="form-control-premium w-100" name="room" required>
                                        <option value="" selected disabled>Pilih Ruangan</option>
                                        @for($i = 1; $i <= 10; $i++)
                                            <option value="UPT-{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}">
                                                UPT-{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold small text-muted text-uppercase">Kategori</label>
                                    <input type="text" class="form-control-premium w-100" name="category" value="TOEFL-LIKE"
                                        required>
                                </div>
                                <div class="d-grid mt-4">
                                    <button type="submit" class="btn btn-premium-success py-3 rounded-3">
                                        <i class="fas fa-save me-2"></i>Terbitkan Jadwal
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endif

                <div class="premium-card">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="fas fa-graduation-cap me-2 text-indigo-600"></i>Data Master</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="d-grid gap-3">
                            <a href="{{ route('admin.faculties.index') }}"
                                class="btn btn-outline-light text-dark border p-3 d-flex align-items-center justify-content-between rounded-3 transition-all hover:bg-light">
                                <span class="fw-bold"><i class="fas fa-university me-3 text-primary"></i>Kelola
                                    Fakultas</span>
                                <i class="fas fa-chevron-right small opacity-50"></i>
                            </a>
                            <a href="{{ route('admin.study-programs.index') }}"
                                class="btn btn-outline-light text-dark border p-3 d-flex align-items-center justify-content-between rounded-3 transition-all hover:bg-light">
                                <span class="fw-bold"><i class="fas fa-book me-3 text-info"></i>Kelola Program Studi</span>
                                <i class="fas fa-chevron-right small opacity-50"></i>
                            </a>
                            <a href="{{ route('admin.gallery.index') }}"
                                class="btn btn-outline-light text-dark border p-3 d-flex align-items-center justify-content-between rounded-3 transition-all hover:bg-light">
                                <span class="fw-bold"><i class="fas fa-images me-3 text-warning"></i>Kelola Galeri
                                    (Slider)</span>
                                <i class="fas fa-chevron-right small opacity-50"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Schedule Table -->
            <div class="col-lg-8">
                <div class="premium-card">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-calendar-alt me-2 text-primary"></i>Jadwal Aktif</h5>
                        <div class="d-flex gap-2">
                            <form action="{{ route('admin.dashboard') }}" method="GET" class="d-flex gap-2">
                                <input type="date" name="search_date" class="form-control form-control-sm border-radius-lg"
                                    value="{{ $searchDate }}">
                                <button type="submit" class="btn btn-sm btn-primary rounded-3 px-3">Filter</button>
                            </form>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-premium mb-0">
                            <thead>
                                <tr>
                                    <th>Jadwal & Ruangan</th>
                                    <th>Kapasitas</th>
                                    <th>Kategori</th>
                                    <th>Status</th>
                                    <th class="text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($schedules as $schedule)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="rounded-3 p-2 me-3 text-center min-w-60 bg-metric-indigo">
                                                    <div class="fw-bold small">{{ $schedule->date->format('M') }}</div>
                                                    <div class="fs-5 fw-extrabold">{{ $schedule->date->format('d') }}</div>
                                                </div>
                                                <div>
                                                    <div class="fw-bold">
                                                        {{ $schedule->time ? \Carbon\Carbon::parse($schedule->time)->format('H:i') : '08:00' }}
                                                    </div>
                                                    <div class="small text-muted"><i
                                                            class="fas fa-map-marker-alt me-1"></i>{{ $schedule->room }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="fw-bold">{{ min($schedule->participants_count, $schedule->capacity) }} /
                                                {{ $schedule->capacity }}</div>
                                            <div class="progress mt-1 h-4px">
                                                @php
                                                    $percent = $schedule->capacity > 0 ? ($schedule->participants_count / $schedule->capacity) * 100 : 0;
                                                @endphp
                                                <div class="progress-bar progress-bar-indigo dashboard-progress"
                                                    data-percent="{{ min($percent, 100) }}"></div>
                                            </div>
                                        </td>
                                        <td><span class="fw-semibold">{{ $schedule->category }}</span></td>
                                        <td>
                                            @if($schedule->status === 'available')
                                                <span class="status-badge bg-metric-emerald">Tersedia</span>
                                            @else
                                                <span class="status-badge bg-metric-rose">Penuh / Tutup</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            @if($schedule->pending_count > 0)
                                                <a href="{{ route('admin.participants.list', ['id' => $schedule->id, 'status' => 'pending']) }}"
                                                    class="btn btn-sm btn-warning rounded-pill me-1"
                                                    title="Validasi {{ $schedule->pending_count }} Peserta">
                                                    <i class="fas fa-check-circle me-1"></i> Validasi
                                                    ({{ $schedule->pending_count }})
                                                </a>
                                            @endif
                                            <a href="{{ route('admin.participants.list', $schedule->id) }}"
                                                class="action-btn bg-metric-blue" title="Daftar Peserta">
                                                <i class="fas fa-users"></i>
                                            </a>
                                            @if(Auth::user()->isAdmin())
                                                <button class="action-btn bg-metric-amber border-0" data-bs-toggle="modal"
                                                    data-bs-target="#editModal{{ $schedule->id }}" title="Edit Jadwal">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="action-btn bg-metric-rose-dark border-0" data-bs-toggle="modal"
                                                    data-bs-target="#deleteModal{{ $schedule->id }}" title="Hapus Jadwal">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5">
                                            <img src="https://cdni.iconscout.com/illustration/premium/thumb/no-data-found-8867280-7223938.png"
                                                alt="No data" class="w-150px opacity-50">
                                            <p class="text-muted mt-3">Tidak ada jadwal ditemukan</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer bg-white border-0 py-3">
                        {{ $schedules->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modals -->
    @foreach($schedules as $schedule)
        <!-- Delete Modal -->
        <div class="modal fade" id="deleteModal{{ $schedule->id }}" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg rounded-4">
                    <div class="modal-body p-4 text-center">
                        <div class="mb-4 text-rose-600">
                            <i class="fas fa-exclamation-triangle fa-4x"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Konfirmasi Penghapusan</h4>
                        <p class="text-muted mb-4">Anda akan menghapus jadwal di ruangan <strong>{{ $schedule->room }}</strong>.
                            Tindakan ini tidak dapat dibatalkan.</p>
                        <div class="bg-light p-3 rounded-3 mb-4 text-start">
                            <div class="small fw-bold text-uppercase text-muted mb-2">Verifikasi Nama Ruangan</div>
                            <input type="text" class="form-control-premium w-100" id="confirmRoomName{{ $schedule->id }}"
                                placeholder="Ketik '{{ $schedule->room }}' untuk menghapus">
                            <div id="confirmError{{ $schedule->id }}" class="text-danger small mt-1 d-none">Nama ruangan tidak
                                sesuai</div>
                        </div>
                        <div class="d-flex gap-3 mt-4">
                            <button type="button" class="btn btn-light flex-grow-1 py-2 rounded-3"
                                data-bs-dismiss="modal">Batal</button>
                            <form action="{{ route('admin.schedule.delete', $schedule->id) }}" method="POST" class="flex-grow-1"
                                id="deleteForm{{ $schedule->id }}">
                                @csrf
                                @method('DELETE')
                                <button type="button"
                                    class="btn btn-danger w-100 py-2 rounded-3 fw-bold btn-confirm-delete-schedule"
                                    data-id="{{ $schedule->id }}" data-room="{{ $schedule->room }}">Hapus Sekarang</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Modal -->
        <div class="modal fade" id="editModal{{ $schedule->id }}" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg rounded-4">
                    <div class="modal-header border-0 p-4 pb-0">
                        <h5 class="fw-bold mb-0">Edit Jadwal #{{ $schedule->id }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('admin.schedule.update', $schedule->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body p-4">
                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted text-uppercase">Tanggal Tes</label>
                                <input type="date" class="form-control-premium w-100" name="date"
                                    value="{{ $schedule->date->format('Y-m-d') }}" required>
                            </div>
                            <div class="row g-3 mb-3">
                                <div class="col-6">
                                    <label class="form-label fw-bold small text-muted text-uppercase">Waktu</label>
                                    <input type="time" class="form-control-premium w-100" name="time"
                                        value="{{ $schedule->time ? \Carbon\Carbon::parse($schedule->time)->format('H:i') : '08:00' }}"
                                        required>
                                </div>
                                <div class="col-6">
                                    <label class="form-label fw-bold small text-muted text-uppercase">Kapasitas</label>
                                    <input type="number" class="form-control-premium w-100" name="capacity"
                                        value="{{ $schedule->capacity }}" min="1" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted text-uppercase">Ruangan</label>
                                <select class="form-control-premium w-100" name="room" required>
                                    @for($i = 1; $i <= 10; $i++)
                                        @php $roomValue = 'UPT-' . str_pad($i, 2, '0', STR_PAD_LEFT); @endphp
                                        <option value="{{ $roomValue }}" {{ $schedule->room == $roomValue ? 'selected' : '' }}>
                                            {{ $roomValue }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="row g-3 mb-3">
                                <div class="col-12">
                                    <label class="form-label fw-bold small text-muted text-uppercase">Nama Penandatangan</label>
                                    <input type="text" class="form-control-premium w-100" name="signature_name"
                                        value="{{ $schedule->signature_name }}">
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-bold small text-muted text-uppercase">NIP Penandatangan</label>
                                    <input type="text" class="form-control-premium w-100" name="signature_nip"
                                        value="{{ $schedule->signature_nip }}">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer border-0 p-4 pt-0">
                            <button type="submit" class="btn btn-primary w-100 py-3 rounded-3 fw-bold shadow-sm">
                                <i class="fas fa-save me-2"></i>Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

    <script nonce="{{ $csp_nonce ?? '' }}">
        document.addEventListener('DOMContentLoaded', function () {
            // Set dynamic progress bar widths
            document.querySelectorAll('.dashboard-progress').forEach(el => {
                const percent = el.getAttribute('data-percent');
                el.style.width = percent + '%';
            });

            // Set up delete confirmation event listeners
            document.querySelectorAll('.btn-confirm-delete-schedule').forEach(btn => {
                btn.addEventListener('click', function () {
                    const scheduleId = this.getAttribute('data-id');
                    const roomName = this.getAttribute('data-room');
                    confirmDelete(scheduleId, roomName);
                });
            });

            // Logout button handler (CSP compliant)
            const logoutBtn = document.getElementById('btn-logout-dashboard');
            if (logoutBtn) {
                logoutBtn.addEventListener('click', function (e) {
                    e.preventDefault();
                    document.getElementById('logout-form').submit();
                });
            }
        });

        function confirmDelete(scheduleId, roomName) {
            const inputRoomName = document.getElementById(`confirmRoomName${scheduleId}`).value;
            const errorMessage = document.getElementById(`confirmError${scheduleId}`);

            if (inputRoomName.trim() !== roomName) {
                errorMessage.classList.remove('d-none');
                return false;
            }

            const form = document.getElementById(`deleteForm${scheduleId}`);
            form.submit();
        }
    </script>

    <!-- Error/Success Modal -->
    <div class="modal fade" id="notificationModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-body p-5 text-center">
                    <div class="mb-4" id="modalIcon">
                        <!-- Icon will be inserted here -->
                    </div>
                    <h4 class="fw-bold mb-3" id="modalTitle"></h4>
                    <p class="text-muted mb-4" id="modalMessage"></p>
                    <button type="button" class="btn btn-primary px-5 py-2 rounded-pill"
                        data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <script nonce="{{ $csp_nonce ?? '' }}">
        // Auto-show modal for success/error messages
        @if(session('success'))
            document.addEventListener('DOMContentLoaded', function () {
                showNotificationModal('success', 'Berhasil!', '{{ session('success') }}');
            });
        @endif

        @if(session('error'))
            document.addEventListener('DOMContentLoaded', function () {
                showNotificationModal('error', 'Perhatian!', '{{ session('error') }}');
            });
        @endif

        function showNotificationModal(type, title, message) {
            const modal = new bootstrap.Modal(document.getElementById('notificationModal'));
            const iconDiv = document.getElementById('modalIcon');
            const titleDiv = document.getElementById('modalTitle');
            const messageDiv = document.getElementById('modalMessage');

            if (type === 'success') {
                iconDiv.innerHTML = '<i class="fas fa-check-circle fa-4x text-success"></i>';
                titleDiv.className = 'fw-bold mb-3 text-success';
            } else if (type === 'error') {
                iconDiv.innerHTML = '<i class="fas fa-exclamation-circle fa-4x text-danger"></i>';
                titleDiv.className = 'fw-bold mb-3 text-danger';
            }

            titleDiv.textContent = title;
            messageDiv.textContent = message;
            modal.show();
        }
    </script>
@endsection