@extends('layouts/contentNavbarLayout')

@section('title', 'Profil Saya')

@section('content')
  @if(session('success'))
    <div class="alert alert-success alert-dismissible" role="alert">
      {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  @if(session('error'))
    <div class="alert alert-danger alert-dismissible" role="alert">
      {{ session('error') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  @if($errors->any())
    <div class="alert alert-danger alert-dismissible" role="alert">
      <ul class="mb-0 ps-3">
        @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  <div class="row g-6">
    {{-- Profile Card --}}
    <div class="col-lg-4">
      <div class="card h-100">
        <div class="card-body text-center pt-5">
          <div class="avatar avatar-xl mb-3">
            <span class="avatar-initial rounded-circle bg-label-primary fs-1">
              {{ strtoupper(substr($user->name, 0, 1)) }}
            </span>
          </div>
          <h5 class="mb-1">{{ $user->name }}</h5>
          <p class="text-muted mb-3">{{ $user->email }}</p>
          <span class="badge bg-label-{{ $user->isSuperAdmin() ? 'danger' : ($user->isAdmin() ? 'primary' : ($user->isOperator() ? 'info' : 'secondary')) }}">
            {{ ucwords(str_replace('_', ' ', $user->role)) }}
          </span>
        </div>
      </div>
    </div>

    <div class="col-lg-8">
      {{-- Informasi Profil --}}
      <div class="card mb-4">
        <div class="card-header d-flex align-items-center">
          <div class="avatar avatar-sm me-3">
            <span class="avatar-initial rounded bg-label-primary">
              <i class="icon-base ti tabler-user"></i>
            </span>
          </div>
          <h5 class="card-title mb-0">Informasi Profil</h5>
        </div>
        <div class="card-body">
          <form action="{{ route('admin.profile.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
              <label for="name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
              <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required>
              @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-3">
              <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
              <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required>
              @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <button type="submit" class="btn btn-primary">
              <i class="icon-base ti tabler-device-floppy me-1"></i> Simpan Perubahan
            </button>
          </form>
        </div>
      </div>

      {{-- Ubah Kata Sandi --}}
      <div class="card">
        <div class="card-header d-flex align-items-center">
          <div class="avatar avatar-sm me-3">
            <span class="avatar-initial rounded bg-label-warning">
              <i class="icon-base ti tabler-key"></i>
            </span>
          </div>
          <h5 class="card-title mb-0">Ubah Kata Sandi</h5>
        </div>
        <div class="card-body">
          <form action="{{ route('admin.profile.password') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
              <label for="current_password" class="form-label">Kata Sandi Saat Ini <span class="text-danger">*</span></label>
              <input type="password" class="form-control @error('current_password') is-invalid @enderror" id="current_password" name="current_password" required>
              @error('current_password')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-3">
              <label for="password" class="form-label">Kata Sandi Baru <span class="text-danger">*</span></label>
              <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
              @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-3">
              <label for="password_confirmation" class="form-label">Konfirmasi Kata Sandi Baru <span class="text-danger">*</span></label>
              <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
            </div>

            <button type="submit" class="btn btn-primary">
              <i class="icon-base ti tabler-key me-1"></i> Ubah Kata Sandi
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
