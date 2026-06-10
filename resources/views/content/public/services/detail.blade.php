@php use Illuminate\Support\Str; @endphp
@extends('layouts/layoutFront')
@section('title', $service->name . ' - Layanan')
@php $breadcrumbs = [['name' => 'Beranda', 'url' => url('/')], ['name' => 'Layanan', 'url' => route('public.services')], ['name' => $service->name]]; @endphp

@section('content')

{{-- Page Header --}}
<div class="mansaba-page-header">
  <div class="container text-center position-relative" style="z-index:1;">
    <nav aria-label="breadcrumb" class="breadcrumb-islami mb-2">
      <ol class="breadcrumb justify-content-center">
        <li class="breadcrumb-item"><a href="{{ route('home') }}" style="color:rgba(255,255,255,0.6);text-decoration:none;">Beranda</a></li>
        <li class="breadcrumb-item"><a href="{{ route('public.services') }}" style="color:rgba(255,255,255,0.6);text-decoration:none;">Layanan</a></li>
        <li class="breadcrumb-item active">{{ $service->name }}</li>
      </ol>
    </nav>
    <div class="d-flex align-items-center justify-content-center gap-3 flex-wrap">
      <div class="service-detail-icon" @if($service->icon_color) style="background:{{ $service->icon_color }}22;color:{{ $service->icon_color }};" @endif>
        <i class="ti tabler-{{ $service->icon }}" style="font-size:2rem;"></i>
      </div>
      <div class="text-start">
        <h1 class="mb-1" style="font-size:1.8rem;">{{ $service->name }}</h1>
        <div class="d-flex flex-wrap gap-2 justify-content-center justify-content-md-start">
          <span class="badge px-3 py-1" style="background:rgba(46,204,113,0.2);color:#2ecc71;font-size:0.75rem;border-radius:20px;">
            <i class="ti tabler-circle-filled" style="font-size:0.4rem;vertical-align:middle;margin-right:3px;"></i>Aktif
          </span>
          @if($service->category)
            <span class="badge px-3 py-1" style="background:rgba(201,151,43,0.15);color:#C9972B;font-size:0.75rem;border-radius:20px;">
              {{ $service->category }}
            </span>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>

