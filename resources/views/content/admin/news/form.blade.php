@extends('layouts/contentNavbarLayout')

@section('title', isset($news) ? 'Edit Berita' : 'Tambah Berita')

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
          <span class="avatar-initial rounded bg-label-primary">
            <i class="icon-base ti tabler-news"></i>
          </span>
        </div>
        <h5 class="card-title mb-0">{{ isset($news) ? 'Edit Berita' : 'Tambah Berita Baru' }}</h5>
      </div>
      <a href="{{ route('admin.news.index') }}" class="btn btn-label-secondary">
        <i class="icon-base ti tabler-arrow-left me-1"></i> Kembali
      </a>
    </div>
    <div class="card-body">
      <form action="{{ isset($news) ? route('admin.news.update', $news->id) : route('admin.news.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @isset($news)
          @method('PUT')
        @endisset

        <div class="mb-3">
          <label for="title" class="form-label">Judul Berita <span class="text-danger">*</span></label>
          <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', isset($news) ? $news->title : '') }}" required>
          @error('title')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="mb-3">
          <label for="category" class="form-label">Kategori</label>
          <select class="form-select @error('category') is-invalid @enderror" id="category" name="category">
            <option value="">Pilih Kategori</option>
            <option value="Akademik" {{ old('category', isset($news) ? $news->category : '') == 'Akademik' ? 'selected' : '' }}>Akademik</option>
            <option value="Non-Akademik" {{ old('category', isset($news) ? $news->category : '') == 'Non-Akademik' ? 'selected' : '' }}>Non-Akademik</option>
            <option value="Pengumuman" {{ old('category', isset($news) ? $news->category : '') == 'Pengumuman' ? 'selected' : '' }}>Pengumuman</option>
            <option value="Kegiatan" {{ old('category', isset($news) ? $news->category : '') == 'Kegiatan' ? 'selected' : '' }}>Kegiatan</option>
            <option value="Prestasi" {{ old('category', isset($news) ? $news->category : '') == 'Prestasi' ? 'selected' : '' }}>Prestasi</option>
          </select>
          @error('category')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="mb-3">
          <label for="content" class="form-label">Konten <span class="text-danger">*</span></label>
          <div id="quill-editor" style="min-height: 300px;">{!! old('content', isset($news) ? $news->content : '') !!}</div>
          <textarea class="d-none" id="content" name="content">{{ old('content', isset($news) ? $news->content : '') }}</textarea>
          @error('content')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="mb-3">
          <label for="image" class="form-label">Gambar</label>
          <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" accept="image/jpeg,image/png,image/jpg,image/gif,image/webp">
          @error('image')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
          @isset($news)
            @if($news->image)
              <div class="mt-2">
                <img src="{{ Storage::url($news->image) }}" alt="{{ $news->title }}" class="img-thumbnail" style="max-height: 150px;">
                <small class="text-muted d-block">Kosongkan jika tidak ingin mengganti gambar.</small>
              </div>
            @endif
          @endisset
        </div>

        <div class="row mb-3">
          <div class="col-md-6">
            <label for="author" class="form-label">Penulis</label>
            <input type="text" class="form-control @error('author') is-invalid @enderror" id="author" name="author" value="{{ old('author', isset($news) ? $news->author : auth()->user()->name) }}">
            @error('author')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          <div class="col-md-6">
            <label for="published_at" class="form-label">Tanggal Publikasi</label>
            <input type="date" class="form-control @error('published_at') is-invalid @enderror" id="published_at" name="published_at" value="{{ old('published_at', isset($news) && $news->published_at ? $news->published_at->format('Y-m-d') : '') }}">
            @error('published_at')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          <div class="col-md-6 d-flex align-items-center pt-3">
            <div class="form-check form-switch">
              <input type="hidden" name="is_published" value="0">
              <input class="form-check-input" type="checkbox" id="is_published" name="is_published" value="1" {{ old('is_published', isset($news) ? $news->is_published : false) ? 'checked' : '' }}>
              <label class="form-check-label" for="is_published">Publikasikan</label>
            </div>
          </div>
        </div>

        <div class="card-footer border-top pt-4 px-0">
          <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">
              <i class="icon-base ti tabler-device-floppy me-1"></i>
              {{ isset($news) ? 'Perbarui' : 'Simpan' }}
            </button>
            <a href="{{ route('admin.news.index') }}" class="btn btn-label-secondary">Batal</a>
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
      placeholder: 'Tulis konten berita di sini...',
      modules: {
        toolbar: [
          [{ 'header': [1, 2, 3, false] }],
          ['bold', 'italic', 'underline', 'strike'],
          [{ 'list': 'ordered'}, { 'list': 'bullet' }],
          ['blockquote', 'code-block'],
          [{ 'align': [] }],
          ['link', 'image'],
          ['clean']
        ]
      }
    });

    var form = document.querySelector('form');
    var contentInput = document.getElementById('content');

    form.addEventListener('submit', function() {
      contentInput.value = quill.root.innerHTML;
    });
  });
</script>
@endsection
