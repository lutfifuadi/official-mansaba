@extends('layouts/contentNavbarLayout')

@section('title', isset($user) ? 'Edit Pengguna' : 'Tambah Pengguna')

@section('content')
  @if(session('error'))
    <div class="alert alert-danger alert-dismissible" role="alert">
      {{ session('error') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <div class="d-flex align-items-center">
        <div class="avatar avatar-sm me-3">
          <span class="avatar-initial rounded bg-label-secondary">
            <i class="icon-base ti tabler-users"></i>
          </span>
        </div>
        <h5 class="card-title mb-0">{{ isset($user) ? 'Edit Pengguna' : 'Tambah Pengguna Baru' }}</h5>
      </div>
      <a href="{{ route('admin.users.index') }}" class="btn btn-label-secondary">
        <i class="icon-base ti tabler-arrow-left me-1"></i> Kembali
      </a>
    </div>
    <div class="card-body">
      <form action="{{ isset($user) ? route('admin.users.update', $user->id) : route('admin.users.store') }}" method="POST">
        @csrf
        @isset($user)
          @method('PUT')
        @endisset

        <div class="mb-3">
          <label for="name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
          <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', isset($user) ? $user->name : '') }}" required>
          @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="mb-3">
          <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
          <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', isset($user) ? $user->email : '') }}" required>
          @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="mb-3">
          <label for="password" class="form-label">
            Password {{ isset($user) ? '(Kosongkan jika tidak ingin mengubah)' : '' }} <span class="text-danger">{{ isset($user) ? '' : '*' }}</span>
          </label>
          <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" {{ isset($user) ? '' : 'required' }}>
          @error('password')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="mb-3">
          <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
          <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" id="password_confirmation" name="password_confirmation" {{ isset($user) ? '' : 'required' }}>
        </div>

        <div class="mb-3">
          <label for="role" class="form-label">Role <span class="text-danger">*</span></label>
          <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required>
            <option value="">Pilih Role</option>
            <option value="super_admin" {{ old('role', isset($user) ? $user->role : '') == 'super_admin' ? 'selected' : '' }}>Super Admin</option>
            <option value="admin" {{ old('role', isset($user) ? $user->role : '') == 'admin' ? 'selected' : '' }}>Admin</option>
            <option value="operator" {{ old('role', isset($user) ? $user->role : '') == 'operator' ? 'selected' : '' }}>Operator</option>
            <option value="editor" {{ old('role', isset($user) ? $user->role : '') == 'editor' ? 'selected' : '' }}>Editor</option>
          </select>
          @error('role')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="card-footer border-top pt-4 px-0">
          <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">
              <i class="icon-base ti tabler-device-floppy me-1"></i>
              {{ isset($user) ? 'Perbarui' : 'Simpan' }}
            </button>
            <a href="{{ route('admin.users.index') }}" class="btn btn-label-secondary">Batal</a>
          </div>
        </div>
      </form>
    </div>
  </div>
@endsection
