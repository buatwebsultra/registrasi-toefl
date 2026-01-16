@extends('layouts.app')

@section('title', 'Manajemen Galeri')

@section('content')
<div class="container-fluid">
    <div class="row mb-4 align-items-center">
        <div class="col-md-8">
            <h1 class="mb-1">Manajemen Galeri (Slider)</h1>
            <p class="text-muted mb-0">Upload gambar atau video untuk ditampilkan di halaman depan.</p>
        </div>
        <div class="col-md-4 text-md-end mt-3 mt-md-0">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary shadow-sm">
                <i class="fas fa-arrow-left me-2"></i>Kembali ke Dashboard
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row">
        <!-- Form Upload -->
        <div class="col-md-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-upload me-2"></i>Upload Item Baru</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.gallery.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="title" class="form-label">Judul (Opsional)</label>
                            <input type="text" class="form-control" id="title" name="title" placeholder="Contoh: Suasana Ujian">
                        </div>
                        
                        <div class="mb-3">
                            <label for="media_type" class="form-label">Tipe Media</label>
                            <select class="form-select" id="media_type" name="media_type" required>
                                <option value="image">Gambar (JPG, PNG)</option>
                                <option value="video">Video (MP4)</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="file" class="form-label">File</label>
                            <input type="file" class="form-control" id="file" name="file" required>
                            <div class="form-text">Maks ukuran 10MB.</div>
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" checked>
                            <label class="form-check-label" for="is_active">Langsung Aktifkan</label>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-save me-1"></i> Simpan
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Daftar Gallery -->
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Daftar Item Galeri</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 150px;">Media</th>
                                    <th>Info</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($items as $item)
                                <tr>
                                    <td>
                                        @if($item->media_type == 'image')
                                            <img src="{{ $item->url }}" alt="{{ $item->title }}" class="img-thumbnail" style="height: 80px; width: 120px; object-fit: cover;">
                                        @else
                                            <video src="{{ $item->url }}" class="img-thumbnail" style="height: 80px; width: 120px; object-fit: cover;"></video>
                                        @endif
                                        <div class="small text-muted text-center mt-1">{{ strtoupper($item->media_type) }}</div>
                                    </td>
                                    <td>
                                        <div class="fw-bold">{{ $item->title ?? 'Tanpa Judul' }}</div>
                                        <div class="small text-muted">Diupload: {{ $item->created_at->format('d M Y') }}</div>
                                    </td>
                                    <td>
                                        @if($item->is_active)
                                            <span class="badge bg-success">Aktif</span>
                                        @else
                                            <span class="badge bg-secondary">Non-Aktif</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <!-- Toggle Status Button -->
                                            <form action="{{ route('admin.gallery.update', $item->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                @if($item->is_active)
                                                    <button type="submit" class="btn btn-sm btn-warning" title="Non-aktifkan">
                                                        <i class="fas fa-eye-slash"></i>
                                                    </button>
                                                @else
                                                    <input type="hidden" name="is_active" value="1">
                                                    <button type="submit" class="btn btn-sm btn-success" title="Aktifkan">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                @endif
                                            </form>

                                            <!-- Delete Button -->
                                            <form action="{{ route('admin.gallery.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin hapus item ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">Belum ada item galeri.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
