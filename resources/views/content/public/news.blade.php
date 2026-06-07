@php use Illuminate\Support\Str; @endphp
@extends('layouts/layoutFront')
@section('title', 'Berita')
@php $breadcrumbs = [['name' => 'Beranda', 'url' => url('/')], ['name' => 'Berita']]; @endphp

@section('content')

{{-- Page Header --}}
<div class="mansaba-page-header">
  <div class="container text-center position-relative" style="z-index:1;">
    <nav aria-label="breadcrumb" class="breadcrumb-islami mb-2">
      <ol class="breadcrumb justify-content-center">
        <li class="breadcrumb-item"><a href="{{ route('home') }}" style="color:rgba(255,255,255,0.6);text-decoration:none;">Beranda</a></li>
        <li class="breadcrumb-item active">Berita</li>
      </ol>
    </nav>
    <h1 class="mb-2">📰 Berita & Informasi</h1>
    <p class="header-subtitle">Ikuti perkembangan terkini dari MAN 1 Kota Bandung</p>
  </div>
</div>

<section class="section-bg-cream py-6">
  <div class="container-xxl">

  {{-- Filter/Search Bar --}}
  <div class="mansaba-card mb-5 p-3">
    <div class="row align-items-center g-3">
      <div class="col">
        <div class="position-relative">
          <i class="ti tabler-search" style="position:absolute;left:16px;top:50%;transform:translateY(-50%);color:#aaa;font-size:1.1rem;"></i>
          <input type="text" id="berita-search" class="form-control ps-5 py-2" placeholder="Cari berita..." style="border-radius:5px;border:1.5px solid #e0e0e0;padding-left:2.7rem !important;">
        </div>
      </div>
      <div class="col-auto">
        <span class="badge-mansaba-green badge px-3 py-2" style="font-size:0.8rem;">
          {{ $news->count() ?? 0 }} Berita
        </span>
      </div>
    </div>
  </div>

  {{-- Grid Berita --}}
  <div class="row g-4" id="berita-grid">
    @forelse ($news ?? [] as $item)
      <div class="col-md-4 col-sm-6 mansaba-fade-up">
        <div class="mansaba-card mansaba-card-news h-100">
          <div style="height:200px;overflow:hidden;position:relative;">
            <img src="{{ $item->image ? Storage::url($item->image) : asset('storage/default-news.jpg') }}"
                 alt="{{ $item->title ?? 'Berita' }}"
                 loading="lazy"
                 style="width:100%;height:100%;object-fit:cover;transition:transform 0.4s ease;"
                 onmouseover="this.style.transform='scale(1.05)'"
                 onmouseout="this.style.transform='scale(1)'">
            <div style="position:absolute;top:12px;left:12px;">
              <span class="news-category-badge">{{ $item->category ?? 'Berita' }}</span>
            </div>
          </div>
          <div class="card-body d-flex flex-column">
            <small class="text-muted d-flex align-items-center gap-1 mb-2">
              <i class="ti tabler-calendar" style="font-size:0.8rem;"></i>
              {{ $item->created_at ? $item->created_at->format('d M Y') : '-' }}
            </small>
            <h5 class="card-title">
              <a href="{{ route('public.news-detail', $item->slug ?? $item->id) }}" style="text-decoration:none;color:inherit;">
                {{ $item->title ?? 'Judul Berita' }}
              </a>
            </h5>
            <p class="card-text text-muted flex-grow-1 mb-3" style="font-size:0.88rem;line-height:1.6;">
              {{ Str::limit(strip_tags($item->excerpt ?? $item->content ?? ''), 120) }}
            </p>
            <a href="{{ route('public.news-detail', $item->slug ?? $item->id) }}" class="btn-read-more">
              Baca Selengkapnya <i class="ti tabler-arrow-right"></i>
            </a>
          </div>
        </div>
      </div>
    @empty
      <div class="col-12">
        <div class="mansaba-card text-center py-5">
          <i class="ti tabler-news" style="font-size:3.5rem;color:#ccc;"></i>
          <h5 class="mt-3 fw-bold" style="color:var(--mansaba-dark);">Belum Ada Berita</h5>
          <p class="text-muted">Belum ada berita yang tersedia saat ini.</p>
          <a href="{{ url('/') }}" class="btn btn-sm px-4" style="background:var(--mansaba-green);color:#fff;border-radius:5px;font-weight:600;">Kembali ke Beranda</a>
        </div>
      </div>
    @endforelse
  </div>

  {{-- Pagination --}}
  @if (isset($news) && method_exists($news, 'links'))
    <div class="mt-5 d-flex justify-content-center mansaba-pagination">
      {{ $news->links() }}
    </div>
  @endif

  <div class="mb-5"></div>

  </div>
</section>

@endsection

@section('page-style')
<style>
.mansaba-pagination .pagination .page-item .page-link {
  border-radius: 5px !important;
  margin: 0 2px;
  color: var(--mansaba-green);
  border-color: #e0e0e0;
}
.mansaba-pagination .pagination .page-item.active .page-link {
  background: var(--mansaba-green) !important;
  border-color: var(--mansaba-green) !important;
  color: #fff !important;
}
</style>
@endsection
