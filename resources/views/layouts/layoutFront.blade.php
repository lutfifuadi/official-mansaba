@php
  $pageConfigs = array_merge(['myLayout' => 'front'], $pageConfigs ?? []);
@endphp
@isset($pageConfigs)
  {!! Helper::updatePageConfig($pageConfigs) !!}
@endisset
@php
  $configData = Helper::appClasses();
  $isFront = true;
@endphp

@extends('layouts/commonMaster')

@section('layoutContent')
  @include('layouts/sections/navbar/navbar-front')

  <!-- Sections:Start -->
  @yield('content')
  <!-- / Sections:End -->

  @include('layouts/sections/footer/footer-front')
@endsection
