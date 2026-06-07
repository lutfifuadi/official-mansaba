@php
$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Home')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
  <div class="card">
    <div class="card-body text-center py-5">
      <h4 class="fw-bold mb-3">MAN 1 Kota Bandung</h4>
      <p class="text-muted mb-4">Sistem Informasi Manajemen Madrasah</p>
      <div class="d-flex justify-content-center gap-3 flex-wrap">
        <a href="{{ url('/') }}" class="btn btn-primary">Ke Beranda</a>
        <a href="{{ url('public/home') }}" class="btn btn-outline-primary">Halaman Publik</a>
        <a href="{{ route('login') }}" class="btn btn-outline-secondary">Login</a>
      </div>
    </div>
  </div>
</div>
@endsection
