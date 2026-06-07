@php
$customizerHidden = 'customizer-hide';
$pageConfigs = ['myLayout' => 'blank'];
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Masuk — ' . config('app.name', 'MAN 1 Kota Bandung'))

@section('vendor-style')
@vite(['resources/assets/vendor/libs/@form-validation/form-validation.scss'])
@endsection

@section('vendor-script')
@vite(['resources/assets/vendor/libs/@form-validation/popular.js',
'resources/assets/vendor/libs/@form-validation/bootstrap5.js',
'resources/assets/vendor/libs/@form-validation/auto-focus.js'])
@endsection

@section('page-script')
@vite(['resources/assets/js/pages-auth.js'])
@endsection

@section('content')
<section class="section-bg-cream" style="height:100vh;height:100dvh;display:flex;align-items:center;overflow:hidden;">
  <div class="container-xxl">
    <div class="row justify-content-center">
      <div class="col-lg-5 col-md-7">
        {{ $slot }}
      </div>
    </div>
  </div>
</section>
@endsection
