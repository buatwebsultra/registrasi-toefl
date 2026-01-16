@extends('layouts.app')

@section('title', 'Kelola Fakultas')

@section('content')
<div class="row">
    <div class="col-md-12">
        <h1>Kelola Fakultas</h1>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary mb-3">Kembali ke Dasbor</a>
        <a href="{{ route('admin.faculties.create') }}" class="btn btn-primary mb-3">Tambah Fakultas Baru</a>
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
                                <th>Program Studi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($faculties as $faculty)
                            <tr>
                                <td>{{ $faculty->id }}</td>
                                <td>{{ $faculty->name }}</td>
                                <td>
                                    @if($faculty->studyPrograms->count() > 0)
                                        <ul class="list-unstyled mb-0">
                                            @foreach($faculty->studyPrograms as $program)
                                                <li><small>{{ $program->name }} ({{ $program->level }})</small></li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <small class="text-muted">Tidak ada program studi</small>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.faculties.edit', $faculty->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                    <form action="{{ route('admin.faculties.delete', $faculty->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus fakultas ini?')">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center">Tidak ada fakultas ditemukan</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center">
                    {{ $faculties->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection