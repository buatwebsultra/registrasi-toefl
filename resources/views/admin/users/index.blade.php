@extends('layouts.app')

@section('title', 'Manajemen User')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="display-6">Manajemen User</h1>
                    <p class="lead text-muted">Kelola akun Admin dan Operator sistem</p>
                </div>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-primary rounded-pill px-4">
                    <i class="fas fa-arrow-left me-2"></i>Kembali ke Dashboard
                </a>
            </div>
        </div>
    </div>

    @if(Auth::user()->isAdmin())
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-primary text-white py-3 rounded-top-4">
                    <h5 class="mb-0"><i class="fas fa-user-plus me-2"></i>Tambah User Baru</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.users.store') }}" method="POST">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="name" class="form-label fw-bold">Nama Lengkap</label>
                                <input type="text" class="form-control rounded-pill" id="name" name="name" required>
                            </div>
                            <div class="col-md-4">
                                <label for="email" class="form-label fw-bold">Email</label>
                                <input type="email" class="form-control rounded-pill" id="email" name="email" required>
                            </div>
                            <div class="col-md-4">
                                <label for="role" class="form-label fw-bold">Peran</label>
                                <select class="form-select rounded-pill" id="role" name="role" required>
                                    <option value="admin">Admin (Lengkap)</option>
                                    <option value="operator" selected>Staff Operator</option>
                                    <option value="prodi">Admin Program Studi</option>
                                </select>
                            </div>
                            <div class="col-md-4 d-none" id="studyProgramSection">
                                <label for="study_program_id" class="form-label fw-bold">Program Studi</label>
                                <select class="form-select rounded-pill" id="study_program_id" name="study_program_id">
                                    <option value="">Pilih Program Studi</option>
                                    @foreach($studyPrograms as $sp)
                                        <option value="{{ $sp->id }}">{{ $sp->name }} ({{ $sp->level }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="nip" class="form-label fw-bold">NIP</label>
                                <input type="text" class="form-control rounded-pill" id="nip" name="nip">
                            </div>
                            <div class="col-md-4">
                                <label for="jabatan" class="form-label fw-bold">Jabatan</label>
                                <input type="text" class="form-control rounded-pill" id="jabatan" name="jabatan" placeholder="Ketua / Sekretaris / Pokja">
                            </div>
                            <div class="col-md-2">
                                <label for="password" class="form-label fw-bold">Password</label>
                                <input type="password" class="form-control rounded-pill" id="password" name="password" required>
                            </div>
                            <div class="col-md-2">
                                <label for="password_confirmation" class="form-label fw-bold">Konfirmasi</label>
                                <input type="password" class="form-control rounded-pill" id="password_confirmation" name="password_confirmation" required>
                            </div>
                        </div>
                        <div class="mt-4 text-end">
                            <button type="submit" class="btn btn-primary rounded-pill px-5">
                                <i class="fas fa-save me-2"></i>Simpan User
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white py-3 rounded-top-4">
                    <h5 class="mb-0 fw-bold">Daftar Admin & Operator</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">Nama</th>
                                    <th>Email / NIP</th>
                                    <th>Jabatan</th>
                                    <th>Peran</th>
                                    <th class="text-end pe-4">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-primary bg-opacity-10 text-primary rounded-circle w-40px h-40px d-flex align-items-center justify-content-center me-3">
                                                <i class="fas fa-user"></i>
                                            </div>
                                            <span class="fw-bold">{{ $user->name }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div>{{ $user->email }}</div>
                                        <small class="text-muted">NIP: {{ $user->nip ?: '-' }}</small>
                                    </td>
                                    <td>{{ $user->jabatan ?: '-' }}</td>
                                    <td>
                                        <span class="badge rounded-pill {{ $user->role === 'admin' ? 'bg-primary' : ($user->role === 'prodi' ? 'bg-success' : 'bg-info') }} px-3 py-2">
                                            {{ ucfirst($user->role) }}
                                        </span>
                                        @if($user->role === 'prodi' && $user->studyProgram)
                                            <div class="small text-muted mt-1">{{ $user->studyProgram->name }}</div>
                                        @endif
                                    </td>
                                    <td class="text-end pe-4">
                                        <div class="btn-group">
                                            <button class="btn btn-sm btn-outline-warning rounded-pill px-3 me-2" data-bs-toggle="modal" data-bs-target="#editUser{{ $user->id }}">
                                                <i class="fas fa-edit me-1"></i> Edit
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#deleteUser{{ $user->id }}">
                                                <i class="fas fa-trash me-1"></i> Hapus
                                            </button>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Edit Modal -->
                                <div class="modal fade" id="editUser{{ $user->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content border-0 shadow rounded-4">
                                            <div class="modal-header bg-warning py-3 rounded-top-4">
                                                <h5 class="modal-title fw-bold">Edit User: {{ $user->name }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-body p-4">
                                                    <div class="row g-3">
                                                        <div class="col-md-6">
                                                            <label class="form-label fw-bold">Nama Lengkap</label>
                                                            <input type="text" class="form-control rounded-pill" name="name" value="{{ $user->name }}" required>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label fw-bold">Email</label>
                                                            <input type="email" class="form-control rounded-pill" name="email" value="{{ $user->email }}" required>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label fw-bold">Peran</label>
                                                            <select class="form-select rounded-pill" name="role" required>
                                                                <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin (Lengkap)</option>
                                                                <option value="operator" {{ $user->role === 'operator' ? 'selected' : '' }}>Staff Operator</option>
                                                                <option value="prodi" {{ $user->role === 'prodi' ? 'selected' : '' }}>Admin Program Studi</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-6 {{ $user->role === 'prodi' ? '' : 'd-none' }} editStudyProgramSection{{ $user->id }}">
                                                            <label class="form-label fw-bold">Program Studi</label>
                                                            <select class="form-select rounded-pill" name="study_program_id">
                                                                <option value="">Pilih Program Studi</option>
                                                                @foreach($studyPrograms as $sp)
                                                                    <option value="{{ $sp->id }}" {{ $user->study_program_id == $sp->id ? 'selected' : '' }}>{{ $sp->name }} ({{ $sp->level }})</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label fw-bold">NIP</label>
                                                            <input type="text" class="form-control rounded-pill" name="nip" value="{{ $user->nip }}">
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label fw-bold">Jabatan</label>
                                                            <input type="text" class="form-control rounded-pill" name="jabatan" value="{{ $user->jabatan }}">
                                                        </div>
                                                        <div class="col-md-12">
                                                            <hr>
                                                            <p class="text-muted small">Biarkan kosong jika tidak ingin mengubah password</p>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label fw-bold">Password Baru</label>
                                                            <input type="password" class="form-control rounded-pill" name="password">
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label fw-bold">Konfirmasi Password</label>
                                                            <input type="password" class="form-control rounded-pill" name="password_confirmation">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer p-3">
                                                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-warning rounded-pill px-4 fw-bold">Simpan Perubahan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <!-- Delete Modal -->
                                <div class="modal fade" id="deleteUser{{ $user->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content border-0 shadow rounded-4">
                                            <div class="modal-header bg-danger text-white py-3 rounded-top-4">
                                                <h5 class="modal-title">Hapus User</h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body p-4 text-center">
                                                <div class="text-danger mb-3">
                                                    <i class="fas fa-exclamation-circle fa-4x"></i>
                                                </div>
                                                <h5>Apakah Anda yakin?</h5>
                                                <p>Anda akan menghapus user <strong>{{ $user->name }}</strong>. Tindakan ini tidak dapat dibatalkan.</p>
                                            </div>
                                            <div class="modal-footer p-3">
                                                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                                                <form action="{{ route('admin.users.delete', $user->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger rounded-pill px-4">Ya, Hapus</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const roleSelect = document.getElementById('role');
        const spSection = document.getElementById('studyProgramSection');

        function toggleSPSection() {
            if (roleSelect && spSection) {
                if (roleSelect.value === 'prodi') {
                    spSection.classList.remove('d-none');
                } else {
                    spSection.classList.add('d-none');
                }
            }
        }

        if (roleSelect) {
            roleSelect.addEventListener('change', toggleSPSection);
            toggleSPSection(); // Run on load
        }

        // For Edit Modals
        @foreach($users as $user)
        (function() {
            const editRoleSelect = document.querySelector('#editUser{{ $user->id }} select[name="role"]');
            const editSpSection = document.querySelector('.editStudyProgramSection{{ $user->id }}');
            
            function toggleEditSPSection() {
                if (editRoleSelect && editSpSection) {
                    if (editRoleSelect.value === 'prodi') {
                        editSpSection.classList.remove('d-none');
                    } else {
                        editSpSection.classList.add('d-none');
                    }
                }
            }

            if (editRoleSelect) {
                editRoleSelect.addEventListener('change', toggleEditSPSection);
                toggleEditSPSection(); // Run on load for each modal
            }
        })();
        @endforeach
    });
</script>
@endsection
