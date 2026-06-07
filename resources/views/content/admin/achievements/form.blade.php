@extends('layouts/contentNavbarLayout')

@section('title', isset($achievement) ? 'Edit Prestasi' : 'Tambah Prestasi')

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
          <span class="avatar-initial rounded bg-label-warning">
            <i class="icon-base ti tabler-trophy"></i>
          </span>
        </div>
        <h5 class="card-title mb-0">{{ isset($achievement) ? 'Edit Prestasi' : 'Tambah Prestasi Baru' }}</h5>
      </div>
      <a href="{{ route('admin.achievements.index') }}" class="btn btn-label-secondary">
        <i class="icon-base ti tabler-arrow-left me-1"></i> Kembali
      </a>
    </div>
    <div class="card-body">
      <form action="{{ isset($achievement) ? route('admin.achievements.update', $achievement->id) : route('admin.achievements.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @isset($achievement)
          @method('PUT')
        @endisset

        <div class="mb-3">
          <label for="title" class="form-label">Judul Prestasi <span class="text-danger">*</span></label>
          <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', isset($achievement) ? $achievement->title : '') }}" required>
          @error('title')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="mb-3">
          <label for="student_name" class="form-label">Nama Siswa <span class="text-danger">*</span></label>
          <input type="text" class="form-control @error('student_name') is-invalid @enderror" id="student_name" name="student_name" value="{{ old('student_name', isset($achievement) ? $achievement->student_name : '') }}" required>
          @error('student_name')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="row mb-3">
          <div class="col-md-6">
            <label for="category" class="form-label">Kategori <span class="text-danger">*</span></label>
            <select class="form-select @error('category') is-invalid @enderror" id="category" name="category" required>
              <option value="">Pilih Kategori</option>
              <option value="Akademik" {{ old('category', isset($achievement) ? $achievement->category : '') == 'Akademik' ? 'selected' : '' }}>Akademik</option>
              <option value="Non-Akademik" {{ old('category', isset($achievement) ? $achievement->category : '') == 'Non-Akademik' ? 'selected' : '' }}>Non-Akademik</option>
            </select>
            @error('category')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          <div class="col-md-6">
            <label for="level" class="form-label">Tingkat <span class="text-danger">*</span></label>
            <select class="form-select @error('level') is-invalid @enderror" id="level" name="level" required>
              <option value="">Pilih Tingkat</option>
              <option value="Internasional" {{ old('level', isset($achievement) ? $achievement->level : '') == 'Internasional' ? 'selected' : '' }}>Internasional</option>
              <option value="Nasional" {{ old('level', isset($achievement) ? $achievement->level : '') == 'Nasional' ? 'selected' : '' }}>Nasional</option>
              <option value="Provinsi" {{ old('level', isset($achievement) ? $achievement->level : '') == 'Provinsi' ? 'selected' : '' }}>Provinsi</option>
              <option value="Kota" {{ old('level', isset($achievement) ? $achievement->level : '') == 'Kota' ? 'selected' : '' }}>Kota</option>
            </select>
            @error('level')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>

        <div class="mb-3">
          <label for="achievement_date" class="form-label">Tanggal Prestasi</label>
          <input type="date" class="form-control @error('achievement_date') is-invalid @enderror" id="achievement_date" name="achievement_date" value="{{ old('achievement_date', isset($achievement) && $achievement->achievement_date ? $achievement->achievement_date->format('Y-m-d') : '') }}">
          @error('achievement_date')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="mb-3">
          <label for="description" class="form-label">Deskripsi</label>
          <div id="quill-editor" style="min-height: 200px;">{!! old('description', isset($achievement) ? $achievement->description : '') !!}</div>
          <textarea class="d-none" id="description" name="description">{{ old('description', isset($achievement) ? $achievement->description : '') }}</textarea>
          @error('description')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="mb-3">
          <label for="image" class="form-label">Gambar</label>
          <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" accept="image/jpeg,image/png,image/jpg,image/gif,image/webp">
          @error('image')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
          @isset($achievement)
            @if($achievement->image)
              <div class="mt-2">
                <img src="{{ \App\Helpers\StorageHelper::url($achievement->image) }}" alt="{{ $achievement->title }}" class="img-thumbnail" style="max-height: 150px;">
                <small class="text-muted d-block">Kosongkan jika tidak ingin mengganti gambar.</small>
              </div>
            @endif
          @endisset
        </div>

        <div class="card-footer border-top pt-4 px-0">
          <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">
              <i class="icon-base ti tabler-device-floppy me-1"></i>
              {{ isset($achievement) ? 'Perbarui' : 'Simpan' }}
            </button>
            <a href="{{ route('admin.achievements.index') }}" class="btn btn-label-secondary">Batal</a>
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
