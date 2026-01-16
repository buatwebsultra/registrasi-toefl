@extends('layouts.app')

@section('title', 'Edit Program Studi')

@section('content')
<div class="row">
    <div class="col-md-12">
        <h1>Edit Program Studi</h1>
        <a href="{{ route('admin.study-programs.index') }}" class="btn btn-secondary mb-3">Kembali ke Program Studi</a>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Informasi Program Studi</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.study-programs.update', $studyProgram->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Program Studi <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $studyProgram->name) }}" required>
                        @error('name')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="level" class="form-label">Jenjang <span class="text-danger">*</span></label>
                        <select class="form-select" id="level" name="level" required>
                            <option value="" disabled>Pilih Jenjang</option>
                            <option value="Diploma 3 (D3)" {{ old('level', $studyProgram->level) == 'Diploma 3 (D3)' ? 'selected' : '' }}>Diploma 3 (D3)</option>
                            <option value="Sarjana (S1)" {{ old('level', $studyProgram->level) == 'Sarjana (S1)' ? 'selected' : '' }}>Sarjana (S1)</option>
                            <option value="Magister (S2)" {{ old('level', $studyProgram->level) == 'Magister (S2)' ? 'selected' : '' }}>Magister (S2)</option>
                            <option value="Doktor (S3)" {{ old('level', $studyProgram->level) == 'Doktor (S3)' ? 'selected' : '' }}>Doktor (S3)</option>
                        </select>
                        @error('level')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="faculty_id" class="form-label">Fakultas <span class="text-danger">*</span></label>
                        <select class="form-select" id="faculty_id" name="faculty_id" required>
                            <option value="">Pilih Fakultas</option>
                            @foreach($faculties as $faculty)
                                <option value="{{ $faculty->id }}" {{ old('faculty_id', $studyProgram->faculty_id) == $faculty->id ? 'selected' : '' }}>
                                    {{ $faculty->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('faculty_id')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="passing_grade" class="form-label">Ambang Batas (Passing Grade) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="passing_grade" name="passing_grade" value="{{ old('passing_grade', $studyProgram->passing_grade) }}" min="0" max="677" required>
                        <small class="text-muted">Nilai minimal untuk dinyatakan Lulus (0-677). Default: 400.</small>
                        @error('passing_grade')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('admin.study-programs.index') }}" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary">Perbarui Program Studi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection