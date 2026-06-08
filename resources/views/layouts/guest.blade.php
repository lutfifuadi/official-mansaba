@props(['split' => false, 'title' => 'Masuk'])

@php
$customizerHidden = 'customizer-hide';
$pageConfigs = ['myLayout' => 'blank'];
@endphp

@extends('layouts/layoutMaster')

@section('title', $title . ' — ' . config('app.name', 'MAN 1 Kota Bandung'))

@section('vendor-style')
@vite([
  'resources/assets/vendor/libs/@form-validation/form-validation.scss',
  'resources/assets/vendor/fonts/trajan-pro/trajan-pro.css',
  'resources/assets/vendor/fonts/amiri/amiri.css',
])
@endsection

@section('vendor-script')
@vite(['resources/assets/vendor/libs/@form-validation/popular.js',
'resources/assets/vendor/libs/@form-validation/bootstrap5.js',
'resources/assets/vendor/libs/@form-validation/auto-focus.js'])
@endsection

@section('page-script')
@vite(['resources/assets/js/pages-auth.js'])
@endsection

@section('page-style')
@if($split)
<style>
.auth-split-wrapper {
  display: flex;
  min-height: 100vh;
  min-height: 100dvh;
}
.auth-split-brand {
  flex: 0 0 58.333%;
  max-width: 58.333%;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  position: relative;
  overflow: hidden;
  padding: 3rem;
}
.auth-split-form {
  flex: 0 0 41.667%;
  max-width: 41.667%;
  display: flex;
  align-items: center;
  justify-content: center;
  background: #ffffff;
  padding: 2rem;
  position: relative;
}
.auth-split-form-inner {
  width: 100%;
  max-width: 420px;
  animation: authFadeIn .6s ease-out;
}
@keyframes authFadeIn {
  from { opacity: 0; transform: translateY(20px); }
  to { opacity: 1; transform: translateY(0); }
}
@media (max-width: 991.98px) {
  .auth-split-wrapper {
    flex-direction: column;
  }
  .auth-split-brand {
    flex: 0 0 auto;
    max-width: 100%;
    padding: 2rem 1.5rem;
    min-height: 220px;
  }
  .auth-split-form {
    flex: 1 1 auto;
    max-width: 100%;
    padding: 1.5rem;
  }
  .auth-split-form-inner {
    animation: none;
  }
}
</style>
@endif
@endsection

@section('content')
@if($split)
<div class="auth-split-wrapper">
  {{-- Left Brand Panel --}}
  <div class="auth-split-brand" style="background:linear-gradient(135deg,var(--mansaba-green) 0%,#0D3B2A 50%,#1A1A2E 100%);">
    <div style="position:absolute;inset:0;background-image:url('data:image/svg+xml,%3Csvg width=\'80\' height=\'80\' viewBox=\'0 0 80 80\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23C9972B\' fill-opacity=\'0.06\'%3E%3Cpath d=\'M50 50c0-5.523 4.477-10 10-10s10 4.477 10 10-4.477 10-10 10c0 5.523-4.477 10-10 10s-10-4.477-10-10 4.477-10 10-10zM10 10c0-5.523 4.477-10 10-10s10 4.477 10 10-4.477 10-10 10c0 5.523-4.477 10-10 10S0 25.523 0 20s4.477-10 10-10zm10 8c4.418 0 8-3.582 8-8s-3.582-8-8-8-8 3.582-8 8 3.582 8 8 8zm40 40c4.418 0 8-3.582 8-8s-3.582-8-8-8-8 3.582-8 8 3.582 8 8 8z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');pointer-events:none;z-index:0;"></div>
    <div style="position:absolute;top:0;left:0;right:0;height:4px;background:linear-gradient(90deg,var(--mansaba-gold),var(--mansaba-gold-light),var(--mansaba-gold));z-index:2;"></div>
    <div style="position:relative;z-index:1;text-align:center;max-width:480px;">
      <div class="mb-4">
        <span style="display:inline-flex;align-items:center;justify-content:center;width:90px;height:90px;border-radius:50%;background:rgba(255,255,255,0.12);backdrop-filter:blur(8px);overflow:hidden;">
          @php $logoSetting = $globalSettings['school_logo'] ?? ''; @endphp
          @if (!empty($logoSetting))
            @php $logoUrl = str_starts_with($logoSetting, 'http') ? $logoSetting : \App\Helpers\StorageHelper::url($logoSetting); @endphp
            <img src="{{ $logoUrl }}" alt="Logo" style="max-height:64px;max-width:64px;object-fit:contain;">
          @else
            @include('_partials.macros', ['width' => '48', 'height' => '33'])
          @endif
        </span>
      </div>
      <h1 style="font-family:'Trajan Pro',serif;color:#fff;font-size:1.6rem;font-weight:700;letter-spacing:0.04em;margin-bottom:0.5rem;">{{ config('app.name', 'MAN 1 Bandung') }}</h1>
      <p style="color:rgba(255,255,255,0.7);font-size:1rem;margin-bottom:1.5rem;font-family:'Amiri',serif;">&ldquo;{{ $globalSettings['motto'] ?? ($globalSettings['visi'] ?? 'Taqwa, Cerdas, Mandiri') }}&rdquo;</p>
      <div style="width:60px;height:2px;background:var(--mansaba-gold);margin:0 auto 2rem;"></div>
      <blockquote style="color:rgba(255,255,255,0.55);font-size:0.95rem;font-style:italic;line-height:1.8;font-family:'Amiri',serif;">
        &ldquo;Ya Tuhan kami, berikanlah kepada kami kebaikan di dunia dan kebaikan di akhirat, dan lindungilah kami dari azab neraka.&rdquo;
        <footer style="margin-top:0.5rem;font-size:0.8rem;color:rgba(255,255,255,0.35);">&mdash; QS. Al-Baqarah: 201</footer>
      </blockquote>
    </div>
  </div>

  {{-- Right Form Panel --}}
  <div class="auth-split-form">
    <div class="auth-split-form-inner">
      {{ $slot }}
    </div>
  </div>
</div>
@else
<section class="section-bg-cream" style="height:100vh;height:100dvh;display:flex;align-items:center;overflow:hidden;">
  <div class="container-xxl">
    <div class="row justify-content-center">
      <div class="col-lg-5 col-md-7">
        {{ $slot }}
      </div>
    </div>
  </div>
</section>
@endif
@endsection
