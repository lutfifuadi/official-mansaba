@extends('layouts/layoutFront')

@section('title', $news->title ?? 'Detail Berita')

@php
  $breadcrumbs = [
    ['name' => 'Beranda', 'url' => url('/')],
    ['name' => 'Berita', 'url' => route('public.news')],
    ['name' => $news->title ?? 'Detail Berita'],
  ];
  $ldNewsArticle = [
    'title' => $news->title,
    'image' => $news->image ? Storage::url($news->image) : '',
    'published_at' => $news->published_at?->toIso8601String(),
    'updated_at' => $news->updated_at?->toIso8601String(),
    'author' => $news->author,
    'description' => strip_tags(Str::limit($news->content ?? '', 160)),
  ];
@endphp

@section('content')
{{-- Page Header --}}
<div class="mansaba-page-header">
  <div class="container text-center position-relative" style="z-index:1;">
    <nav aria-label="breadcrumb" class="breadcrumb-islami mb-2">
      <ol class="breadcrumb justify-content-center">
        <li class="breadcrumb-item"><a href="{{ route('home') }}" style="color:rgba(255,255,255,0.6);text-decoration:none;">Beranda</a></li>
        <li class="breadcrumb-item"><a href="{{ url('public/news') }}" style="color:rgba(255,255,255,0.6);text-decoration:none;">Berita</a></li>
        <li class="breadcrumb-item active">Detail</li>
      </ol>
    </nav>
    <h1 class="mb-2">📰 Detail Berita</h1>
    <p class="header-subtitle">{{ $news->title ?? 'Detail Berita' }}</p>
  </div>
</div>

<section class="section-bg-cream py-6">
  <div class="container-xxl">

    <div class="row justify-content-center">
      <div class="col-lg-10">
        <div class="mansaba-card">
          @if ($news->image ?? false)
            <img class="card-img-top" src="{{ Storage::url($news->image) }}" alt="{{ $news->title }}" loading="lazy" style="max-height: 500px; object-fit: cover;">
          @endif
          <div class="card-body p-4 p-md-5">
            <h2 class="card-title fw-bold mb-3" style="color:var(--mansaba-dark);">{{ $news->title ?? 'Judul Berita' }}</h2>

            <div class="d-flex flex-wrap gap-3 mb-4 text-muted small" style="font-weight:600;">
              <span><i class="ti tabler-calendar me-1"></i>{{ $news->created_at ? $news->created_at->format('d M Y') : '-' }}</span>
              @if ($news->author ?? false)
                <span><i class="ti tabler-user me-1"></i>{{ $news->author }}</span>
              @endif
              @if ($news->category ?? false)
                <span class="badge-mansaba-green badge px-2 py-1"><i class="ti tabler-tag me-1"></i>{{ $news->category }}</span>
              @endif
            </div>

            <div class="content-text" style="font-size:1.05rem;line-height:1.8;color:#444;">
              {!! $news->content ?? '<p class="text-muted">Konten tidak tersedia.</p>' !!}
            </div>

            <div class="mt-5 pt-4 border-top" style="border-color:rgba(27,94,66,0.1)!important;">
              <a href="{{ url('public/news') }}" class="btn px-4 py-2" style="background:var(--mansaba-green);color:#fff;border-radius:5px;font-weight:600;">
                <i class="ti tabler-arrow-left me-2"></i>Kembali ke Berita
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>
</section>
@endsection
