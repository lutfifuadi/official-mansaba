@extends('layouts/contentNavbarLayout')

@section('title', isset($extracurricular) ? 'Edit Ekstrakurikuler' : 'Tambah Ekstrakurikuler')

@section('vendor-style')
@vite(['resources/assets/vendor/libs/quill/editor.scss'])
@endsection

@section('vendor-script')
@vite(['resources/assets/vendor/libs/quill/quill.js'])
@endsection

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
          <span class="avatar-initial rounded bg-label-info">
            <i class="icon-base ti tabler-users-group"></i>
          </span>
        </div>
        <h5 class="card-title mb-0">{{ isset($extracurricular) ? 'Edit Ekstrakurikuler' : 'Tambah Ekstrakurikuler Baru' }}</h5>
      </div>
      <a href="{{ route('admin.extracurriculars.index') }}" class="btn btn-label-secondary">
        <i class="icon-base ti tabler-arrow-left me-1"></i> Kembali
      </a>
    </div>
    <div class="card-body">
      <form action="{{ isset($extracurricular) ? route('admin.extracurriculars.update', $extracurricular->id) : route('admin.extracurriculars.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @isset($extracurricular)
          @method('PUT')
        @endisset

        <div class="mb-3">
          <label for="name" class="form-label">Nama Ekstrakurikuler <span class="text-danger">*</span></label>
          <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', isset($extracurricular) ? $extracurricular->name : '') }}" required>
          @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="mb-3">
          <label for="description" class="form-label">Deskripsi</label>
          <div id="quill-editor" style="min-height: 200px;">{!! old('description', isset($extracurricular) ? $extracurricular->description : '') !!}</div>
          <textarea class="d-none" id="description" name="description">{{ old('description', isset($extracurricular) ? $extracurricular->description : '') }}</textarea>
          @error('description')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="row mb-3">
          <div class="col-md-6">
            <label for="coach" class="form-label">Pembina</label>
            <input type="text" class="form-control @error('coach') is-invalid @enderror" id="coach" name="coach" value="{{ old('coach', isset($extracurricular) ? $extracurricular->coach : '') }}">
            @error('coach')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          <div class="col-md-6">
            <label for="category" class="form-label">Kategori</label>
            <input type="text" class="form-control @error('category') is-invalid @enderror" id="category" name="category" value="{{ old('category', isset($extracurricular) ? $extracurricular->category : '') }}">
            @error('category')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>

        <div class="mb-3">
          <label for="schedule" class="form-label">Jadwal</label>
          <input type="text" class="form-control @error('schedule') is-invalid @enderror" id="schedule" name="schedule" value="{{ old('schedule', isset($extracurricular) ? $extracurricular->schedule : '') }}" placeholder="Contoh: Senin & Rabu, 15:00 - 17:00">
          @error('schedule')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="mb-3">
          <label for="image" class="form-label">Gambar</label>
          <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" accept="image/jpeg,image/png,image/jpg,image/gif,image/webp">
          @error('image')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
          @isset($extracurricular)
            @if($extracurricular->image)
              <div class="mt-2">
                <img src="{{ \App\Helpers\StorageHelper::url($extracurricular->image) }}" alt="{{ $extracurricular->name }}" class="img-thumbnail" style="max-height: 150px;">
                <small class="text-muted d-block">Kosongkan jika tidak ingin mengganti gambar.</small>
              </div>
            @endif
          @endisset
        </div>

        <div class="card-footer border-top pt-4 px-0">
          <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">
              <i class="icon-base ti tabler-device-floppy me-1"></i>
              {{ isset($extracurricular) ? 'Perbarui' : 'Simpan' }}
            </button>
            <a href="{{ route('admin.extracurriculars.index') }}" class="btn btn-label-secondary">Batal</a>
          </div>
        </div>
      </form>
    </div>
  </div>
@endsection

@section('page-script')
<script>
  document.addEventListener('DOMContentLoaded', function() {
    var quill = new Quill('#quill-editor', {
      theme: 'snow',
      placeholder: 'Tulis deskripsi di sini...',
      modules: {
        toolbar: [
          [{ 'header': [2, 3, false] }],
          ['bold', 'italic', 'underline'],
          [{ 'list': 'ordered'}, { 'list': 'bullet' }],
          ['link'],
          ['clean']
        ]
      }
    });
    var form = document.querySelector('form');
    var input = document.getElementById('description');
    form.addEventListener('submit', function() { input.value = quill.root.innerHTML; });
  });
</script>
@endsection
