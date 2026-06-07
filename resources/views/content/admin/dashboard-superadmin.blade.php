@php
$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Dashboard - Super Admin')

@section('vendor-style')
@vite([
'resources/assets/vendor/libs/apex-charts/apex-charts.scss',
'resources/assets/vendor/libs/swiper/swiper.scss',
'resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss',
'resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss',
'resources/assets/vendor/fonts/flag-icons.scss'
])
@endsection
@section('page-style')
@vite('resources/assets/vendor/scss/pages/cards-advance.scss')
@endsection
@section('vendor-script')
@vite([
'resources/assets/vendor/libs/apex-charts/apexcharts.js',
'resources/assets/vendor/libs/swiper/swiper.js',
'resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js'
])
@endsection

@section('page-style')
<style>
  @keyframes pulse { 0% { opacity:1; } 50% { opacity:0.4; } 100% { opacity:1; } }
</style>
@endsection
@section('page-script')
@vite('resources/assets/js/dashboards-analytics.js')
@endsection

@section('content')
<div class="row g-6">

  {{-- Swiper Carousel — Statistik Konten --}}
  <div class="col-xl-6 col">
    <div class="swiper-container swiper-container-horizontal swiper swiper-card-advance-bg"
      id="swiper-with-pagination-cards">
      <div class="swiper-wrapper">
        <div class="swiper-slide">
          <div class="row">
            <div class="col-12">
              <h5 class="text-white mb-0">MAN 1 Kota Bandung</h5>
              <small>Total Konten Website</small>
            </div>
            <div class="row">
              <div class="col-lg-7 col-md-9 col-12 order-2 order-md-1 pt-md-9">
                <h6 class="text-white mt-0 mt-md-3 mb-4">Ringkasan</h6>
                <div class="row">
                  <div class="col-6">
                    <ul class="list-unstyled mb-0">
                      <li class="d-flex mb-4 align-items-center">
                        <p class="mb-0 fw-medium me-2 website-analytics-text-bg">{{ $totalBerita }}</p>
                        <p class="mb-0">Berita</p>
                      </li>
                      <li class="d-flex align-items-center">
                        <p class="mb-0 fw-medium me-2 website-analytics-text-bg">{{ $totalGaleri }}</p>
                        <p class="mb-0">Galeri</p>
                      </li>
                    </ul>
                  </div>
                  <div class="col-6">
                    <ul class="list-unstyled mb-0">
                      <li class="d-flex mb-4 align-items-center">
                        <p class="mb-0 fw-medium me-2 website-analytics-text-bg">{{ $totalPrestasi }}</p>
                        <p class="mb-0">Prestasi</p>
                      </li>
                      <li class="d-flex align-items-center">
                        <p class="mb-0 fw-medium me-2 website-analytics-text-bg">{{ $totalEkskul }}</p>
                        <p class="mb-0">Ekskul</p>
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
              <div class="col-lg-5 col-md-3 col-12 order-1 order-md-2 my-4 my-md-0 text-center">
                <img src="{{ asset('assets/img/illustrations/card-website-analytics-1.png') }}" alt="Website Analytics"
                  height="150" class="card-website-analytics-img" />
              </div>
            </div>
          </div>
        </div>
        <div class="swiper-slide">
          <div class="row">
            <div class="col-12">
              <h5 class="text-white mb-0">MAN 1 Kota Bandung</h5>
              <small>Interaksi Pengunjung</small>
            </div>
            <div class="col-lg-7 col-md-9 col-12 order-2 order-md-1 pt-md-9">
              <h6 class="text-white mt-0 mt-md-3 mb-4">Pengguna &amp; Konten</h6>
              <div class="row">
                <div class="col-6">
                  <ul class="list-unstyled mb-0">
                    <li class="d-flex mb-4 align-items-center">
                      <p class="mb-0 fw-medium me-2 website-analytics-text-bg">{{ $totalPengguna }}</p>
                      <p class="mb-0">Pengguna</p>
                    </li>
                    <li class="d-flex align-items-center">
                      <p class="mb-0 fw-medium me-2 website-analytics-text-bg">{{ $totalPublished }}%</p>
                      <p class="mb-0">Konten Publik</p>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
            <div class="col-lg-5 col-md-3 col-12 order-1 order-md-2 my-4 my-md-0 text-center">
              <img src="{{ asset('assets/img/illustrations/card-website-analytics-2.png') }}" alt="Website Analytics"
                height="150" class="card-website-analytics-img" />
            </div>
          </div>
        </div>
        <div class="swiper-slide">
          <div class="row">
            <div class="col-12">
              <h5 class="text-white mb-0">MAN 1 Kota Bandung</h5>
              <small>Selamat Datang, {{ auth()->user()->name }}</small>
            </div>
            <div class="col-lg-7 col-md-9 col-12 order-2 order-md-1 pt-md-9">
              <h6 class="text-white mt-0 mt-md-3 mb-4">Akses Cepat</h6>
              <div class="row">
                <div class="col-6">
                  <ul class="list-unstyled mb-0">
                    <li class="d-flex mb-4 align-items-center">
                      <a href="{{ route('admin.news.create') }}" class="text-white text-decoration-none fw-medium">+ Berita Baru</a>
                    </li>
                  </ul>
                </div>
                <div class="col-6">
                  <ul class="list-unstyled mb-0">
                    @if($role === 'super_admin')
                    <li class="d-flex align-items-center">
                      <a href="{{ route('admin.users.create') }}" class="text-white text-decoration-none fw-medium">+ Pengguna</a>
                    </li>
                    @endif
                  </ul>
                </div>
              </div>
            </div>
            <div class="col-lg-5 col-md-3 col-12 order-1 order-md-2 my-4 my-md-0 text-center">
              <img src="{{ asset('assets/img/illustrations/card-website-analytics-3.png') }}" alt="Website Analytics"
                height="150" class="card-website-analytics-img" />
            </div>
          </div>
        </div>
      </div>
      <div class="swiper-pagination"></div>
    </div>
  </div>

  {{-- Konten Publik vs Draft --}}
  <div class="col-xl-3 col-sm-6">
    <div class="card h-100">
      <div class="card-header pb-0">
        <h5 class="mb-3 card-title">Konten Publik</h5>
        <p class="mb-0 text-body">Total Konten Website</p>
        <h4 class="mb-0">{{ $totalBerita + $totalGaleri + $totalPrestasi + $totalEkskul }}</h4>
      </div>
      <div class="card-body px-0">
        <div id="averageDailySales"></div>
      </div>
    </div>
  </div>

  {{-- Published vs Draft Overview --}}
  <div class="col-xl-3 col-sm-6">
    <div class="card h-100">
      <div class="card-header">
        <div class="d-flex justify-content-between">
          <p class="mb-0 text-body">Published vs Draft</p>
          <p class="card-text fw-medium text-success">{{ $totalPublished }}% Publik</p>
        </div>
        <h4 class="card-title mb-1">{{ $totalPublished }}/{{ $totalPublished + $totalDraft }}</h4>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-4">
            <div class="d-flex gap-2 align-items-center mb-2">
              <span class="badge bg-label-info p-1 rounded"><i class="icon-base ti tabler-circle-check icon-sm"></i></span>
              <p class="mb-0">Publik</p>
            </div>
            <h5 class="mb-0 pt-1">{{ $totalPublishedPercent }}%</h5>
            <small class="text-body-secondary">{{ $totalPublished }}</small>
          </div>
          <div class="col-4">
            <div class="divider divider-vertical">
              <div class="divider-text">
                <span class="badge-divider-bg bg-label-secondary">VS</span>
              </div>
            </div>
          </div>
          <div class="col-4 text-end">
            <div class="d-flex gap-2 justify-content-end align-items-center mb-2">
              <p class="mb-0">Draft</p>
              <span class="badge bg-label-warning p-1 rounded"><i class="icon-base ti tabler-edit icon-sm"></i></span>
            </div>
            <h5 class="mb-0 pt-1">{{ 100 - $totalPublishedPercent }}%</h5>
            <small class="text-body-secondary">{{ $totalDraft }}</small>
          </div>
        </div>
        <div class="d-flex align-items-center mt-6">
          <div class="progress w-100" style="height: 10px;">
            <div class="progress-bar bg-info" style="width: {{ $totalPublishedPercent }}%" role="progressbar" aria-valuenow="{{ $totalPublishedPercent }}" aria-valuemin="0" aria-valuemax="100"></div>
            <div class="progress-bar bg-warning" role="progressbar" style="width: {{ 100 - $totalPublishedPercent }}%" aria-valuenow="{{ 100 - $totalPublishedPercent }}" aria-valuemin="0" aria-valuemax="100"></div>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- Penerbitan Mingguan --}}
  <div class="col-md-6">
    <div class="card h-100">
      <div class="card-header pb-0 d-flex justify-content-between">
        <div class="card-title mb-0">
          <h5 class="mb-1">Penerbitan Mingguan</h5>
          <p class="card-subtitle">7 Hari Terakhir</p>
        </div>
      </div>
      <div class="card-body">
        <div class="row align-items-center g-md-8">
          <div class="col-12 col-md-5 d-flex flex-column">
            <div class="d-flex gap-2 align-items-center mb-3 flex-wrap">
              <h2 class="mb-0">{{ array_sum($weeklyPublish) }}</h2>
              <div class="badge rounded bg-label-success">Konten</div>
            </div>
            <small class="text-body">Total konten yang diterbitkan pekan ini</small>
          </div>
          <div class="col-12 col-md-7 ps-xl-8">
            <div id="weeklyEarningReports"></div>
          </div>
        </div>
        <div class="border rounded p-5 mt-5">
          <div class="row gap-4 gap-sm-0">
            <div class="col-12 col-sm-4">
              <div class="d-flex gap-2 align-items-center">
                <div class="badge rounded bg-label-primary p-1"><i class="icon-base ti tabler-news icon-18px"></i></div>
                <h6 class="mb-0 fw-normal">Berita</h6>
              </div>
              <h4 class="my-2">{{ $weeklyBreakdown['news'] }}</h4>
              <div class="progress w-75" style="height:4px">
                <div class="progress-bar" role="progressbar" style="width: {{ $weeklyBreakdown['news'] > 0 ? ($weeklyBreakdown['news'] / max(1, array_sum($weeklyBreakdown)) * 100) : 0 }}%" aria-valuenow="{{ $weeklyBreakdown['news'] }}" aria-valuemin="0" aria-valuemax="100"></div>
              </div>
            </div>
            <div class="col-12 col-sm-4">
              <div class="d-flex gap-2 align-items-center">
                <div class="badge rounded bg-label-info p-1"><i class="icon-base ti tabler-photo icon-18px"></i></div>
                <h6 class="mb-0 fw-normal">Galeri</h6>
              </div>
              <h4 class="my-2">{{ $weeklyBreakdown['gallery'] }}</h4>
              <div class="progress w-75" style="height:4px">
                <div class="progress-bar bg-info" role="progressbar" style="width: {{ $weeklyBreakdown['gallery'] > 0 ? ($weeklyBreakdown['gallery'] / max(1, array_sum($weeklyBreakdown)) * 100) : 0 }}%" aria-valuenow="{{ $weeklyBreakdown['gallery'] }}" aria-valuemin="0" aria-valuemax="100"></div>
              </div>
            </div>
            <div class="col-12 col-sm-4">
              <div class="d-flex gap-2 align-items-center">
                <div class="badge rounded bg-label-warning p-1"><i class="icon-base ti tabler-trophy icon-18px"></i></div>
                <h6 class="mb-0 fw-normal">Prestasi</h6>
              </div>
              <h4 class="my-2">{{ $weeklyBreakdown['achievement'] }}</h4>
              <div class="progress w-75" style="height:4px">
                <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $weeklyBreakdown['achievement'] > 0 ? ($weeklyBreakdown['achievement'] / max(1, array_sum($weeklyBreakdown)) * 100) : 0 }}%" aria-valuenow="{{ $weeklyBreakdown['achievement'] }}" aria-valuemin="0" aria-valuemax="100"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- Support Tracker — Publish vs Draft Ratio --}}
  <div class="col-12 col-md-6">
    <div class="card h-100">
      <div class="card-header d-flex justify-content-between">
        <div class="card-title mb-0">
          <h5 class="mb-1">Rasio Konten</h5>
          <p class="card-subtitle">Published vs Draft</p>
        </div>
      </div>
      <div class="card-body row">
        <div class="col-12 col-sm-4">
          <div class="mt-lg-4 mt-lg-2 mb-lg-6 mb-2">
            <h2 class="mb-0">{{ $totalPublished + $totalDraft }}</h2>
            <p class="mb-0">Total Konten</p>
          </div>
          <ul class="p-0 m-0">
            <li class="d-flex gap-4 align-items-center mb-lg-3 pb-1">
              <div class="badge rounded bg-label-primary p-1_5"><i class="icon-base ti tabler-circle-check icon-md"></i></div>
              <div>
                <h6 class="mb-0 text-nowrap">Published</h6>
                <small class="text-body-secondary">{{ $totalPublished }}</small>
              </div>
            </li>
            <li class="d-flex gap-4 align-items-center mb-lg-3 pb-1">
              <div class="badge rounded bg-label-warning p-1_5"><i class="icon-base ti tabler-edit icon-md"></i></div>
              <div>
                <h6 class="mb-0 text-nowrap">Draft</h6>
                <small class="text-body-secondary">{{ $totalDraft }}</small>
              </div>
            </li>
            <li class="d-flex gap-4 align-items-center pb-1">
              <div class="badge rounded bg-label-info p-1_5"><i class="icon-base ti tabler-users icon-md"></i></div>
              <div>
                <h6 class="mb-0 text-nowrap">Pengguna</h6>
                <small class="text-body-secondary">{{ $totalPengguna }}</small>
              </div>
            </li>
          </ul>
        </div>
        <div class="col-12 col-md-8">
          <div id="supportTracker"></div>
        </div>
      </div>
    </div>
  </div>

  {{-- Statistik per Kategori Konten --}}
  <div class="col-xxl-4 col-md-6 order-1 order-xl-0">
    <div class="card h-100">
      <div class="card-header d-flex justify-content-between">
        <div class="card-title mb-0">
          <h5 class="mb-1">Statistik Konten</h5>
          <p class="card-subtitle">Semua Kategori</p>
        </div>
      </div>
      <div class="card-body">
        <ul class="p-0 m-0">
          <li class="d-flex align-items-center mb-4">
            <div class="avatar flex-shrink-0 me-4">
              <span class="avatar-initial rounded bg-label-primary"><i class="icon-base ti tabler-news fs-5"></i></span>
            </div>
            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
              <div class="me-2">
                <h6 class="mb-0 me-1">{{ $totalBerita }} Berita</h6>
                <small class="text-body">Konten Berita</small>
              </div>
              <div class="user-progress">
                <p class="text-success fw-medium mb-0 d-flex align-items-center gap-1">
                  {{ $totalBerita > 0 ? round($totalBerita / max(1, $totalBerita + $totalGaleri + $totalPrestasi + $totalEkskul) * 100) : 0 }}%
                </p>
              </div>
            </div>
          </li>
          <li class="d-flex align-items-center mb-4">
            <div class="avatar flex-shrink-0 me-4">
              <span class="avatar-initial rounded bg-label-success"><i class="icon-base ti tabler-photo fs-5"></i></span>
            </div>
            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
              <div class="me-2">
                <h6 class="mb-0 me-1">{{ $totalGaleri }} Galeri</h6>
                <small class="text-body">Foto &amp; Gambar</small>
              </div>
              <div class="user-progress">
                <p class="text-success fw-medium mb-0 d-flex align-items-center gap-1">
                  {{ $totalGaleri > 0 ? round($totalGaleri / max(1, $totalBerita + $totalGaleri + $totalPrestasi + $totalEkskul) * 100) : 0 }}%
                </p>
              </div>
            </div>
          </li>
          <li class="d-flex align-items-center mb-4">
            <div class="avatar flex-shrink-0 me-4">
              <span class="avatar-initial rounded bg-label-warning"><i class="icon-base ti tabler-trophy fs-5"></i></span>
            </div>
            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
              <div class="me-2">
                <h6 class="mb-0 me-1">{{ $totalPrestasi }} Prestasi</h6>
                <small class="text-body">Pencapaian Siswa</small>
              </div>
              <div class="user-progress">
                <p class="text-success fw-medium mb-0 d-flex align-items-center gap-1">
                  {{ $totalPrestasi > 0 ? round($totalPrestasi / max(1, $totalBerita + $totalGaleri + $totalPrestasi + $totalEkskul) * 100) : 0 }}%
                </p>
              </div>
            </div>
          </li>
          <li class="d-flex align-items-center mb-4">
            <div class="avatar flex-shrink-0 me-4">
              <span class="avatar-initial rounded bg-label-info"><i class="icon-base ti tabler-users-group fs-5"></i></span>
            </div>
            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
              <div class="me-2">
                <h6 class="mb-0 me-1">{{ $totalEkskul }} Ekskul</h6>
                <small class="text-body">Ekstrakurikuler</small>
              </div>
              <div class="user-progress">
                <p class="text-success fw-medium mb-0 d-flex align-items-center gap-1">
                  {{ $totalEkskul > 0 ? round($totalEkskul / max(1, $totalBerita + $totalGaleri + $totalPrestasi + $totalEkskul) * 100) : 0 }}%
                </p>
              </div>
            </div>
          </li>
          <li class="d-flex align-items-center">
            <div class="avatar flex-shrink-0 me-4">
              <span class="avatar-initial rounded bg-label-secondary"><i class="icon-base ti tabler-users fs-5"></i></span>
            </div>
            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
              <div class="me-2">
                <h6 class="mb-0 me-1">{{ $totalPengguna }} Pengguna</h6>
                <small class="text-body">Akses Admin</small>
              </div>
              <div class="user-progress">
                <p class="text-success fw-medium mb-0 d-flex align-items-center gap-1">
                  {{ $userRoleCounts['super_admin'] ?? 0 }} SA
                </p>
              </div>
            </div>
          </li>
        </ul>
      </div>
    </div>
  </div>

  {{-- Total Earning Chart — Distribusi Konten --}}
  <div class="col-12 col-md-6 col-xxl-4 order-2 order-xl-0">
    <div class="card h-100">
      <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
          <h5 class="mb-0 card-title">Distribusi Konten</h5>
        </div>
        <div class="d-flex align-items-center">
          <h2 class="mb-0 me-2">{{ $totalPublished }}%</h2>
          <i class="icon-base ti tabler-chevron-up text-success me-1"></i>
          <h6 class="text-success mb-0">Publik</h6>
        </div>
      </div>
      <div class="card-body">
        <div id="totalEarningChart"></div>
        <div class="d-flex align-items-start my-4">
          <div class="badge rounded bg-label-primary p-2 me-4 rounded"><i class="icon-base ti tabler-news icon-md"></i></div>
          <div class="d-flex justify-content-between w-100 gap-2 align-items-center">
            <div class="me-2">
              <h6 class="mb-0">Berita Publik</h6>
              <small class="text-body">{{ $beritaPublished }} dari {{ $totalBerita }}</small>
            </div>
            <h6 class="mb-0 text-success">{{ $totalBerita > 0 ? round($beritaPublished / $totalBerita * 100) : 0 }}%</h6>
          </div>
        </div>
        <div class="d-flex align-items-start">
          <div class="badge rounded bg-label-secondary p-2 me-4 rounded"><i class="icon-base ti tabler-photo icon-md"></i></div>
          <div class="d-flex justify-content-between w-100 gap-2 align-items-center">
            <div class="me-2">
              <h6 class="mb-0">Galeri Publik</h6>
              <small class="text-body">{{ $galleryPublished }} dari {{ $totalGaleri }}</small>
            </div>
            <h6 class="mb-0 text-success">{{ $totalGaleri > 0 ? round($galleryPublished / $totalGaleri * 100) : 0 }}%</h6>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- Monthly Campaign State — Recent Activity --}}
  <div class="col-xxl-4 col-md-6">
    <div class="card h-100">
      <div class="card-header d-flex justify-content-between">
        <div class="card-title mb-0">
          <h5 class="mb-1">Aktivitas Terbaru</h5>
          <p class="card-subtitle">Log Singkat</p>
        </div>
      </div>
      <div class="card-body">
        <ul class="p-0 m-0">
          @forelse($recentActivities as $activity)
            <li class="mb-6 d-flex justify-content-between align-items-center">
              <div class="badge bg-label-{{ $activity['color'] }} rounded p-1_5">
                <i class="icon-base ti {{ $activity['icon'] }} icon-md"></i>
              </div>
              <div class="d-flex justify-content-between w-100 flex-wrap">
                <h6 class="mb-0 ms-4">{{ $activity['title'] }}</h6>
                <div class="d-flex">
                  <small class="text-body-secondary">{{ $activity['time'] }}</small>
                </div>
              </div>
            </li>
          @empty
            <li class="mb-6 text-center text-muted">Belum ada aktivitas</li>
          @endforelse
        </ul>
      </div>
    </div>
  </div>

  {{-- Kunjungan Ringkasan --}}
  <div class="col-xxl-8 col-md-6 col-12">
    <div class="card h-100">
      <div class="card-header d-flex justify-content-between">
        <div class="card-title mb-0">
          <h5 class="mb-1">Kunjungan Website</h5>
          <p class="card-subtitle">Ringkasan Traffic</p>
        </div>
        <a href="{{ route('admin.visits.index') }}" class="btn btn-sm btn-label-info">
          <i class="icon-base ti tabler-chart-line me-1"></i> Detail
        </a>
      </div>
      <div class="card-body">
        <div class="row text-center g-4 mb-4">
          <div class="col-3">
            <h3 class="mb-0 text-info">{{ number_format($visitToday) }}</h3>
            <small class="text-body-secondary">Hari Ini</small>
            <div><small class="text-muted">{{ number_format($visitTodayUnique) }} unik</small></div>
          </div>
          <div class="col-3">
            <h3 class="mb-0 text-{{ $visitYesterday > 0 ? ($visitToday >= $visitYesterday ? 'success' : 'danger') : 'secondary' }}">
              {{ number_format($visitToday >= $visitYesterday ? $visitToday - $visitYesterday : $visitYesterday - $visitToday) }}
            </h3>
            <small class="text-body-secondary">vs Kemarin</small>
            <div>
              @php $trend = $visitYesterday > 0 ? round(($visitToday - $visitYesterday) / $visitYesterday * 100) : 0; @endphp
              <small class="text-{{ $trend >= 0 ? 'success' : 'danger' }}">
                <i class="icon-base ti {{ $trend >= 0 ? 'tabler-trending-up' : 'tabler-trending-down' }}"></i>
                {{ $trend >= 0 ? '+' : '' }}{{ $trend }}%
              </small>
            </div>
          </div>
          <div class="col-3">
            <h3 class="mb-0 text-success">{{ number_format($visitWeek) }}</h3>
            <small class="text-body-secondary">Minggu Ini</small>
          </div>
          <div class="col-3">
            <h3 class="mb-0 text-primary">{{ number_format($visitTotal) }}</h3>
            <small class="text-body-secondary">Total</small>
            <div><small class="text-muted">{{ number_format($visitTotalUnique) }} unik</small></div>
          </div>
        </div>
        <div class="border-top pt-3 mb-4">
          <div class="d-flex align-items-center justify-content-center gap-2">
            <span class="badge rounded-pill bg-success d-inline-block" style="width:8px;height:8px;animation:pulse 2s infinite;"></span>
            <span class="fw-medium">{{ number_format($visitOnline) }}</span>
            <small class="text-body-secondary">pengunjung online (5 menit terakhir)</small>
          </div>
        </div>

        @php
          $pageLabels = ['/' => 'Beranda', '/berita' => 'Berita', '/galeri' => 'Galeri', '/prestasi' => 'Prestasi', '/ekstrakurikuler' => 'Ekskul', '/profil' => 'Profil'];
          $topPageUrl = $visitTopPage?->url ?? '-';
          $topPageLabel = $pageLabels[$topPageUrl] ?? $topPageUrl;
          $deviceColors = ['desktop' => 'primary', 'mobile' => 'info', 'tablet' => 'warning'];
        @endphp
        <div class="row g-3 border-top pt-3">
          <div class="col-6">
            <small class="text-body-secondary fw-medium d-block mb-1">Halaman Terpopuler</small>
            <div class="d-flex align-items-center gap-2">
              <i class="icon-base ti tabler-link text-primary"></i>
              <span>{{ $topPageLabel }}</span>
              <small class="text-muted">({{ number_format($visitTopPage?->total ?? 0) }})</small>
            </div>
          </div>
          <div class="col-6">
            <small class="text-body-secondary fw-medium d-block mb-1">Perangkat (Minggu Ini)</small>
            <div class="d-flex gap-3">
              @forelse ($visitByDevice as $device => $count)
                <div class="d-flex align-items-center gap-1">
                  <span class="badge bg-label-{{ $deviceColors[$device] ?? 'secondary' }} p-1">
                    <i class="icon-base ti {{ $device === 'mobile' ? 'tabler-device-mobile' : ($device === 'tablet' ? 'tabler-device-tablet' : 'tabler-device-desktop') }}" style="font-size:0.75rem;"></i>
                  </span>
                  <small>{{ number_format($count) }}</small>
                </div>
              @empty
                <small class="text-muted">Belum ada data</small>
              @endforelse
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- Source Visit — Quick Stats --}}
  @if(auth()->user()->isSuperAdmin())
  <div class="col-xxl-4 col-md-6 col-12">
    <div class="card h-100">
      <div class="card-header d-flex justify-content-between">
        <div class="card-title mb-0">
          <h5 class="mb-1">Pengguna Sistem</h5>
          <p class="card-subtitle">Berdasarkan Role</p>
        </div>
      </div>
      <div class="card-body">
        <ul class="list-unstyled mb-0">
          <li class="mb-6">
            <div class="d-flex align-items-center">
              <div class="badge bg-label-danger text-body p-2 me-4 rounded"><i class="icon-base ti tabler-shield icon-md"></i></div>
              <div class="d-flex justify-content-between w-100 flex-wrap gap-2">
                <div class="me-2">
                  <h6 class="mb-0">Super Admin</h6>
                  <small class="text-body">Akses penuh</small>
                </div>
                <div class="d-flex align-items-center">
                  <p class="mb-0">{{ $userRoleCounts['super_admin'] ?? 0 }}</p>
                  <div class="ms-4 badge bg-label-danger">{{ $totalPengguna > 0 ? round(($userRoleCounts['super_admin'] ?? 0) / $totalPengguna * 100) : 0 }}%</div>
                </div>
              </div>
            </div>
          </li>
          <li class="mb-6">
            <div class="d-flex align-items-center">
              <div class="badge bg-label-primary text-body p-2 me-4 rounded"><i class="icon-base ti tabler-user-shield icon-md"></i></div>
              <div class="d-flex justify-content-between w-100 flex-wrap gap-2">
                <div class="me-2">
                  <h6 class="mb-0">Admin</h6>
                  <small class="text-body">Manajemen konten</small>
                </div>
                <div class="d-flex align-items-center">
                  <p class="mb-0">{{ $userRoleCounts['admin'] ?? 0 }}</p>
                  <div class="ms-4 badge bg-label-primary">{{ $totalPengguna > 0 ? round(($userRoleCounts['admin'] ?? 0) / $totalPengguna * 100) : 0 }}%</div>
                </div>
              </div>
            </div>
          </li>
          <li class="mb-6">
            <div class="d-flex align-items-center">
              <div class="badge bg-label-info text-body p-2 me-4 rounded"><i class="icon-base ti tabler-user-cog icon-md"></i></div>
              <div class="d-flex justify-content-between w-100 flex-wrap gap-2">
                <div class="me-2">
                  <h6 class="mb-0">Operator</h6>
                  <small class="text-body">Input berita</small>
                </div>
                <div class="d-flex align-items-center">
                  <p class="mb-0">{{ $userRoleCounts['operator'] ?? 0 }}</p>
                  <div class="ms-4 badge bg-label-info">{{ $totalPengguna > 0 ? round(($userRoleCounts['operator'] ?? 0) / $totalPengguna * 100) : 0 }}%</div>
                </div>
              </div>
            </div>
          </li>
          <li class="mb-6">
            <div class="d-flex align-items-center">
              <div class="badge bg-label-secondary text-body p-2 me-4 rounded"><i class="icon-base ti tabler-user-edit icon-md"></i></div>
              <div class="d-flex justify-content-between w-100 flex-wrap gap-2">
                <div class="me-2">
                  <h6 class="mb-0">Editor</h6>
                  <small class="text-body">Berita &amp; prestasi</small>
                </div>
                <div class="d-flex align-items-center">
                  <p class="mb-0">{{ $userRoleCounts['editor'] ?? 0 }}</p>
                  <div class="ms-4 badge bg-label-secondary">{{ $totalPengguna > 0 ? round(($userRoleCounts['editor'] ?? 0) / $totalPengguna * 100) : 0 }}%</div>
                </div>
              </div>
            </div>
          </li>
        </ul>
      </div>
    </div>
  </div>
  @endif

  {{-- Tabel Berita Terbaru --}}
  <div class="col-xxl-12">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Berita Terbaru</h5>
        <a href="{{ route('admin.news.index') }}" class="btn btn-sm btn-label-primary">Lihat Semua</a>
      </div>
      <div class="table-responsive mb-4">
        <table class="table table-hover">
          <thead class="border-top">
            <tr>
              <th>#</th>
              <th>Judul</th>
              <th>Kategori</th>
              <th>Penulis</th>
              <th>Status</th>
              <th>Tanggal</th>
            </tr>
          </thead>
          <tbody>
            @forelse($beritaTerbaru as $item)
              <tr>
                <td>{{ $loop->iteration }}</td>
                <td class="text-truncate" style="max-width:250px">{{ $item->title }}</td>
                <td>{{ $item->category ?? 'Umum' }}</td>
                <td>{{ $item->author }}</td>
                <td>
                  @if($item->is_published)
                    <span class="badge bg-label-success">Publik</span>
                  @else
                    <span class="badge bg-label-secondary">Draft</span>
                  @endif
                </td>
                <td>{{ $item->published_at ? $item->published_at->format('d M Y') : '-' }}</td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="text-center">Belum ada berita.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

</div>
@endsection