<section class="section-bg-cream py-5">
  <div class="container-xxl" style="max-width:860px;">

    {{-- Back Link --}}
    <a href="{{ route('public.services') }}" class="d-inline-flex align-items-center gap-1 mb-4" style="color:var(--mansaba-green);text-decoration:none;font-weight:600;font-size:0.9rem;">
      <i class="ti tabler-arrow-left"></i> Kembali ke Semua Layanan
    </a>

    <div class="mansaba-card p-4 p-md-5">

      {{-- Description --}}
      @if($service->description)
        <div class="service-section">
          <h5 class="service-section-title">
            <i class="ti tabler-file-text me-2" style="color:var(--mansaba-green);"></i>Deskripsi
          </h5>
          <p class="service-section-body">{{ $service->description }}</p>
        </div>
      @endif

      {{-- Requirements --}}
      @if($service->requirements)
        <div class="service-section">
          <h5 class="service-section-title">
            <i class="ti tabler-checklist me-2" style="color:var(--mansaba-green);"></i>Persyaratan
          </h5>
          <div class="service-section-body">
            {!! Str::of($service->requirements)->explode("\n")->map(fn($line) => trim($line))->filter()->reduce(function($carry, $line) {
              $clean = ltrim($line, '-*•');
              return $carry . '<div class="d-flex align-items-start gap-2 mb-1"><i class="ti tabler-check mt-1" style="color:var(--mansaba-green);font-size:0.85rem;flex-shrink:0;"></i><span>' . e(trim($clean)) . '</span></div>';
            }, '') !!}
          </div>
        </div>
      @endif

      {{-- Procedures --}}
      @if($service->procedures)
        <div class="service-section">
          <h5 class="service-section-title">
            <i class="ti tabler-list-numbers me-2" style="color:var(--mansaba-green);"></i>Prosedur
          </h5>
          <ol class="service-procedure-list">
            @php $procedures = collect(explode("\n", $service->procedures))->map(fn($l) => trim($l))->filter()->values(); @endphp
            @foreach($procedures as $proc)
              @php $clean = ltrim($proc, '0123456789.)-•* '); @endphp
              <li>{{ trim($clean) }}</li>
            @endforeach
          </ol>
        </div>
      @endif

      {{-- Contact Person --}}
      @if($service->contact_person)
        <div class="service-section">
          <h5 class="service-section-title">
            <i class="ti tabler-phone me-2" style="color:var(--mansaba-green);"></i>Kontak Penanggung Jawab
          </h5>
          <div class="service-contact-card p-3" style="background:rgba(27,94,66,0.04);border-radius:8px;border-left:4px solid var(--mansaba-green);">
            <div class="d-flex align-items-center gap-3">
              <div style="width:44px;height:44px;border-radius:50%;background:var(--mansaba-green);color:#fff;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <i class="ti tabler-user" style="font-size:1.2rem;"></i>
              </div>
              <div>
                <p class="mb-0 fw-semibold" style="color:var(--mansaba-dark);">{{ $service->contact_person }}</p>
              </div>
            </div>
          </div>
        </div>
      @endif

      {{-- CTA Button --}}
      <div class="mt-4 pt-3 border-top">
        @if($service->url && $service->url !== '#')
          <a href="{{ $service->url }}" target="_blank" rel="noopener" class="btn btn-lg w-100 d-flex align-items-center justify-content-center gap-2" style="background:var(--mansaba-green);color:#fff;border-radius:8px;font-weight:700;padding:14px 24px;">
            <i class="ti tabler-external-link" style="font-size:1.1rem;"></i>
            Akses Layanan
          </a>
        @else
          <button class="btn btn-lg w-100 d-flex align-items-center justify-content-center gap-2" disabled style="border-radius:8px;font-weight:700;padding:14px 24px;background:#e0e0e0;border-color:#e0e0e0;color:#999;">
            <i class="ti tabler-clock"></i>
            Segera Hadir
          </button>
        @endif
      </div>

      {{-- Footer Back Link --}}
      <div class="mt-3 text-center">
        <a href="{{ route('public.services') }}" class="d-inline-flex align-items-center gap-1" style="color:var(--mansaba-green);text-decoration:none;font-weight:500;font-size:0.88rem;">
          <i class="ti tabler-arrow-left"></i> Kembali ke Semua Layanan
        </a>
      </div>

    </div>
  </div>
</section>

@endsection

@section('page-style')
<style>
.service-section {
  margin-bottom: 2rem;
  padding-bottom: 1.5rem;
  border-bottom: 1px solid rgba(0,0,0,0.05);
}
.service-section:last-of-type {
  border-bottom: none;
  margin-bottom: 1rem;
  padding-bottom: 0;
}
.service-section-title {
  font-weight: 700;
  color: var(--mansaba-dark);
  margin-bottom: 0.75rem;
  display: flex;
  align-items: center;
}
.service-section-body {
  font-size: 0.95rem;
  line-height: 1.8;
  color: var(--mansaba-text);
}
.service-detail-icon {
  width: 64px;
  height: 64px;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 50%;
  background: rgba(27,94,66,0.1);
  color: var(--mansaba-green);
  flex-shrink: 0;
}
.service-contact-card {
  transition: transform 0.2s ease;
}
.service-contact-card:hover {
  transform: translateX(4px);
}
.service-procedure-list {
  list-style: none;
  counter-reset: step;
  padding-left: 0;
  margin-bottom: 0;
}
.service-procedure-list li {
  counter-increment: step;
  display: flex;
  align-items: flex-start;
  gap: 0.5rem;
  margin-bottom: 0.5rem;
  font-size: 0.95rem;
  line-height: 1.8;
  color: var(--mansaba-text);
}
.service-procedure-list li::before {
  content: counter(step);
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 26px;
  height: 26px;
  border-radius: 50%;
  background: var(--mansaba-green);
  color: #fff;
  font-size: 0.75rem;
  font-weight: 700;
  flex-shrink: 0;
  margin-top: 4px;
}
</style>
@endsection
