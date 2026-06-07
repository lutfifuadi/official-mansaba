@extends('layouts/contentNavbarLayout')

@section('title', isset($announcement) ? 'Edit Pengumuman' : 'Tambah Pengumuman')

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
            <i class="icon-base ti tabler-speakerphone"></i>
          </span>
        </div>
        <h5 class="card-title mb-0">{{ isset($announcement) ? 'Edit Pengumuman' : 'Tambah Pengumuman Baru' }}</h5>
      </div>
      <a href="{{ route('admin.announcements.index') }}" class="btn btn-label-secondary">
        <i class="icon-base ti tabler-arrow-left me-1"></i> Kembali
      </a>
    </div>
    <div class="card-body">
      <form action="{{ isset($announcement) ? route('admin.announcements.update', $announcement->id) : route('admin.announcements.store') }}" method="POST">
        @csrf
        @isset($announcement)
          @method('PUT')
        @endisset

        <div class="mb-3">
          <label for="title" class="form-label">Judul Pengumuman <span class="text-danger">*</span></label>
          <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', isset($announcement) ? $announcement->title : '') }}" required>
          @error('title')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="mb-3">
          <label for="content" class="form-label">Konten <span class="text-danger">*</span></label>
          <div id="quill-editor" style="min-height: 250px;">{!! old('content', isset($announcement) ? $announcement->content : '') !!}</div>
          <textarea class="d-none" id="content" name="content">{{ old('content', isset($announcement) ? $announcement->content : '') }}</textarea>
          @error('content')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="row mb-3">
          <div class="col-md-6">
            <label for="published_at" class="form-label">Tanggal Publikasi</label>
            <input type="datetime-local" class="form-control @error('published_at') is-invalid @enderror" id="published_at" name="published_at" value="{{ old('published_at', isset($announcement) && $announcement->published_at ? $announcement->published_at->format('Y-m-d\TH:i') : '') }}">
            @error('published_at')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          <div class="col-md-6 d-flex align-items-center pt-3">
            <div class="form-check form-switch">
              <input type="hidden" name="is_active" value="0">
              <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', isset($announcement) ? $announcement->is_active : true) ? 'checked' : '' }}>
              <label class="form-check-label" for="is_active">Aktif</label>
            </div>
          </div>
        </div>

        <div class="card-footer border-top pt-4 px-0">
          <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">
              <i class="icon-base ti tabler-device-floppy me-1"></i>
              {{ isset($announcement) ? 'Perbarui' : 'Simpan' }}
            </button>
            <a href="{{ route('admin.announcements.index') }}" class="btn btn-label-secondary">Batal</a>
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
      placeholder: 'Tulis konten pengumuman di sini...',
      modules: {
        toolbar: [
          [{ 'header': [2, 3, false] }],
          ['bold', 'italic', 'underline', 'strike'],
          [{ 'list': 'ordered'}, { 'list': 'bullet' }],
          ['link'],
          ['clean']
        ]
      }
    });
    var form = document.querySelector('form');
    var input = document.getElementById('content');
    form.addEventListener('submit', function() { input.value = quill.root.innerHTML; });
  });
</script>
@endsection
