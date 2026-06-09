@extends('layouts/contentNavbarLayout')

@section('title', isset($service) ? 'Edit Layanan' : 'Tambah Layanan')

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
          <span class="avatar-initial rounded bg-label-danger">
            <i class="icon-base ti tabler-world"></i>
          </span>
        </div>
        <h5 class="card-title mb-0">{{ isset($service) ? 'Edit Layanan' : 'Tambah Layanan Baru' }}</h5>
      </div>
      <a href="{{ route('admin.services.index') }}" class="btn btn-label-secondary">
        <i class="icon-base ti tabler-arrow-left me-1"></i> Kembali
      </a>
    </div>
    <div class="card-body">
      <form action="{{ isset($service) ? route('admin.services.update', $service->id) : route('admin.services.store') }}" method="POST">
        @csrf
        @isset($service)
          @method('PUT')
        @endisset

        <div class="mb-3">
          <label for="name" class="form-label">Nama Layanan <span class="text-danger">*</span></label>
          <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', isset($service) ? $service->name : '') }}" required>
          @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="row mb-3">
          <div class="col-md-6">
            <label for="icon" class="form-label">Ikon (Tabler)</label>
            <input type="text" class="form-control @error('icon') is-invalid @enderror" id="icon" name="icon" value="{{ old('icon', isset($service) ? $service->icon : '') }}" placeholder="contoh: building-arch">
            <small class="text-muted">Nama ikon dari <a href="https://tabler-icons.io" target="_blank">Tabler Icons</a>. Contoh: <code>building-arch</code>, <code>file-text</code>, <code>user-check</code></small>
            @error('icon')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          <div class="col-md-3">
            <label for="sort_order" class="form-label">Urutan</label>
            <input type="number" class="form-control @error('sort_order') is-invalid @enderror" id="sort_order" name="sort_order" value="{{ old('sort_order', isset($service) ? $service->sort_order : 0) }}" min="0">
            @error('sort_order')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          <div class="col-md-3 d-flex align-items-center pt-4">
            <div class="form-check form-switch mb-0">
              <input type="hidden" name="is_active" value="0">
              <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', isset($service) ? $service->is_active : true) ? 'checked' : '' }} style="width:3rem;height:1.5rem;cursor:pointer;">
              <label class="form-check-label ms-1" for="is_active">Aktif</label>
            </div>
          </div>
        </div>

        <div class="mb-3">
          <label for="url" class="form-label">URL Layanan</label>
          <input type="url" class="form-control @error('url') is-invalid @enderror" id="url" name="url" value="{{ old('url', isset($service) ? $service->url : '') }}" placeholder="https://...">
          <small class="text-muted">Biarkan kosong atau isi <code>#</code> jika layanan belum tersedia.</small>
          @error('url')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="card-footer border-top pt-4 px-0">
          <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">
              <i class="icon-base ti tabler-device-floppy me-1"></i>
              {{ isset($service) ? 'Perbarui' : 'Simpan' }}
            </button>
            <a href="{{ route('admin.services.index') }}" class="btn btn-label-secondary">Batal</a>
          </div>
        </div>
      </form>
    </div>
  </div>
@endsection
