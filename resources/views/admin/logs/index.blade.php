@extends('layouts.app')

@section('title', 'Log Aktivitas')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="display-6">Riwayat Riwayat Kegiatan</h1>
                    <p class="lead text-muted">Log lengkap aktivitas Admin, Operator, dan Peserta</p>
                </div>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-primary rounded-pill px-4">
                    <i class="fas fa-arrow-left me-2"></i>Kembali ke Dashboard
                </a>
            </div>
        </div>
    </div>

    <!-- Date Filter and Download Section -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body">
                    <form action="{{ route('admin.logs.download') }}" method="POST" class="row g-3 align-items-end">
                        @csrf
                        <div class="col-md-4">
                            <label for="start_date" class="form-label fw-bold">
                                <i class="fas fa-calendar-alt me-1"></i>Dari Tanggal
                            </label>
                            <input type="date" 
                                   class="form-control" 
                                   id="start_date" 
                                   name="start_date"
                                   max="{{ date('Y-m-d') }}">
                            <small class="text-muted">Kosongkan untuk download semua</small>
                        </div>
                        <div class="col-md-4">
                            <label for="end_date" class="form-label fw-bold">
                                <i class="fas fa-calendar-alt me-1"></i>Sampai Tanggal
                            </label>
                            <input type="date" 
                                   class="form-control" 
                                   id="end_date" 
                                   name="end_date"
                                   max="{{ date('Y-m-d') }}">
                            <small class="text-muted">Kosongkan untuk download semua</small>
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-success w-100 rounded-pill px-4">
                                <i class="fas fa-download me-2"></i>Download Log
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">Waktu (Tanggal, Jam, Menit, Detik)</th>
                                    <th>User</th>
                                    <th>Kegiatan</th>
                                    <th>Keterangan</th>
                                    <th class="pe-4">IP Address</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($logs as $log)
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold">{{ $log->created_at->format('d/m/Y') }}</div>
                                        <small class="text-muted">{{ $log->created_at->format('H:i:s') }}</small>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="rounded-circle p-2 me-2 {{ $log->user_type === 'admin' ? 'bg-primary bg-opacity-10 text-primary' : 'bg-success bg-opacity-10 text-success' }}" style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;">
                                                <i class="fas {{ $log->user_type === 'admin' ? 'fa-user-shield' : 'fa-user' }}"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold">{{ $log->user_name }}</div>
                                                <small class="text-muted text-capitalize">{{ $log->user_type }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge rounded-pill bg-secondary px-3 py-2">
                                            {{ $log->action }}
                                        </span>
                                    </td>
                                    <td>{{ $log->description }}</td>
                                    <td class="pe-4">
                                        <small class="text-muted">{{ $log->ip_address }}</small>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5">
                                        <i class="fas fa-info-circle fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">Belum ada riwayat kegiatan tercatat.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($logs->hasPages())
                <div class="card-footer bg-white py-3 rounded-bottom-4">
                    {{ $logs->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const downloadForm = document.querySelector('form[action*="/admin/logs/download"]');
    
    if (downloadForm) {
        downloadForm.addEventListener('submit', async function(e) {
            e.preventDefault(); // Prevent default form submission
            
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            
            // Show loading state
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Downloading...';
            
            try {
                const formData = new FormData(this);
                
                // Send fetch request
                const response = await fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                if (!response.ok) {
                    throw new Error('Download failed: ' + response.statusText);
                }
                
                // Get the blob from response
                const blob = await response.blob();
                
                // Extract filename from Content-Disposition header
                const contentDisposition = response.headers.get('Content-Disposition');
                let filename = 'activity_logs.txt';
                if (contentDisposition) {
                    const matches = /filename="?([^"]+)"?/.exec(contentDisposition);
                    if (matches && matches[1]) {
                        filename = matches[1];
                    }
                }
                
                // Create download link
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = filename;
                document.body.appendChild(a);
                a.click();
                
                // Cleanup
                window.URL.revokeObjectURL(url);
                document.body.removeChild(a);
                
                // Show success message (optional)
                console.log('File downloaded successfully: ' + filename);
                
            } catch (error) {
                console.error('Download error:', error);
                alert('Terjadi kesalahan saat mendownload file: ' + error.message);
            } finally {
                // Restore button state
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        });
    }
});
</script>
@endsection
