@extends('layouts/contentNavbarLayout')

@section('title', 'Dashboard')

@section('content')
  @if(session('success'))
    <div class="alert alert-success alert-dismissible" role="alert">
      {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  @if(session('error'))
    <div class="alert alert-danger alert-dismissible" role="alert">
      {{ session('error') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  {{-- Welcome Banner --}}
  <div class="card bg-primary text-white mb-4">
    <div class="card-body">
      <div class="row align-items-center">
        <div class="col">
          <h4 class="text-white mb-1">Selamat Datang, {{ auth()->user()->name }}! 👋</h4>
          <p class="mb-0 opacity-75">Kelola konten website MAN 1 Kota Bandung dari sini.</p>
        </div>
        <div class="col-auto d-none d-md-block">
          <div class="d-flex gap-2">
            <a href="{{ route('admin.news.create') }}" class="btn btn-light btn-sm">
              <i class="icon-base ti tabler-plus me-1"></i> Berita Baru
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- Stat Cards --}}
  <div class="row g-6 mb-6">
    <div class="col-sm-6 col-xl-3">
      <div class="card h-100">
        <div class="card-body">
          <div class="d-flex align-items-start justify-content-between">
            <div class="content-left">
              <span class="text-heading fw-medium d-block mb-1">Berita</span>
              <div class="d-flex align-items-end">
                <h3 class="mb-0 me-2">{{ $totalBerita }}</h3>
                <small class="text-body-secondary">artikel</small>
              </div>
            </div>
            <div class="avatar">
              <span class="avatar-initial rounded bg-label-primary">
                <i class="icon-base ti tabler-news icon-lg"></i>
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>

    @isset($totalGaleri)
    <div class="col-sm-6 col-xl-3">
      <div class="card h-100">
        <div class="card-body">
          <div class="d-flex align-items-start justify-content-between">
            <div class="content-left">
              <span class="text-heading fw-medium d-block mb-1">Galeri</span>
              <div class="d-flex align-items-end">
                <h3 class="mb-0 me-2">{{ $totalGaleri }}</h3>
                <small class="text-body-secondary">foto</small>
              </div>
            </div>
            <div class="avatar">
              <span class="avatar-initial rounded bg-label-success">
                <i class="icon-base ti tabler-photo icon-lg"></i>
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>
    @endisset

    @isset($totalPrestasi)
    <div class="col-sm-6 col-xl-3">
      <div class="card h-100">
        <div class="card-body">
          <div class="d-flex align-items-start justify-content-between">
            <div class="content-left">
              <span class="text-heading fw-medium d-block mb-1">Prestasi</span>
              <div class="d-flex align-items-end">
                <h3 class="mb-0 me-2">{{ $totalPrestasi }}</h3>
                <small class="text-body-secondary">pencapaian</small>
              </div>
            </div>
            <div class="avatar">
              <span class="avatar-initial rounded bg-label-warning">
                <i class="icon-base ti tabler-trophy icon-lg"></i>
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>
    @endisset

    @isset($totalEkskul)
    <div class="col-sm-6 col-xl-3">
      <div class="card h-100">
        <div class="card-body">
          <div class="d-flex align-items-start justify-content-between">
            <div class="content-left">
              <span class="text-heading fw-medium d-block mb-1">Ekskul</span>
              <div class="d-flex align-items-end">
                <h3 class="mb-0 me-2">{{ $totalEkskul }}</h3>
                <small class="text-body-secondary">kegiatan</small>
              </div>
            </div>
            <div class="avatar">
              <span class="avatar-initial rounded bg-label-info">
                <i class="icon-base ti tabler-users-group icon-lg"></i>
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>

    @endisset

    @isset($totalPengguna)
    <div class="col-sm-6 col-xl-3">
      <div class="card h-100">
        <div class="card-body">
          <div class="d-flex align-items-start justify-content-between">
            <div class="content-left">
              <span class="text-heading fw-medium d-block mb-1">Pengguna</span>
              <div class="d-flex align-items-end">
                <h3 class="mb-0 me-2">{{ $totalPengguna }}</h3>
                <small class="text-body-secondary">akun</small>
              </div>
            </div>
            <div class="avatar">
              <span class="avatar-initial rounded bg-label-secondary">
                <i class="icon-base ti tabler-users icon-lg"></i>
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>
    @endisset

    <div class="col-sm-6 col-xl-3">
      <div class="card h-100">
        <div class="card-body">
          <div class="d-flex align-items-start justify-content-between">
            <div class="content-left">
              <span class="text-heading fw-medium d-block mb-1">Kunjungan</span>
              <div class="d-flex align-items-end">
                <h3 class="mb-0 me-2">{{ number_format($visitToday) }}</h3>
                <small class="text-body-secondary">hari ini</small>
              </div>
              <div class="d-flex align-items-center gap-3 mt-1">
                <small class="text-body-secondary">{{ number_format($visitWeek) }} minggu ini</small>
                <small class="text-body-secondary">|</small>
                <small class="text-body-secondary">{{ number_format($visitTotal) }} total</small>
              </div>
            </div>
            <div class="avatar">
              <span class="avatar-initial rounded bg-label-info">
                <i class="icon-base ti tabler-chart-line icon-lg"></i>
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- Content Cards --}}
  <div class="row g-6">
    {{-- Berita Terbaru --}}
    <div class="col-lg-6">
      <div class="card h-100">
        <div class="card-header border-bottom d-flex justify-content-between align-items-center">
          <div class="d-flex align-items-center">
            <div class="avatar avatar-sm me-3">
              <span class="avatar-initial rounded bg-label-primary">
                <i class="icon-base ti tabler-news"></i>
              </span>
            </div>
            <h5 class="card-title mb-0">Berita Terbaru</h5>
          </div>
          <a href="{{ route('admin.news.index') }}" class="btn btn-sm btn-label-primary">Lihat Semua</a>
        </div>
        <div class="card-body p-0">
          @if($beritaTerbaru->count() > 0)
            <div class="table-responsive">
              <table class="table table-hover mb-0">
                <thead class="border-top">
                  <tr>
                    <th>Judul</th>
                    <th>Kategori</th>
                    <th>Tanggal</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($beritaTerbaru as $berita)
                    <tr>
                      <td class="text-truncate" style="max-width:200px">
                        <strong>{{ $berita->title }}</strong>
                        <br><small class="text-muted">{{ $berita->author }}</small>
                      </td>
                      <td><span class="badge bg-label-{{ $catColors[$berita->category] ?? 'secondary' }}">{{ $berita->category ?? 'Umum' }}</span></td>
                      <td><small class="text-muted">{{ $berita->published_at ? $berita->published_at->format('d M Y') : '-' }}</small></td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          @else
            <div class="text-center py-5">
              <div class="avatar avatar-lg mb-3">
                <span class="avatar-initial rounded bg-label-secondary">
                  <i class="icon-base ti tabler-news-off icon-lg"></i>
                </span>
              </div>
              <p class="text-muted mb-0">Belum ada berita.</p>
            </div>
          @endif
        </div>
      </div>
    </div>

  </div>

@endsection
