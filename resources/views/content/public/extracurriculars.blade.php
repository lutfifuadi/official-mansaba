@php use Illuminate\Support\Str; @endphp
@extends('layouts/layoutFront')
@section('title', 'Ekstrakurikuler')
@php $breadcrumbs = [['name' => 'Beranda', 'url' => url('/')], ['name' => 'Ekstrakurikuler']]; @endphp

@section('content')

{{-- Page Header --}}
<div class="mansaba-page-header">
  <div class="container text-center position-relative" style="z-index:1;">
    <nav aria-label="breadcrumb" class="breadcrumb-islami mb-2">
      <ol class="breadcrumb justify-content-center">
        <li class="breadcrumb-item"><a href="{{ route('home') }}" style="color:rgba(255,255,255,0.6);text-decoration:none;">Beranda</a></li>
        <li class="breadcrumb-item active">Ekstrakurikuler</li>
      </ol>
    </nav>
    <h1 class="mb-2">⚽ Kegiatan Ekstrakurikuler</h1>
    <p class="header-subtitle">Sarana pengembangan bakat, minat, dan karakter siswa</p>
  </div>
</div>

<section class="section-bg-cream py-6">
  <div class="container-xxl">

  {{-- Intro Card --}}
  <div class="mansaba-card mb-5 p-4">
    <div class="row align-items-center g-3">
      <div class="col-md-2 text-center">
        <div style="width:70px;height:70px;background:linear-gradient(135deg,var(--mansaba-green),var(--mansaba-green-mid));border-radius:5px;display:inline-flex;align-items:center;justify-content:center;">
          <i class="ti tabler-star" style="font-size:2rem;color:#fff;"></i>
        </div>
      </div>
      <div class="col-md-10">
        <h5 class="fw-bold mb-1" style="color:var(--mansaba-dark);">Pengembangan Diri Siswa</h5>
        <p class="text-muted mb-0" style="line-height:1.7;">MAN 1 Kota Bandung menyediakan berbagai kegiatan ekstrakurikuler untuk mengembangkan potensi siswa di bidang akademik, seni, olahraga, dan kerohanian Islam.</p>
      </div>
    </div>
  </div>

  {{-- Grid Ekstrakurikuler --}}
  <div class="row g-4">
    @forelse ($extracurriculars ?? [] as $item)
      @php
        $colors = ['var(--mansaba-green)', 'var(--mansaba-gold)', 'var(--mansaba-maroon)', '#1A66B3', '#7B3FA0', '#1A8C68'];
        $color = $colors[$loop->index % count($colors)];
      @endphp
      <div class="col-lg-4 col-md-6 mansaba-fade-up">
        <div class="mansaba-card h-100">
          <div class="card-body p-4">
            <div class="d-flex align-items-start gap-3 mb-3">
              <div style="width:52px;height:52px;background:{{ $color }}1A;border:2px solid {{ $color }}33;border-radius:5px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <i class="ti tabler-{{ $item->icon ?? 'users' }}" style="font-size:1.5rem;color:{{ $color }};"></i>
              </div>
              <div class="flex-grow-1">
                <h5 class="fw-bold mb-1" style="color:var(--mansaba-dark);font-size:1rem;">{{ $item->name ?? 'Nama Ekskul' }}</h5>
                @if ($item->schedule ?? false)
                  <div class="d-flex align-items-center gap-1" style="color:#6c757d;font-size:0.8rem;">
                    <i class="ti tabler-clock" style="font-size:0.9rem;"></i>
                    {{ $item->schedule }}
                  </div>
                @endif
              </div>
            </div>
            <p class="text-muted mb-4" style="font-size:0.87rem;line-height:1.6;">
              {{ Str::limit(strip_tags($item->description ?? 'Kegiatan pengembangan diri dan bakat siswa.'), 120) }}
            </p>
            <div class="d-flex align-items-center justify-content-between">
              @if ($item->member_count ?? false)
                <small class="text-muted">
                  <i class="ti tabler-users me-1"></i>{{ $item->member_count }} anggota
                </small>
              @else
                <span></span>
              @endif
              <a href="{{ route('public.extracurricular-detail', $item->slug ?? $item->id) }}"
                 class="btn btn-sm px-3"
                 style="background:{{ $color }}15;color:{{ $color }};border:1.5px solid {{ $color }}33;border-radius:5px;font-weight:600;font-size:0.82rem;text-decoration:none;transition:all 0.2s;"
                 onmouseover="this.style.background='{{ $color }}';this.style.color='#fff';"
                 onmouseout="this.style.background='{{ $color }}15';this.style.color='{{ $color }}';">
                <i class="ti tabler-eye me-1"></i>Selengkapnya
              </a>
            </div>
          </div>
        </div>
      </div>
    @empty
      <div class="col-12">
        <div class="mansaba-card text-center py-5">
          <i class="ti tabler-ball-football" style="font-size:3.5rem;color:#ccc;"></i>
          <h5 class="mt-3 fw-bold" style="color:var(--mansaba-dark);">Belum Ada Ekstrakurikuler</h5>
          <p class="text-muted">Data ekstrakurikuler belum tersedia.</p>
        </div>
      </div>
    @endforelse
  </div>

  @if (isset($extracurriculars) && method_exists($extracurriculars, 'links'))
    <div class="mt-5 d-flex justify-content-center">{{ $extracurriculars->links() }}</div>
  @endif

  <div class="mb-5"></div>
  </div>
</section>

@endsection
