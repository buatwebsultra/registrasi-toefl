@extends('layouts.app')

@section('title', 'Edit Fakultas')

@section('content')
<div class="row">
    <div class="col-md-12">
        <h1>Edit Fakultas</h1>
        <a href="{{ route('admin.faculties.index') }}" class="btn btn-secondary mb-3">Kembali ke Fakultas</a>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Informasi Fakultas</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.faculties.update', $faculty->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Fakultas <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $faculty->name) }}" required>
                        @error('name')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('admin.faculties.index') }}" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary">Perbarui Fakultas</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection