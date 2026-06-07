@php use Illuminate\Support\Str; @endphp
@extends('layouts/layoutFront')
@section('title', 'Galeri')
@php $breadcrumbs = [['name' => 'Beranda', 'url' => url('/')], ['name' => 'Galeri']]; @endphp

@section('content')

{{-- Page Header --}}
<div class="mansaba-page-header">
  <div class="container text-center position-relative" style="z-index:1;">
    <nav aria-label="breadcrumb" class="breadcrumb-islami mb-2">
      <ol class="breadcrumb justify-content-center">
        <li class="breadcrumb-item"><a href="{{ route('home') }}" style="color:rgba(255,255,255,0.6);text-decoration:none;">Beranda</a></li>
        <li class="breadcrumb-item active">Galeri</li>
      </ol>
    </nav>
    <h1 class="mb-2">📸 Galeri Kegiatan</h1>
    <p class="header-subtitle">Dokumentasi kegiatan dan momen berharga di MAN 1 Kota Bandung</p>
  </div>
</div>

<section class="section-bg-cream py-6">
  <div class="container-xxl">

  {{-- Filter Kategori --}}
  @isset($categories)
  <div class="mansaba-card p-3 mb-4">
    <ul class="nav gap-2 flex-wrap" id="galleryTab" role="tablist">
      <li class="nav-item" role="presentation">
        <button class="btn btn-sm active" id="all-tab" data-bs-toggle="pill" data-bs-target="#all"
                type="button" role="tab"
                style="background:var(--mansaba-green);color:#fff;border-radius:5px;font-weight:600;">
          Semua
        </button>
      </li>
      @foreach ($categories as $cat)
      <li class="nav-item" role="presentation">
        <button class="btn btn-sm btn-outline-secondary" id="{{ Str::slug($cat) }}-tab"
                data-bs-toggle="pill" data-bs-target="#{{ Str::slug($cat) }}"
                type="button" role="tab"
                style="border-radius:5px;">
          {{ $cat }}
        </button>
      </li>
      @endforeach
    </ul>
  </div>
  @endisset

  {{-- Gallery Grid --}}
  <div class="tab-content" id="galleryTabContent">
    <div class="tab-pane fade show active" id="all" role="tabpanel">
      <div class="row g-3">
        @forelse ($galleries ?? [] as $item)
          <div class="col-md-4 col-sm-6 mansaba-fade-up">
            <div class="mansaba-gallery-item" style="height:240px;">
              @php $firstImg = $item->images->first(); @endphp
              <a href="{{ $firstImg ? \App\Helpers\StorageHelper::url($firstImg->image) : '#' }}"
                 data-fancybox="gallery-{{ $item->id }}"
                 data-caption="{{ $item->title ?? '' }}">
                <img src="{{ $firstImg ? \App\Helpers\StorageHelper::url($firstImg->image) : asset('assets/img/placeholder.jpg') }}"
                     alt="{{ $item->title ?? 'Foto' }}"
                     loading="lazy"
                     style="width:100%;height:240px;object-fit:cover;display:block;">
              </a>
              @if($item->images->count() > 1)
                @foreach($item->images->skip(1) as $gi)
                  <a href="{{ \App\Helpers\StorageHelper::url($gi->image) }}"
                     data-fancybox="gallery-{{ $item->id }}"
                     data-caption="{{ $item->title ?? '' }}"
                     class="d-none">
                    <img src="{{ \App\Helpers\StorageHelper::url($gi->image) }}" alt="{{ $item->title ?? '' }}" loading="lazy" style="display:none;">
                  </a>
                @endforeach
              @endif
              @if ($item->title)
              <div style="position:absolute;bottom:0;left:0;right:0;background:linear-gradient(transparent,rgba(27,94,66,0.85));padding:1rem;z-index:2;">
                <p class="mb-0 text-white" style="font-size:0.82rem;font-weight:500;">{{ $item->title }}</p>
              </div>
              @endif
            </div>
          </div>
        @empty
          <div class="col-12">
            <div class="mansaba-card text-center py-5">
              <i class="ti tabler-photo" style="font-size:3.5rem;color:#ccc;"></i>
              <h5 class="mt-3 fw-bold" style="color:var(--mansaba-dark);">Belum Ada Galeri</h5>
              <p class="text-muted">Belum ada foto yang tersedia saat ini.</p>
            </div>
          </div>
        @endforelse
      </div>
    </div>

    @isset($categories)
      @foreach ($categories as $cat)
      <div class="tab-pane fade" id="{{ Str::slug($cat) }}" role="tabpanel">
        <div class="row g-3">
          @php $catGalleries = $galleries->filter(fn($g) => ($g->category ?? '') === $cat); @endphp
          @forelse ($catGalleries as $item)
            @php $firstImg = $item->images->first(); @endphp
            <div class="col-md-4 col-sm-6">
              <div class="mansaba-gallery-item" style="height:240px;">
                <a href="{{ $firstImg ? \App\Helpers\StorageHelper::url($firstImg->image) : '#' }}"
                   data-fancybox="gallery-{{ Str::slug($cat) }}"
                   data-caption="{{ $item->title ?? '' }}">
                  <img src="{{ $firstImg ? \App\Helpers\StorageHelper::url($firstImg->image) : asset('assets/img/placeholder.jpg') }}"
                       alt="{{ $item->title ?? 'Foto' }}"
                       loading="lazy"
                       style="width:100%;height:240px;object-fit:cover;display:block;">
                </a>
              </div>
            </div>
          @empty
            <div class="col-12 text-center py-4">
              <p class="text-muted">Tidak ada foto di kategori ini.</p>
            </div>
          @endforelse
        </div>
      </div>
      @endforeach
    @endisset
  </div>

  @if (isset($galleries) && method_exists($galleries, 'links'))
    <div class="mt-5 d-flex justify-content-center">{{ $galleries->links() }}</div>
  @endif

  <div class="mb-5"></div>
  </div>
</section>

@endsection
