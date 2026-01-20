@extends('layouts.app')

@section('title', 'Kelola Program Studi')

@section('content')
<div class="row">
    <div class="col-md-12">
        <h1>Kelola Program Studi</h1>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary mb-3">Kembali ke Dasbor</a>
        <a href="{{ route('admin.study-programs.create') }}" class="btn btn-primary mb-3">Tambah Program Studi Baru</a>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Nama</th>
                                <th>Jenjang</th>
                                <th>Fakultas</th>
                                <th>Ambang Batas (Passing Grade)</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($studyPrograms as $program)
                            <tr>
                                <td>{{ $program->id }}</td>
                                <td>{{ $program->name }}</td>
                                <td>{{ $program->level }}</td>
                                <td>{{ $program->faculty->name }}</td>
                                <td>{{ $program->passing_grade }}</td>
                                <td>
                                    <a href="{{ route('admin.study-programs.edit', $program->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                    <form action="{{ route('admin.study-programs.delete', $program->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm btn-delete-confirm" data-message="Apakah Anda yakin ingin menghapus program studi ini?">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center">Tidak ada program studi ditemukan</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center">
                    {{ $studyPrograms->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script nonce="{{ $csp_nonce ?? '' }}">
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.btn-delete-confirm').forEach(btn => {
            btn.addEventListener('click', function(e) {
                const message = this.getAttribute('data-message') || 'Apakah Anda yakin?';
                if (!confirm(message)) {
                    e.preventDefault();
                }
            });
        });
    });
</script>
@endsection