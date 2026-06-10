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
          <label for="category" class="form-label">Kategori <span class="text-danger">*</span></label>
          <select class="form-select @error('category') is-invalid @enderror" id="category" name="category" required>
            <option value="">Pilih Kategori</option>
            @foreach(['Akademik', 'Administrasi', 'Kesiswaan', 'Kepegawaian', 'Teknis/IT', 'Lainnya'] as $cat)
              <option value="{{ $cat }}" {{ old('category', isset($service) ? $service->category : '') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
            @endforeach
          </select>
          @error('category')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="row mb-3">
          <div class="col-md-6">
            <label for="icon_color" class="form-label">Warna Ikon</label>
            <div class="d-flex align-items-center gap-2">
              <input type="color" class="form-control form-control-color @error('icon_color') is-invalid @enderror" id="icon_color" name="icon_color" value="{{ old('icon_color', isset($service) ? $service->icon_color : '#1B5E42') }}" style="width:60px;height:38px;padding:3px;">
              <span class="text-muted small">{{ old('icon_color', isset($service) ? $service->icon_color : '#1B5E42') }}</span>
            </div>
            @error('icon_color')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          <div class="col-md-6">
            <label for="url" class="form-label">URL Layanan</label>
            <input type="url" class="form-control @error('url') is-invalid @enderror" id="url" name="url" value="{{ old('url', isset($service) ? $service->url : '') }}" placeholder="https://...">
            <small class="text-muted">Biarkan kosong atau isi <code>#</code> jika layanan belum tersedia.</small>
            @error('url')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>

        <hr class="my-4">
        <h6 class="fw-semibold mb-3" style="color:var(--mansaba-dark);"><i class="ti tabler-info-circle me-1"></i> Informasi Detail</h6>

        <div class="mb-3">
          <label for="description" class="form-label">Deskripsi</label>
          <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4" placeholder="Deskripsi lengkap tentang layanan ini...">{{ old('description', isset($service) ? $service->description : '') }}</textarea>
          @error('description')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="row mb-3">
          <div class="col-md-6">
            <label for="requirements" class="form-label">Persyaratan</label>
            <textarea class="form-control @error('requirements') is-invalid @enderror" id="requirements" name="requirements" rows="5" placeholder="Tulis persyaratan, satu per baris...">{{ old('requirements', isset($service) ? $service->requirements : '') }}</textarea>
            <small class="text-muted">Tulis setiap persyaratan dalam baris terpisah.</small>
            @error('requirements')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          <div class="col-md-6">
            <label for="procedures" class="form-label">Prosedur</label>
            <textarea class="form-control @error('procedures') is-invalid @enderror" id="procedures" name="procedures" rows="5" placeholder="Tulis prosedur langkah demi langkah, satu per baris...">{{ old('procedures', isset($service) ? $service->procedures : '') }}</textarea>
            <small class="text-muted">Tulis setiap langkah dalam baris terpisah.</small>
            @error('procedures')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>

        <div class="mb-3">
          <label for="contact_person" class="form-label">Kontak Penanggung Jawab</label>
          <input type="text" class="form-control @error('contact_person') is-invalid @enderror" id="contact_person" name="contact_person" value="{{ old('contact_person', isset($service) ? $service->contact_person : '') }}" placeholder="Nama + jabatan (opsional: nomor telepon/email)">
          <small class="text-muted">Contoh: Bpk. Ahmad Fauzi (Waka Kesiswaan) — 0812-3456-7890</small>
          @error('contact_person')
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
