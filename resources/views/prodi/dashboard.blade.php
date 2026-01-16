@extends('layouts.app')

@section('title', 'Dashboard Program Studi')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-body p-0">
                    <div class="bg-primary p-4 text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h1 class="display-6 mb-1">Dashboard Program Studi</h1>
                                <p class="lead mb-0 opacity-75">{{ $studyProgram->name }} ({{ $studyProgram->level }})</p>
                            </div>
                            <div class="text-end d-none d-md-block">
                                <i class="fas fa-university fa-3x opacity-25"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistik Ringkas -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 text-center p-4">
                <div class="text-primary mb-2">
                    <i class="fas fa-users fa-2x"></i>
                </div>
                <h3 class="fw-bold">{{ $participants->total() }}</h3>
                <p class="text-muted mb-0">Total Mahasiswa Terdaftar</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 text-center p-4">
                <div class="text-success mb-2">
                    <i class="fas fa-check-circle fa-2x"></i>
                </div>
                <h3 class="fw-bold">{{ $participants->where('passed', true)->count() }}</h3>
                <p class="text-muted mb-0">Lulus (Halaman Ini)</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 text-center p-4">
                <div class="text-warning mb-2">
                    <i class="fas fa-info-circle fa-2x"></i>
                </div>
                <h5 class="fw-bold">Ambang Batas: {{ $studyProgram->passing_grade }}</h5>
                <p class="text-muted mb-0">Passing Grade Prodi</p>
            </div>
        </div>
    </div>

    <!-- Filter Pencarian -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <form method="GET" action="{{ route('prodi.dashboard') }}">
                        <div class="row g-3">
                            <div class="col-md-5">
                                <label class="form-label fw-bold small text-uppercase">Cari Berdasarkan NIM</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0 rounded-start-pill">
                                        <i class="fas fa-id-card text-muted"></i>
                                    </span>
                                    <input type="text" name="search_nim" class="form-control border-start-0 rounded-end-pill" placeholder="Masukkan NIM..." value="{{ request('search_nim') }}">
                                </div>
                            </div>
                            <div class="col-md-5">
                                <label class="form-label fw-bold small text-uppercase">Cari Berdasarkan Nama</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0 rounded-start-pill">
                                        <i class="fas fa-user text-muted"></i>
                                    </span>
                                    <input type="text" name="search_name" class="form-control border-start-0 rounded-end-pill" placeholder="Masukkan Nama..." value="{{ request('search_name') }}">
                                </div>
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary w-100 rounded-pill">
                                    <i class="fas fa-search me-2"></i>Cari
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold">Daftar Hasil Tes Mahasiswa</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light text-muted small text-uppercase">
                                <tr>
                                    <th class="ps-4">Mahasiswa</th>
                                    <th>NIM</th>
                                    <th>Tanggal Tes</th>
                                    <th>Skor TOEFL</th>
                                    <th>Status</th>
                                    <th class="text-end pe-4">Validasi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($participants as $p)
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold">{{ $p->name }}</div>
                                    </td>
                                    <td>{{ $p->nim }}</td>
                                    <td>{{ $p->test_date ? $p->test_date->format('d M Y') : 'Belum Tes' }}</td>
                                    <td>
                                        @if($p->test_score)
                                            <div class="fs-5 fw-bold text-primary">{{ number_format($p->test_score, 0, '', '') }}</div>
                                            <small class="text-muted">PBT: {{ $p->listening_score_pbt }}/{{ $p->structure_score_pbt }}/{{ $p->reading_score_pbt }}</small>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($p->test_score)
                                            <span class="badge rounded-pill {{ $p->passed ? 'bg-success' : 'bg-warning' }} px-3 py-2">
                                                {{ $p->passed ? 'LULUS' : 'TIDAK LULUS' }}
                                            </span>
                                        @else
                                            <span class="badge rounded-pill bg-secondary px-3 py-2">BELUM DINILAI</span>
                                        @endif
                                    </td>
                                    <td class="text-end pe-4">
                                        @if($p->is_score_validated)
                                            <div class="text-success small">
                                                <i class="fas fa-check-circle me-1"></i>Tervalidasi
                                            </div>
                                        @else
                                            <div class="text-muted small">
                                                <i class="fas fa-clock me-1"></i>Belum Validasi
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <div class="text-muted">
                                            <i class="fas fa-user-slash fa-3x mb-3"></i>
                                            <p>Tidak ada data mahasiswa ditemukan.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($participants->hasPages())
                <div class="card-footer bg-white py-3">
                    <div class="d-flex justify-content-center">
                        {{ $participants->appends(request()->query())->links() }}
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
