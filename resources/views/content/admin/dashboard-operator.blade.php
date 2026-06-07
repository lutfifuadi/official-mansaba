@php
$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Dashboard - Operator')

@section('vendor-style')
@vite(['resources/assets/vendor/libs/apex-charts/apex-charts.scss'])
@endsection

@section('vendor-script')
@vite(['resources/assets/vendor/libs/apex-charts/apexcharts.js'])
@endsection

@section('page-script')
@vite(['resources/assets/js/dashboards-operator.js'])
@endsection

@section('content')
<script>
  window.__operatorDashboard = {
    weeklyLabels: @json($weeklyLabels),
    weeklyData: @json($weeklyData),
    publishedPercent: {{ $publishedPercent }},
    beritaPublished: {{ $beritaPublished }},
    beritaDraft: {{ $beritaDraft }},
  };
</script>

<div class="row g-6">
  {{-- Total Berita --}}
  <div class="col-xxl-2 col-md-3 col-6">
    <div class="card h-100">
      <div class="card-header pb-3">
        <h5 class="card-title mb-1">Total Berita</h5>
        <p class="card-subtitle">Semua Konten</p>
      </div>
      <div class="card-body pt-0">
        <div id="totalBeritaSpark"></div>
        <div class="d-flex justify-content-between align-items-center mt-2 gap-3">
          <h4 class="mb-0">{{ $totalBerita }}</h4>
          <small class="text-primary">berita</small>
        </div>
      </div>
    </div>
  </div>

  {{-- Berita Published --}}
  <div class="col-xxl-2 col-md-3 col-6">
    <div class="card h-100">
      <div class="card-header pb-0">
        <h5 class="card-title mb-1">Published</h5>
        <p class="card-subtitle">Sudah Tayang</p>
      </div>
      <div id="beritaPublishedSpark"></div>
      <div class="card-body pt-0">
        <div class="d-flex justify-content-between align-items-center mt-3 gap-3">
          <h4 class="mb-0">{{ $beritaPublished }}</h4>
          @if($totalBerita > 0)
            <small class="text-success">{{ $publishedPercent }}%</small>
          @endif
        </div>
      </div>
    </div>
  </div>

  {{-- Total Prestasi --}}
  <div class="col-xxl-2 col-md-3 col-6">
    <div class="card h-100">
      <div class="card-body">
        <div class="badge p-2 bg-label-warning mb-3 rounded">
          <i class="icon-base ti tabler-trophy icon-28px"></i>
        </div>
        <h5 class="card-title mb-1">Prestasi</h5>
        <p class="card-subtitle">Pencapaian</p>
        <p class="text-heading mb-3 mt-1">{{ $totalPrestasi }}</p>
        <span class="badge bg-label-warning">View only</span>
      </div>
    </div>
  </div>

  {{-- Kunjungan Ringkasan --}}
  <div class="col-xxl-2 col-md-3 col-6">
    <div class="card h-100">
      <div class="card-body">
        <div class="badge p-2 bg-label-info mb-3 rounded">
          <i class="icon-base ti tabler-chart-line icon-28px"></i>
        </div>
        <h5 class="card-title mb-1">Kunjungan</h5>
        <p class="card-subtitle">Traffic Website</p>
        <div class="d-flex gap-3 mt-2">
          <div>
            <p class="text-heading mb-0">{{ number_format($visitToday) }}</p>
            <small>Hari Ini</small>
          </div>
          <div>
            <p class="text-heading mb-0">{{ number_format($visitTotal) }}</p>
            <small>Total</small>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- Galeri & Ekskul --}}
  <div class="col-xxl-2 col-md-3 col-6">
    <div class="card h-100">
      <div class="card-body">
        <div class="badge p-2 bg-label-info mb-3 rounded">
          <i class="icon-base ti tabler-photo icon-28px"></i>
        </div>
        <h5 class="card-title mb-1">Galeri + Ekskul</h5>
        <p class="card-subtitle">Konten Lain</p>
        <div class="d-flex gap-3 mt-2">
          <div>
            <p class="text-heading mb-0">{{ $totalGaleri }}</p>
            <small>Galeri</small>
          </div>
          <div>
            <p class="text-heading mb-0">{{ $totalEkskul }}</p>
            <small>Ekskul</small>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- Weekly Published Chart --}}
  <div class="col-xxl-4 col-xl-5 col-md-6 col-sm-8 col-12 mb-md-0">
    <div class="card h-100">
      <div class="card-body row">
        <div class="d-flex flex-column col-4">
          <div class="card-title mb-auto">
            <h5 class="mb-2 text-nowrap">Publikasi Mingguan</h5>
            <p class="mb-0">7 Hari Terakhir</p>
          </div>
          <div class="chart-statistics">
            <h3 class="card-title mb-1">{{ array_sum($weeklyData) }}</h3>
            <span class="badge bg-label-primary">berita</span>
          </div>
        </div>
        <div id="weeklyPublishedChart" class="col-8"></div>
      </div>
    </div>
  </div>

  {{-- News Status Tabs --}}
  <div class="col-xxl-8 col-12">
    <div class="card h-100">
      <div class="card-header d-flex justify-content-between align-items-center">
        <div class="card-title m-0">
          <h5 class="mb-1">Status Berita</h5>
          <p class="card-subtitle">Ringkasan Konten</p>
        </div>
        <a href="{{ route('admin.news.index') }}" class="btn btn-sm btn-label-primary">Kelola Berita</a>
      </div>
      <div class="card-body">
        <ul class="nav nav-tabs widget-nav-tabs pb-8 gap-4 mx-1 d-flex flex-nowrap" role="tablist">
          <li class="nav-item">
            <a href="javascript:void(0);"
              class="nav-link btn active d-flex flex-column align-items-center justify-content-center" role="tab"
              data-bs-toggle="tab" data-bs-target="#navs-published" aria-controls="navs-published" aria-selected="true">
              <div class="badge bg-label-success rounded p-2">
                <i class="icon-base ti tabler-circle-check icon-md"></i>
              </div>
              <h6 class="tab-widget-title mb-0 mt-2">Published</h6>
            </a>
          </li>
          <li class="nav-item">
            <a href="javascript:void(0);"
              class="nav-link btn d-flex flex-column align-items-center justify-content-center" role="tab"
              data-bs-toggle="tab" data-bs-target="#navs-draft" aria-controls="navs-draft" aria-selected="false">
              <div class="badge bg-label-warning rounded p-2">
                <i class="icon-base ti tabler-edit icon-md"></i>
              </div>
              <h6 class="tab-widget-title mb-0 mt-2">Draft</h6>
            </a>
          </li>
          <li class="nav-item">
            <a href="javascript:void(0);"
              class="nav-link btn d-flex flex-column align-items-center justify-content-center" role="tab"
              data-bs-toggle="tab" data-bs-target="#navs-progress" aria-controls="navs-progress" aria-selected="false">
              <div class="badge bg-label-info rounded p-2">
                <i class="icon-base ti tabler-progress icon-md"></i>
              </div>
              <h6 class="tab-widget-title mb-0 mt-2">Progress</h6>
            </a>
          </li>
        </ul>

        <div class="tab-content p-0 ms-0 ms-sm-2">
          {{-- Published Tab --}}
          <div class="tab-pane fade show active" id="navs-published" role="tabpanel">
            @if($beritaPublished > 0)
              <div class="d-flex align-items-center mb-4">
                <div class="badge bg-label-success rounded p-2 me-3">
                  <i class="icon-base ti tabler-news icon-lg"></i>
                </div>
                <div>
                  <h4 class="mb-1">{{ $beritaPublished }} <small class="text-muted fs-6">berita tayang</small></h4>
                  @if($totalBerita > 0)
                    <div class="progress" style="height:6px; width:200px">
                      <div class="progress-bar bg-success" style="width:{{ $publishedPercent }}%"></div>
                    </div>
                  @endif
                </div>
              </div>
            @else
              <p class="text-muted">Belum ada berita yang diterbitkan.</p>
            @endif

            @if(!empty($newsByCategory))
              <h6 class="mb-3">Per Kategori</h6>
              <div class="d-flex flex-wrap gap-3">
                @foreach($newsByCategory as $cat => $count)
                  <span class="badge bg-label-primary fs-6 px-3 py-2">
                    {{ $cat ?: 'Tanpa Kategori' }} <strong>{{ $count }}</strong>
                  </span>
                @endforeach
              </div>
            @endif
          </div>

          {{-- Draft Tab --}}
          <div class="tab-pane fade" id="navs-draft" role="tabpanel">
            @if($draftNews->count() > 0)
              <div class="d-flex align-items-center mb-4">
                <div class="badge bg-label-warning rounded p-2 me-3">
                  <i class="icon-base ti tabler-file-pencil icon-lg"></i>
                </div>
                <div>
                  <h4 class="mb-1">{{ $beritaDraft }} <small class="text-muted fs-6">draft</small></h4>
                </div>
              </div>
              <ul class="list-unstyled mb-0">
                @foreach($draftNews as $draft)
                  <li class="d-flex align-items-center mb-3 pb-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                    <div class="badge bg-label-secondary rounded p-1_5 me-3">
                      <i class="icon-base ti tabler-edit icon-sm"></i>
                    </div>
                    <div class="flex-grow-1">
                      <h6 class="mb-0">{{ $draft->title ?: '(Tanpa Judul)' }}</h6>
                      <small class="text-muted">{{ $draft->updated_at->diffForHumans() }}</small>
                    </div>
                    <a href="{{ route('admin.news.edit', $draft->id) }}" class="btn btn-icon btn-sm btn-text-secondary">
                      <i class="icon-base ti tabler-arrow-right icon-sm"></i>
                    </a>
                  </li>
                @endforeach
              </ul>
            @else
              <p class="text-muted">Tidak ada draft.</p>
            @endif
          </div>

          {{-- Progress Tab --}}
          <div class="tab-pane fade" id="navs-progress" role="tabpanel">
            <ul class="p-0 m-0">
              <li class="mb-6">
                <div class="d-flex justify-content-between align-items-center mb-2">
                  <h6 class="mb-0">Published</h6>
                  <small>{{ $beritaPublished }} / {{ $totalBerita }}</small>
                </div>
                <div class="progress" style="height:10px;">
                  <div class="progress-bar bg-success" style="width:{{ $publishedPercent }}%"></div>
                </div>
              </li>
              <li class="mb-6">
                <div class="d-flex justify-content-between align-items-center mb-2">
                  <h6 class="mb-0">Draft</h6>
                  <small>{{ $beritaDraft }} / {{ $totalBerita }}</small>
                </div>
                <div class="progress" style="height:10px;">
                  <div class="progress-bar bg-warning" style="width:{{ $totalBerita > 0 ? round($beritaDraft / $totalBerita * 100) : 0 }}%"></div>
                </div>
              </li>
              <li>
                <div class="d-flex justify-content-between align-items-center mt-5">
                  <span>Total Keseluruhan</span>
                  <h5 class="mb-0">{{ $totalBerita }}</h5>
                </div>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- Berita Terbaru Table --}}
  <div class="col-md-6 col-12">
    <div class="card h-100">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title m-0 me-2">Berita Terbaru</h5>
        <a href="{{ route('admin.news.index') }}" class="btn btn-sm btn-text-secondary">
          <i class="icon-base ti tabler-arrow-right icon-sm"></i>
        </a>
      </div>
      <div class="table-responsive">
        <table class="table table-borderless border-top">
          <thead class="border-bottom">
            <tr>
              <th>JUDUL</th>
              <th>STATUS</th>
              <th>TANGGAL</th>
            </tr>
          </thead>
          <tbody>
            @forelse($beritaTerbaru as $berita)
              <tr>
                <td>
                  <div class="d-flex flex-column">
                    <p class="mb-0 text-heading text-truncate" style="max-width:200px">{{ $berita->title }}</p>
                    <small class="text-body">{{ $berita->category ?? 'Umum' }}</small>
                  </div>
                </td>
                <td>
                  @if($berita->is_published)
                    <span class="badge bg-label-success">Published</span>
                  @else
                    <span class="badge bg-label-warning">Draft</span>
                  @endif
                </td>
                <td>
                  <small class="text-body text-nowrap">{{ $berita->published_at ? $berita->published_at->format('d M Y') : '-' }}</small>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="3" class="text-center py-4 text-muted">Belum ada berita.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  {{-- Quick Links & Actions --}}
  <div class="col-xxl-6">
    <div class="card h-100">
      <div class="card-header d-flex justify-content-between">
        <h5 class="card-title m-0 me-2 pt-1 mb-2 d-flex align-items-center">
          <i class="icon-base ti tabler-bolt me-3"></i> Aksi Cepat
        </h5>
      </div>
      <div class="card-body">
        <div class="row g-4">
          <div class="col-sm-6">
            <a href="{{ route('admin.news.create') }}" class="card border shadow-none h-100 text-decoration-none">
              <div class="card-body text-center">
                <div class="badge bg-label-primary p-3 rounded mb-3">
                  <i class="icon-base ti tabler-plus icon-lg"></i>
                </div>
                <h6 class="mb-1">Tulis Berita Baru</h6>
                <small class="text-body">Buat konten berita</small>
              </div>
            </a>
          </div>
          <div class="col-sm-6">
            <a href="{{ route('admin.news.index') }}" class="card border shadow-none h-100 text-decoration-none">
              <div class="card-body text-center">
                <div class="badge bg-label-info p-3 rounded mb-3">
                  <i class="icon-base ti tabler-list icon-lg"></i>
                </div>
                <h6 class="mb-1">Kelola Berita</h6>
                <small class="text-body">{{ $beritaDraft }} draft menunggu</small>
              </div>
            </a>
          </div>
          <div class="col-sm-6">
            <a href="{{ route('admin.news.index') }}?filter=published" class="card border shadow-none h-100 text-decoration-none">
              <div class="card-body text-center">
                <div class="badge bg-label-success p-3 rounded mb-3">
                  <i class="icon-base ti tabler-eye icon-lg"></i>
                </div>
                <h6 class="mb-1">Lihat Published</h6>
                <small class="text-body">{{ $beritaPublished }} berita tayang</small>
              </div>
            </a>
          </div>
          <div class="col-sm-6">
            <a href="{{ route('admin.profile') }}" class="card border shadow-none h-100 text-decoration-none">
              <div class="card-body text-center">
                <div class="badge bg-label-secondary p-3 rounded mb-3">
                  <i class="icon-base ti tabler-user icon-lg"></i>
                </div>
                <h6 class="mb-1">Profil Saya</h6>
                <small class="text-body">Pengaturan akun</small>
              </div>
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
