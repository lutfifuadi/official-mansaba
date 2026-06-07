@extends('layouts/contentNavbarLayout')

@section('title', isset($gallery) ? 'Edit Galeri' : 'Tambah Galeri')

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
          <span class="avatar-initial rounded bg-label-success">
            <i class="icon-base ti tabler-photo"></i>
          </span>
        </div>
        <h5 class="card-title mb-0">{{ isset($gallery) ? 'Edit Galeri' : 'Tambah Galeri Baru' }}</h5>
      </div>
      <a href="{{ route('admin.galleries.index') }}" class="btn btn-label-secondary">
        <i class="icon-base ti tabler-arrow-left me-1"></i> Kembali
      </a>
    </div>
    <div class="card-body">
      <form action="{{ isset($gallery) ? route('admin.galleries.update', $gallery->id) : route('admin.galleries.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @isset($gallery)
          @method('PUT')
        @endisset

        <div class="mb-3">
          <label for="title" class="form-label">Judul <span class="text-danger">*</span></label>
          <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', isset($gallery) ? $gallery->title : '') }}" required>
          @error('title')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="mb-3">
          <label for="category" class="form-label">Kategori</label>
          <select class="form-select @error('category') is-invalid @enderror" id="category" name="category">
            <option value="">Pilih Kategori</option>
            <option value="Kegiatan" {{ old('category', isset($gallery) ? $gallery->category : '') == 'Kegiatan' ? 'selected' : '' }}>Kegiatan</option>
            <option value="Akademik" {{ old('category', isset($gallery) ? $gallery->category : '') == 'Akademik' ? 'selected' : '' }}>Akademik</option>
            <option value="Non-Akademik" {{ old('category', isset($gallery) ? $gallery->category : '') == 'Non-Akademik' ? 'selected' : '' }}>Non-Akademik</option>
            <option value="Fasilitas" {{ old('category', isset($gallery) ? $gallery->category : '') == 'Fasilitas' ? 'selected' : '' }}>Fasilitas</option>
            <option value="Lainnya" {{ old('category', isset($gallery) ? $gallery->category : '') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
          </select>
          @error('category')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="mb-3">
          <label class="form-label">Gambar <span class="text-danger">*</span></label>
          <input type="file" class="form-control @error('images.*') is-invalid @enderror" id="images" name="images[]" accept="image/jpeg,image/png,image/jpg,image/gif,image/webp" multiple {{ isset($gallery) ? '' : 'required' }}>
          @error('images.*')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
          <small class="text-muted d-block mt-1">Bisa pilih lebih dari 1 gambar (CTRL+klik atau SHIFT+klik)</small>

          @isset($gallery)
            @if($gallery->images->count() > 0)
              <div class="row g-2 mt-2">
                @foreach($gallery->images as $gi)
                  <div class="col-4 col-md-3 col-lg-2">
                    <div class="position-relative border rounded" style="padding:4px;">
                      <img src="{{ Storage::url($gi->image) }}" alt="{{ $gallery->title }}" class="img-fluid rounded" style="height:100px;width:100%;object-fit:cover;">
                      <form action="{{ route('admin.galleries.image.delete', $gi->id) }}" method="POST" class="position-absolute top-0 end-0 m-1" onsubmit="return confirm('Hapus gambar ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-icon rounded-pill bg-white text-danger" style="width:24px;height:24px;padding:0;font-size:14px;line-height:1;box-shadow:0 1px 3px rgba(0,0,0,0.2);">&times;</button>
                      </form>
                    </div>
                  </div>
                @endforeach
              </div>
              <small class="text-muted d-block mt-1">Pilih gambar baru untuk menambah. Hapus per gambar jika perlu.</small>
            @endif
          @endisset
        </div>

        <div class="mb-3">
          <label for="description" class="form-label">Deskripsi</label>
          <div id="quill-editor" style="min-height: 200px;">{!! old('description', isset($gallery) ? $gallery->description : '') !!}</div>
          <textarea class="d-none" id="description" name="description">{{ old('description', isset($gallery) ? $gallery->description : '') }}</textarea>
          @error('description')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="card-footer border-top pt-4 px-0">
          <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">
              <i class="icon-base ti tabler-device-floppy me-1"></i>
              {{ isset($gallery) ? 'Perbarui' : 'Simpan' }}
            </button>
            <a href="{{ route('admin.galleries.index') }}" class="btn btn-label-secondary">Batal</a>
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
    var descInput = document.getElementById('description');

    form.addEventListener('submit', function() {
      descInput.value = quill.root.innerHTML;
    });
  });
</script>
@endsection
