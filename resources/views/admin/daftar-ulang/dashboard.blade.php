@extends('layouts/contentNavbarLayout')

@section('title', 'Dashboard Progress Daftar Ulang')

@section('vendor-style')
@vite([
  'resources/assets/vendor/libs/apex-charts/apex-charts.scss'
])
@endsection

@section('vendor-script')
@vite([
  'resources/assets/vendor/libs/apex-charts/apexcharts.js'
])
@endsection

@section('content')
<!-- Row 1: Global Stats Cards -->
<div class="row g-5 mb-5">
  <div class="col-sm-6 col-xl-3">
    <div class="card h-100">
      <div class="card-body pb-3 pt-3">
        <div class="d-flex align-items-start justify-content-between">
          <div class="content-left">
            <span class="text-heading fw-medium d-block mb-0">Total Siswa</span>
            <div class="d-flex align-items-end mt-1">
              <h3 class="mb-0 me-2" id="dash-stat-total">{{ $totalSiswa }}</h3>
              <small class="text-body-secondary">siswa</small>
            </div>
          </div>
          <div class="avatar">
            <span class="avatar-initial rounded bg-label-primary">
              <i class="icon-base ti tabler-users icon-lg"></i>
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-sm-6 col-xl-3">
    <div class="card h-100">
      <div class="card-body pb-3 pt-3">
        <div class="d-flex align-items-start justify-content-between">
          <div class="content-left">
            <span class="text-heading fw-medium d-block mb-0">Sudah Lengkap</span>
            <div class="d-flex align-items-end mt-1">
              <h3 class="mb-0 me-2 text-success" id="dash-stat-lengkap">{{ $jumlahLengkap }}</h3>
              <small class="text-body-secondary">siswa</small>
            </div>
          </div>
          <div class="avatar">
            <span class="avatar-initial rounded bg-label-success">
              <i class="icon-base ti tabler-circle-check icon-lg"></i>
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-sm-6 col-xl-3">
    <div class="card h-100">
      <div class="card-body pb-3 pt-3">
        <div class="d-flex align-items-start justify-content-between">
          <div class="content-left">
            <span class="text-heading fw-medium d-block mb-0">Belum Lengkap</span>
            <div class="d-flex align-items-end mt-1">
              <h3 class="mb-0 me-2 text-danger" id="dash-stat-belum">{{ $jumlahBelumLengkap }}</h3>
              <small class="text-body-secondary">siswa</small>
            </div>
          </div>
          <div class="avatar">
            <span class="avatar-initial rounded bg-label-danger">
              <i class="icon-base ti tabler-circle-x icon-lg"></i>
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-sm-6 col-xl-3">
    <div class="card h-100">
      <div class="card-body pb-3 pt-3">
        <div class="d-flex align-items-start justify-content-between">
          <div class="content-left">
            <span class="text-heading fw-medium d-block mb-0">Total Progress</span>
            <div class="d-flex align-items-end mt-1">
              <h3 class="mb-0 me-2" id="dash-stat-persen">{{ $progressGlobal }}%</h3>
              <small class="text-body-secondary">selesai</small>
            </div>
          </div>
          <div class="avatar">
            <span class="avatar-initial rounded bg-label-info">
              <i class="icon-base ti tabler-chart-pie icon-lg"></i>
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Row 2: Detail Progress Per Kelas (XI & XII) -->
<div class="row g-5 mb-5">
  <div class="col-md-6">
    <div class="card h-100">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-4">
          <h5 class="card-title mb-0">Progress Kelas XI (Target)</h5>
          <span class="badge bg-label-primary">Target: {{ $totalSiswaXI }} Siswa</span>
        </div>
        <div class="d-flex align-items-center gap-4 mb-3">
          <div class="w-100">
            <div class="d-flex justify-content-between mb-1">
              <span class="fw-medium text-heading" id="dash-xi-lengkap-text">{{ $jumlahLengkapXI }} dari {{ $totalSiswaXI }} Lengkap</span>
              <span class="fw-semibold text-primary" id="dash-xi-persen">{{ $progressXI }}%</span>
            </div>
            <div class="progress" style="height: 10px;">
              <div class="progress-bar bg-primary" id="dash-xi-progress-bar" role="progressbar" style="width: {{ $progressXI }}%;" aria-valuenow="{{ $progressXI }}" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
          </div>
        </div>
        <div class="row text-center g-4">
          <div class="col-6 border-end">
            <p class="mb-1 text-muted">Lengkap</p>
            <h4 class="mb-0 text-success" id="dash-xi-lengkap">{{ $jumlahLengkapXI }}</h4>
          </div>
          <div class="col-6">
            <p class="mb-1 text-muted">Belum Lengkap</p>
            <h4 class="mb-0 text-danger" id="dash-xi-belum">{{ $jumlahBelumLengkapXI }}</h4>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-md-6">
    <div class="card h-100">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-4">
          <h5 class="card-title mb-0">Progress Kelas XII (Target)</h5>
          <span class="badge bg-label-primary">Target: {{ $totalSiswaXII }} Siswa</span>
        </div>
        <div class="d-flex align-items-center gap-4 mb-3">
          <div class="w-100">
            <div class="d-flex justify-content-between mb-1">
              <span class="fw-medium text-heading" id="dash-xii-lengkap-text">{{ $jumlahLengkapXII }} dari {{ $totalSiswaXII }} Lengkap</span>
              <span class="fw-semibold text-success" id="dash-xii-persen">{{ $progressXII }}%</span>
            </div>
            <div class="progress" style="height: 10px;">
              <div class="progress-bar bg-success" id="dash-xii-progress-bar" role="progressbar" style="width: {{ $progressXII }}%;" aria-valuenow="{{ $progressXII }}" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
          </div>
        </div>
        <div class="row text-center g-4">
          <div class="col-6 border-end">
            <p class="mb-1 text-muted">Lengkap</p>
            <h4 class="mb-0 text-success" id="dash-xii-lengkap">{{ $jumlahLengkapXII }}</h4>
          </div>
          <div class="col-6">
            <p class="mb-1 text-muted">Belum Lengkap</p>
            <h4 class="mb-0 text-danger" id="dash-xii-belum">{{ $jumlahBelumLengkapXII }}</h4>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Row 2.5: Nav Tabs Detail Segmentasi Per Tingkat -->
<div class="nav-align-top mb-5">
  <ul class="nav nav-tabs" role="tablist">
    <li class="nav-item">
      <button type="button" class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-detail-semua" role="tab" aria-selected="true">
        <i class="icon-base ti tabler-chart-pie me-1_5"></i> Semua Tingkat
      </button>
    </li>
    <li class="nav-item">
      <button type="button" class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-detail-xi" role="tab" aria-selected="false">
        <i class="icon-base ti tabler-school me-1_5"></i> Kelas XI
      </button>
    </li>
    <li class="nav-item">
      <button type="button" class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-detail-xii" role="tab" aria-selected="false">
        <i class="icon-base ti tabler-school me-1_5"></i> Kelas XII
      </button>
    </li>
  </ul>
  <div class="tab-content p-0 bg-transparent shadow-none border-0 mt-4">
    <!-- TAB SEMUA TINGKAT -->
    <div class="tab-pane fade show active" id="tab-detail-semua" role="tabpanel">
      <div class="row g-5">
        <!-- Kolom Kiri: Donut Chart Kelompok Kelengkapan -->
        <div class="col-md-6 col-lg-7">
          <div class="card h-100">
            <div class="card-header d-flex align-items-center justify-content-between">
              <div class="card-title mb-0">
                <h5 class="m-0 me-2">Segmentasi Kelompok Kelengkapan</h5>
                <small class="text-muted">Porsi kelengkapan berkas siswa secara keseluruhan</small>
              </div>
            </div>
            <div class="card-body">
              <div id="kelompokDonutChart" style="min-height: 290px;"></div>
            </div>
          </div>
        </div>
        <!-- Kolom Kanan: Daftar Kurang Berkas Terbanyak -->
        <div class="col-md-6 col-lg-5">
          <div class="card h-100">
            <div class="card-header d-flex align-items-center justify-content-between">
              <div class="card-title mb-0">
                <h5 class="m-0 me-2">Kurang Berkas Terbanyak</h5>
                <small class="text-muted">Item berkas yang paling banyak kurang kumpul</small>
              </div>
            </div>
            <div class="card-body">
              <!-- Progress Bars untuk masing-masing item berkas -->
              <div class="mb-4">
                <div class="d-flex justify-content-between align-items-center mb-1">
                  <span class="fw-medium text-heading">Ijazah</span>
                  <div class="d-flex align-items-center">
                    <span class="text-muted me-2 small" id="kurang-ijazah-count">{{ $ringkasanKurang['ijazah'] }} siswa</span>
                    <span class="fw-semibold text-danger" id="kurang-ijazah-persen">{{ $totalSiswa > 0 ? round(($ringkasanKurang['ijazah'] / $totalSiswa) * 100, 1) : 0 }}%</span>
                  </div>
                </div>
                <div class="progress" style="height: 8px;">
                  <div class="progress-bar bg-danger" id="kurang-ijazah-bar" role="progressbar" style="width: {{ $totalSiswa > 0 ? ($ringkasanKurang['ijazah'] / $totalSiswa) * 100 : 0 }}%;" aria-valuenow="{{ $totalSiswa > 0 ? ($ringkasanKurang['ijazah'] / $totalSiswa) * 100 : 0 }}" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
              </div>
              <div class="mb-4">
                <div class="d-flex justify-content-between align-items-center mb-1">
                  <span class="fw-medium text-heading">Akte Kelahiran</span>
                  <div class="d-flex align-items-center">
                    <span class="text-muted me-2 small" id="kurang-akte-count">{{ $ringkasanKurang['akte_kelahiran'] }} siswa</span>
                    <span class="fw-semibold text-warning" id="kurang-akte-persen">{{ $totalSiswa > 0 ? round(($ringkasanKurang['akte_kelahiran'] / $totalSiswa) * 100, 1) : 0 }}%</span>
                  </div>
                </div>
                <div class="progress" style="height: 8px;">
                  <div class="progress-bar bg-warning" id="kurang-akte-bar" role="progressbar" style="width: {{ $totalSiswa > 0 ? ($ringkasanKurang['akte_kelahiran'] / $totalSiswa) * 100 : 0 }}%;" aria-valuenow="{{ $totalSiswa > 0 ? ($ringkasanKurang['akte_kelahiran'] / $totalSiswa) * 100 : 0 }}" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
              </div>
              <div class="mb-4">
                <div class="d-flex justify-content-between align-items-center mb-1">
                  <span class="fw-medium text-heading">Raport</span>
                  <div class="d-flex align-items-center">
                    <span class="text-muted me-2 small" id="kurang-raport-count">{{ $ringkasanKurang['raport'] }} siswa</span>
                    <span class="fw-semibold text-info" id="kurang-raport-persen">{{ $totalSiswa > 0 ? round(($ringkasanKurang['raport'] / $totalSiswa) * 100, 1) : 0 }}%</span>
                  </div>
                </div>
                <div class="progress" style="height: 8px;">
                  <div class="progress-bar bg-info" id="kurang-raport-bar" role="progressbar" style="width: {{ $totalSiswa > 0 ? ($ringkasanKurang['raport'] / $totalSiswa) * 100 : 0 }}%;" aria-valuenow="{{ $totalSiswa > 0 ? ($ringkasanKurang['raport'] / $totalSiswa) * 100 : 0 }}" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
              </div>
              <div class="mb-0">
                <div class="d-flex justify-content-between align-items-center mb-1">
                  <span class="fw-medium text-heading">Kartu Keluarga</span>
                  <div class="d-flex align-items-center">
                    <span class="text-muted me-2 small" id="kurang-kk-count">{{ $ringkasanKurang['kartu_keluarga'] }} siswa</span>
                    <span class="fw-semibold text-primary" id="kurang-kk-persen">{{ $totalSiswa > 0 ? round(($ringkasanKurang['kartu_keluarga'] / $totalSiswa) * 100, 1) : 0 }}%</span>
                  </div>
                </div>
                <div class="progress" style="height: 8px;">
                  <div class="progress-bar bg-primary" id="kurang-kk-bar" role="progressbar" style="width: {{ $totalSiswa > 0 ? ($ringkasanKurang['kartu_keluarga'] / $totalSiswa) * 100 : 0 }}%;" aria-valuenow="{{ $totalSiswa > 0 ? ($ringkasanKurang['kartu_keluarga'] / $totalSiswa) * 100 : 0 }}" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- TAB KELAS XI -->
    <div class="tab-pane fade" id="tab-detail-xi" role="tabpanel">
      <div class="row g-5">
        <!-- Kolom Kiri: Donut Chart Kelompok Kelengkapan XI -->
        <div class="col-md-6 col-lg-7">
          <div class="card h-100">
            <div class="card-header d-flex align-items-center justify-content-between">
              <div class="card-title mb-0">
                <h5 class="m-0 me-2">Segmentasi Kelompok Kelengkapan Kelas XI</h5>
                <small class="text-muted">Porsi kelengkapan berkas siswa target Kelas XI</small>
              </div>
            </div>
            <div class="card-body">
              <div id="kelompokDonutChartXI" style="min-height: 290px;"></div>
            </div>
          </div>
        </div>
        <!-- Kolom Kanan: Daftar Kurang Berkas Terbanyak XI -->
        <div class="col-md-6 col-lg-5">
          <div class="card h-100">
            <div class="card-header d-flex align-items-center justify-content-between">
              <div class="card-title mb-0">
                <h5 class="m-0 me-2">Kurang Berkas Terbanyak Kelas XI</h5>
                <small class="text-muted">Item berkas yang paling banyak kurang kumpul Kelas XI</small>
              </div>
            </div>
            <div class="card-body">
              <!-- Progress Bars XI -->
              <div class="mb-4">
                <div class="d-flex justify-content-between align-items-center mb-1">
                  <span class="fw-medium text-heading">Ijazah</span>
                  <div class="d-flex align-items-center">
                    <span class="text-muted me-2 small" id="kurang-ijazah-count-xi">{{ $ringkasanKurangXI['ijazah'] }} siswa</span>
                    <span class="fw-semibold text-danger" id="kurang-ijazah-persen-xi">{{ $totalSiswaXI > 0 ? round(($ringkasanKurangXI['ijazah'] / $totalSiswaXI) * 100, 1) : 0 }}%</span>
                  </div>
                </div>
                <div class="progress" style="height: 8px;">
                  <div class="progress-bar bg-danger" id="kurang-ijazah-bar-xi" role="progressbar" style="width: {{ $totalSiswaXI > 0 ? ($ringkasanKurangXI['ijazah'] / $totalSiswaXI) * 100 : 0 }}%;" aria-valuenow="{{ $totalSiswaXI > 0 ? ($ringkasanKurangXI['ijazah'] / $totalSiswaXI) * 100 : 0 }}" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
              </div>
              <div class="mb-4">
                <div class="d-flex justify-content-between align-items-center mb-1">
                  <span class="fw-medium text-heading">Akte Kelahiran</span>
                  <div class="d-flex align-items-center">
                    <span class="text-muted me-2 small" id="kurang-akte-count-xi">{{ $ringkasanKurangXI['akte_kelahiran'] }} siswa</span>
                    <span class="fw-semibold text-warning" id="kurang-akte-persen-xi">{{ $totalSiswaXI > 0 ? round(($ringkasanKurangXI['akte_kelahiran'] / $totalSiswaXI) * 100, 1) : 0 }}%</span>
                  </div>
                </div>
                <div class="progress" style="height: 8px;">
                  <div class="progress-bar bg-warning" id="kurang-akte-bar-xi" role="progressbar" style="width: {{ $totalSiswaXI > 0 ? ($ringkasanKurangXI['akte_kelahiran'] / $totalSiswaXI) * 100 : 0 }}%;" aria-valuenow="{{ $totalSiswaXI > 0 ? ($ringkasanKurangXI['akte_kelahiran'] / $totalSiswaXI) * 100 : 0 }}" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
              </div>
              <div class="mb-4">
                <div class="d-flex justify-content-between align-items-center mb-1">
                  <span class="fw-medium text-heading">Raport</span>
                  <div class="d-flex align-items-center">
                    <span class="text-muted me-2 small" id="kurang-raport-count-xi">{{ $ringkasanKurangXI['raport'] }} siswa</span>
                    <span class="fw-semibold text-info" id="kurang-raport-persen-xi">{{ $totalSiswaXI > 0 ? round(($ringkasanKurangXI['raport'] / $totalSiswaXI) * 100, 1) : 0 }}%</span>
                  </div>
                </div>
                <div class="progress" style="height: 8px;">
                  <div class="progress-bar bg-info" id="kurang-raport-bar-xi" role="progressbar" style="width: {{ $totalSiswaXI > 0 ? ($ringkasanKurangXI['raport'] / $totalSiswaXI) * 100 : 0 }}%;" aria-valuenow="{{ $totalSiswaXI > 0 ? ($ringkasanKurangXI['raport'] / $totalSiswaXI) * 100 : 0 }}" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
              </div>
              <div class="mb-0">
                <div class="d-flex justify-content-between align-items-center mb-1">
                  <span class="fw-medium text-heading">Kartu Keluarga</span>
                  <div class="d-flex align-items-center">
                    <span class="text-muted me-2 small" id="kurang-kk-count-xi">{{ $ringkasanKurangXI['kartu_keluarga'] }} siswa</span>
                    <span class="fw-semibold text-primary" id="kurang-kk-persen-xi">{{ $totalSiswaXI > 0 ? round(($ringkasanKurangXI['kartu_keluarga'] / $totalSiswaXI) * 100, 1) : 0 }}%</span>
                  </div>
                </div>
                <div class="progress" style="height: 8px;">
                  <div class="progress-bar bg-primary" id="kurang-kk-bar-xi" role="progressbar" style="width: {{ $totalSiswaXI > 0 ? ($ringkasanKurangXI['kartu_keluarga'] / $totalSiswaXI) * 100 : 0 }}%;" aria-valuenow="{{ $totalSiswaXI > 0 ? ($ringkasanKurangXI['kartu_keluarga'] / $totalSiswaXI) * 100 : 0 }}" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- TAB KELAS XII -->
    <div class="tab-pane fade" id="tab-detail-xii" role="tabpanel">
      <div class="row g-5">
        <!-- Kolom Kiri: Donut Chart Kelompok Kelengkapan XII -->
        <div class="col-md-6 col-lg-7">
          <div class="card h-100">
            <div class="card-header d-flex align-items-center justify-content-between">
              <div class="card-title mb-0">
                <h5 class="m-0 me-2">Segmentasi Kelompok Kelengkapan Kelas XII</h5>
                <small class="text-muted">Porsi kelengkapan berkas siswa target Kelas XII</small>
              </div>
            </div>
            <div class="card-body">
              <div id="kelompokDonutChartXII" style="min-height: 290px;"></div>
            </div>
          </div>
        </div>
        <!-- Kolom Kanan: Daftar Kurang Berkas Terbanyak XII -->
        <div class="col-md-6 col-lg-5">
          <div class="card h-100">
            <div class="card-header d-flex align-items-center justify-content-between">
              <div class="card-title mb-0">
                <h5 class="m-0 me-2">Kurang Berkas Terbanyak Kelas XII</h5>
                <small class="text-muted">Item berkas yang paling banyak kurang kumpul Kelas XII</small>
              </div>
            </div>
            <div class="card-body">
              <!-- Progress Bars XII -->
              <div class="mb-4">
                <div class="d-flex justify-content-between align-items-center mb-1">
                  <span class="fw-medium text-heading">Ijazah</span>
                  <div class="d-flex align-items-center">
                    <span class="text-muted me-2 small" id="kurang-ijazah-count-xii">{{ $ringkasanKurangXII['ijazah'] }} siswa</span>
                    <span class="fw-semibold text-danger" id="kurang-ijazah-persen-xii">{{ $totalSiswaXII > 0 ? round(($ringkasanKurangXII['ijazah'] / $totalSiswaXII) * 100, 1) : 0 }}%</span>
                  </div>
                </div>
                <div class="progress" style="height: 8px;">
                  <div class="progress-bar bg-danger" id="kurang-ijazah-bar-xii" role="progressbar" style="width: {{ $totalSiswaXII > 0 ? ($ringkasanKurangXII['ijazah'] / $totalSiswaXII) * 100 : 0 }}%;" aria-valuenow="{{ $totalSiswaXII > 0 ? ($ringkasanKurangXII['ijazah'] / $totalSiswaXII) * 100 : 0 }}" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
              </div>
              <div class="mb-4">
                <div class="d-flex justify-content-between align-items-center mb-1">
                  <span class="fw-medium text-heading">Akte Kelahiran</span>
                  <div class="d-flex align-items-center">
                    <span class="text-muted me-2 small" id="kurang-akte-count-xii">{{ $ringkasanKurangXII['akte_kelahiran'] }} siswa</span>
                    <span class="fw-semibold text-warning" id="kurang-akte-persen-xii">{{ $totalSiswaXII > 0 ? round(($ringkasanKurangXII['akte_kelahiran'] / $totalSiswaXII) * 100, 1) : 0 }}%</span>
                  </div>
                </div>
                <div class="progress" style="height: 8px;">
                  <div class="progress-bar bg-warning" id="kurang-akte-bar-xii" role="progressbar" style="width: {{ $totalSiswaXII > 0 ? ($ringkasanKurangXII['akte_kelahiran'] / $totalSiswaXII) * 100 : 0 }}%;" aria-valuenow="{{ $totalSiswaXII > 0 ? ($ringkasanKurangXII['akte_kelahiran'] / $totalSiswaXII) * 100 : 0 }}" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
              </div>
              <div class="mb-4">
                <div class="d-flex justify-content-between align-items-center mb-1">
                  <span class="fw-medium text-heading">Raport</span>
                  <div class="d-flex align-items-center">
                    <span class="text-muted me-2 small" id="kurang-raport-count-xii">{{ $ringkasanKurangXII['raport'] }} siswa</span>
                    <span class="fw-semibold text-info" id="kurang-raport-persen-xii">{{ $totalSiswaXII > 0 ? round(($ringkasanKurangXII['raport'] / $totalSiswaXII) * 100, 1) : 0 }}%</span>
                  </div>
                </div>
                <div class="progress" style="height: 8px;">
                  <div class="progress-bar bg-info" id="kurang-raport-bar-xii" role="progressbar" style="width: {{ $totalSiswaXII > 0 ? ($ringkasanKurangXII['raport'] / $totalSiswaXII) * 100 : 0 }}%;" aria-valuenow="{{ $totalSiswaXII > 0 ? ($ringkasanKurangXII['raport'] / $totalSiswaXII) * 100 : 0 }}" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
              </div>
              <div class="mb-0">
                <div class="d-flex justify-content-between align-items-center mb-1">
                  <span class="fw-medium text-heading">Kartu Keluarga</span>
                  <div class="d-flex align-items-center">
                    <span class="text-muted me-2 small" id="kurang-kk-count-xii">{{ $ringkasanKurangXII['kartu_keluarga'] }} siswa</span>
                    <span class="fw-semibold text-primary" id="kurang-kk-persen-xii">{{ $totalSiswaXII > 0 ? round(($ringkasanKurangXII['kartu_keluarga'] / $totalSiswaXII) * 100, 1) : 0 }}%</span>
                  </div>
                </div>
                <div class="progress" style="height: 8px;">
                  <div class="progress-bar bg-primary" id="kurang-kk-bar-xii" role="progressbar" style="width: {{ $totalSiswaXII > 0 ? ($ringkasanKurangXII['kartu_keluarga'] / $totalSiswaXII) * 100 : 0 }}%;" aria-valuenow="{{ $totalSiswaXII > 0 ? ($ringkasanKurangXII['kartu_keluarga'] / $totalSiswaXII) * 100 : 0 }}" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Row 3: Tabel Rekapitulasi Data -->
<div class="card">
  <div class="card-header border-bottom d-flex align-items-center justify-content-between">
    <div class="d-flex align-items-center">
      <div class="avatar avatar-sm me-3">
        <span class="avatar-initial rounded bg-label-info">
          <i class="icon-base ti tabler-list icon-lg"></i>
        </span>
      </div>
      <h5 class="card-title mb-0">Tabel Rekapitulasi Data Daftar Ulang</h5>
    </div>
    <span class="text-muted small">Terakhir diperbarui: Hari ini, {{ date('d F Y') }}</span>
  </div>
  <div class="table-responsive">
    <table class="table table-hover table-striped mb-0">
      <thead>
        <tr>
          <th>Kelas Target</th>
          <th class="text-center">Total Siswa</th>
          <th class="text-center text-success">Jumlah Lengkap</th>
          <th class="text-center text-danger">Jumlah Belum Lengkap</th>
          <th>Persentase Progress</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td><span class="fw-bold">Kelas XI</span></td>
          <td class="text-center" id="dash-table-xi-total">{{ $totalSiswaXI }}</td>
          <td class="text-center" id="dash-table-xi-lengkap">{{ $jumlahLengkapXI }}</td>
          <td class="text-center" id="dash-table-xi-belum">{{ $jumlahBelumLengkapXI }}</td>
          <td>
            <div class="d-flex align-items-center gap-3">
              <span class="fw-medium text-heading" id="dash-table-xi-persen">{{ $progressXI }}%</span>
              <div class="progress w-100" style="height: 6px;">
                <div class="progress-bar bg-primary" id="dash-table-xi-progress-bar" role="progressbar" style="width: {{ $progressXI }}%;" aria-valuenow="{{ $progressXI }}" aria-valuemin="0" aria-valuemax="100"></div>
              </div>
            </div>
          </td>
        </tr>
        <tr>
          <td><span class="fw-bold">Kelas XII</span></td>
          <td class="text-center" id="dash-table-xii-total">{{ $totalSiswaXII }}</td>
          <td class="text-center" id="dash-table-xii-lengkap">{{ $jumlahLengkapXII }}</td>
          <td class="text-center" id="dash-table-xii-belum">{{ $jumlahBelumLengkapXII }}</td>
          <td>
            <div class="d-flex align-items-center gap-3">
              <span class="fw-medium text-heading" id="dash-table-xii-persen">{{ $progressXII }}%</span>
              <div class="progress w-100" style="height: 6px;">
                <div class="progress-bar bg-success" id="dash-table-xii-progress-bar" role="progressbar" style="width: {{ $progressXII }}%;" aria-valuenow="{{ $progressXII }}" aria-valuemin="0" aria-valuemax="100"></div>
              </div>
            </div>
          </td>
        </tr>
      </tbody>
      <tfoot class="table-light">
        <tr class="fw-bold text-heading">
          <td>Total Keseluruhan</td>
          <td class="text-center" id="dash-total-siswa">{{ $totalSiswa }}</td>
          <td class="text-center" id="dash-total-lengkap">{{ $jumlahLengkap }}</td>
          <td class="text-center" id="dash-total-belum">{{ $jumlahBelumLengkap }}</td>
          <td>
            <div class="d-flex align-items-center gap-3">
              <span id="dash-total-persen">{{ $progressGlobal }}%</span>
              <div class="progress w-100" style="height: 8px;">
                <div class="progress-bar bg-info" id="dash-total-progress-bar" role="progressbar" style="width: {{ $progressGlobal }}%;" aria-valuenow="{{ $progressGlobal }}" aria-valuemin="0" aria-valuemax="100"></div>
              </div>
            </div>
          </td>
        </tr>
      </tfoot>
    </table>
  </div>
</div>
@endsection

@section('page-script')
@vite('resources/js/daftar-ulang-echo.js')

<script>
/**
 * Dashboard Real-Time Sync
 * Lapis 1: WebSocket via Laravel Echo (Reverb)
 * Lapis 2: Fallback polling AJAX setiap 30 detik jika Echo gagal
 */

var echoConnected = false;
var fallbackInterval = null;

// ============================================================
// FUNGSI UTAMA: Update semua elemen DOM dashboard
// ============================================================
function updateDashboardStats(stats) {
    // 1. Global Stats Cards
    var elTotal = document.getElementById('dash-stat-total');
    if (elTotal) elTotal.textContent = stats.total;

    var elLengkap = document.getElementById('dash-stat-lengkap');
    if (elLengkap) {
        elLengkap.textContent = stats.lengkap;
        elLengkap.style.color = '#28c76f';
        setTimeout(function() {
            elLengkap.style.transition = 'color 1s ease';
            elLengkap.style.color = '';
        }, 1200);
    }

    var elBelum = document.getElementById('dash-stat-belum');
    if (elBelum) elBelum.textContent = stats.belum;

    var elPersen = document.getElementById('dash-stat-persen');
    if (elPersen) elPersen.textContent = (stats.persen || 0) + '%';

    // 2. Kelas XI Card
    var xiText = document.getElementById('dash-xi-lengkap-text');
    if (xiText) xiText.textContent = (stats.lengkap_xi || 0) + ' dari ' + (stats.total_xi || 0) + ' Lengkap';

    var xiPersen = document.getElementById('dash-xi-persen');
    if (xiPersen) xiPersen.textContent = (stats.persen_xi || 0) + '%';

    var xiBar = document.getElementById('dash-xi-progress-bar');
    if (xiBar) {
        xiBar.style.width = (stats.persen_xi || 0) + '%';
        xiBar.setAttribute('aria-valuenow', stats.persen_xi || 0);
    }

    var xiLengkap = document.getElementById('dash-xi-lengkap');
    if (xiLengkap) xiLengkap.textContent = stats.lengkap_xi || 0;

    var xiBelum = document.getElementById('dash-xi-belum');
    if (xiBelum) xiBelum.textContent = stats.belum_xi || 0;

    // 3. Kelas XII Card
    var xiiText = document.getElementById('dash-xii-lengkap-text');
    if (xiiText) xiiText.textContent = (stats.lengkap_xii || 0) + ' dari ' + (stats.total_xii || 0) + ' Lengkap';

    var xiiPersen = document.getElementById('dash-xii-persen');
    if (xiiPersen) xiiPersen.textContent = (stats.persen_xii || 0) + '%';

    var xiiBar = document.getElementById('dash-xii-progress-bar');
    if (xiiBar) {
        xiiBar.style.width = (stats.persen_xii || 0) + '%';
        xiiBar.setAttribute('aria-valuenow', stats.persen_xii || 0);
    }

    var xiiLengkap = document.getElementById('dash-xii-lengkap');
    if (xiiLengkap) xiiLengkap.textContent = stats.lengkap_xii || 0;

    var xiiBelum = document.getElementById('dash-xii-belum');
    if (xiiBelum) xiiBelum.textContent = stats.belum_xii || 0;

    // 4. Tabel Row — Kelas XI
    var tXiTotal = document.getElementById('dash-table-xi-total');
    if (tXiTotal) tXiTotal.textContent = stats.total_xi || 0;

    var tXiLengkap = document.getElementById('dash-table-xi-lengkap');
    if (tXiLengkap) tXiLengkap.textContent = stats.lengkap_xi || 0;

    var tXiBelum = document.getElementById('dash-table-xi-belum');
    if (tXiBelum) tXiBelum.textContent = stats.belum_xi || 0;

    var tXiPersen = document.getElementById('dash-table-xi-persen');
    if (tXiPersen) tXiPersen.textContent = (stats.persen_xi || 0) + '%';

    var tXiBar = document.getElementById('dash-table-xi-progress-bar');
    if (tXiBar) {
        tXiBar.style.width = (stats.persen_xi || 0) + '%';
        tXiBar.setAttribute('aria-valuenow', stats.persen_xi || 0);
    }

    // 5. Tabel Row — Kelas XII
    var tXiiTotal = document.getElementById('dash-table-xii-total');
    if (tXiiTotal) tXiiTotal.textContent = stats.total_xii || 0;

    var tXiiLengkap = document.getElementById('dash-table-xii-lengkap');
    if (tXiiLengkap) tXiiLengkap.textContent = stats.lengkap_xii || 0;

    var tXiiBelum = document.getElementById('dash-table-xii-belum');
    if (tXiiBelum) tXiiBelum.textContent = stats.belum_xii || 0;

    var tXiiPersen = document.getElementById('dash-table-xii-persen');
    if (tXiiPersen) tXiiPersen.textContent = (stats.persen_xii || 0) + '%';

    var tXiiBar = document.getElementById('dash-table-xii-progress-bar');
    if (tXiiBar) {
        tXiiBar.style.width = (stats.persen_xii || 0) + '%';
        tXiiBar.setAttribute('aria-valuenow', stats.persen_xii || 0);
    }

    // 6. Tabel Footer — Total Keseluruhan
    var fTotal = document.getElementById('dash-total-siswa');
    if (fTotal) fTotal.textContent = stats.total || 0;

    var fLengkap = document.getElementById('dash-total-lengkap');
    if (fLengkap) fLengkap.textContent = stats.lengkap || 0;

    var fBelum = document.getElementById('dash-total-belum');
    if (fBelum) fBelum.textContent = stats.belum || 0;

    var fPersen = document.getElementById('dash-total-persen');
    if (fPersen) fPersen.textContent = (stats.persen || 0) + '%';

    var fBar = document.getElementById('dash-total-progress-bar');
    if (fBar) {
        fBar.style.width = (stats.persen || 0) + '%';
        fBar.setAttribute('aria-valuenow', stats.persen || 0);
    }

    // 7. Update Donut Charts (Global, XI, XII)
    updateChartData(kelompokChart, stats.statistik_kelompok);
    updateChartData(kelompokChartXI, stats.statistik_kelompok_xi);
    updateChartData(kelompokChartXII, stats.statistik_kelompok_xii);

    // 8. Update Ringkasan Berkas Kurang (Global, XI, XII)
    if (stats.ringkasan_kurang) {
        updateProgressBars('', stats.ringkasan_kurang, stats.total);
    }
    if (stats.ringkasan_kurang_xi) {
        updateProgressBars('-xi', stats.ringkasan_kurang_xi, stats.total_xi);
    }
    if (stats.ringkasan_kurang_xii) {
        updateProgressBars('-xii', stats.ringkasan_kurang_xii, stats.total_xii);
    }

    console.log('[Dashboard] Statistik diperbarui:', stats);
}

// ============================================================
// FUNGSI UTAMA: Helper Pembaruan UI Dinamis
// ============================================================
function updateChartData(chart, data) {
    if (chart && data) {
        chart.updateSeries([
            data.lengkap || 0,
            data.hampir_lengkap || 0,
            data.setengah_lengkap || 0,
            data.baru_memulai || 0,
            data.belum_kumpul || 0
        ]);
    }
}

function updateProgressBars(suffix, ringkasan, total) {
    var items = ['ijazah', 'akte_kelahiran', 'raport', 'kartu_keluarga'];
    var idMap = {
        'ijazah': 'ijazah',
        'akte_kelahiran': 'akte',
        'raport': 'raport',
        'kartu_keluarga': 'kk'
    };

    items.forEach(function(item) {
        var count = ringkasan[item] || 0;
        var persen = total > 0 ? Math.round((count / total) * 100 * 10) / 10 : 0;
        
        var countId = 'kurang-' + idMap[item] + '-count' + suffix;
        var persenId = 'kurang-' + idMap[item] + '-persen' + suffix;
        var barId = 'kurang-' + idMap[item] + '-bar' + suffix;

        var elCount = document.getElementById(countId);
        if (elCount) elCount.textContent = count + ' siswa';

        var elPersen = document.getElementById(persenId);
        if (elPersen) elPersen.textContent = persen + '%';

        var elBar = document.getElementById(barId);
        if (elBar) {
            elBar.style.width = persen + '%';
            elBar.setAttribute('aria-valuenow', persen);
        }
    });
}

// ============================================================
// INI: ApexCharts Donut Chart Kelompok Kelengkapan (Global, XI, XII)
// ============================================================
var kelompokChart;
var kelompokChartXI;
var kelompokChartXII;

document.addEventListener('DOMContentLoaded', function() {
    // Helper opsi chart ApexCharts
    function getChartOptions(seriesData) {
        return {
            chart: {
                height: 320,
                type: 'donut',
                parentHeightOffset: 0
            },
            labels: ['Lengkap', 'Hampir Lengkap', 'Setengah Lengkap', 'Baru Memulai', 'Belum Kumpul'],
            series: seriesData,
            colors: ['#28c76f', '#a855f7', '#ff9f43', '#00bad1', '#ea5455'], // Sukses, Ungu, Warning, Info, Danger
            stroke: {
                width: 5,
                colors: [document.documentElement.getAttribute('data-bs-theme') === 'dark' ? '#2f3349' : '#fff']
            },
            dataLabels: {
                enabled: true,
                formatter: function (val, opt) {
                    return parseInt(val) + '%';
                }
            },
            legend: {
                show: true,
                position: 'bottom',
                horizontalAlign: 'center',
                labels: {
                    colors: document.documentElement.getAttribute('data-bs-theme') === 'dark' ? '#b6bee3' : '#5d596c'
                }
            },
            plotOptions: {
                pie: {
                    donut: {
                        size: '70%',
                        labels: {
                            show: true,
                            value: {
                                show: true,
                                fontSize: '26px',
                                fontWeight: '600',
                                color: document.documentElement.getAttribute('data-bs-theme') === 'dark' ? '#b6bee3' : '#5d596c',
                                formatter: function (val) {
                                    return val + ' siswa';
                                }
                            },
                            total: {
                                show: true,
                                label: 'Total Siswa',
                                fontSize: '15px',
                                color: '#a5a3ae',
                                formatter: function (w) {
                                    return w.globals.seriesTotals.reduce(function (a, b) {
                                        return a + b;
                                    }, 0) + ' siswa';
                                }
                            }
                        }
                    }
                }
            },
            responsive: [
                {
                    breakpoint: 480,
                    options: {
                        chart: {
                            height: 350
                        },
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            ]
        };
    }

    // Inisialisasi Chart Global
    var chartElementGlobal = document.querySelector('#kelompokDonutChart');
    if (chartElementGlobal) {
        kelompokChart = new ApexCharts(chartElementGlobal, getChartOptions([
            {{ $statistikKelompok['lengkap'] }},
            {{ $statistikKelompok['hampir_lengkap'] }},
            {{ $statistikKelompok['setengah_lengkap'] }},
            {{ $statistikKelompok['baru_memulai'] }},
            {{ $statistikKelompok['belum_kumpul'] }}
        ]));
        kelompokChart.render();
    }

    // Inisialisasi Chart Kelas XI
    var chartElementXI = document.querySelector('#kelompokDonutChartXI');
    if (chartElementXI) {
        kelompokChartXI = new ApexCharts(chartElementXI, getChartOptions([
            {{ $statistikKelompokXI['lengkap'] }},
            {{ $statistikKelompokXI['hampir_lengkap'] }},
            {{ $statistikKelompokXI['setengah_lengkap'] }},
            {{ $statistikKelompokXI['baru_memulai'] }},
            {{ $statistikKelompokXI['belum_kumpul'] }}
        ]));
        kelompokChartXI.render();
    }

    // Inisialisasi Chart Kelas XII
    var chartElementXII = document.querySelector('#kelompokDonutChartXII');
    if (chartElementXII) {
        kelompokChartXII = new ApexCharts(chartElementXII, getChartOptions([
            {{ $statistikKelompokXII['lengkap'] }},
            {{ $statistikKelompokXII['hampir_lengkap'] }},
            {{ $statistikKelompokXII['setengah_lengkap'] }},
            {{ $statistikKelompokXII['baru_memulai'] }},
            {{ $statistikKelompokXII['belum_kumpul'] }}
        ]));
        kelompokChartXII.render();
    }

    // Trigger resize event saat berpindah tab untuk memicu reflow render ApexCharts yang mulus
    document.querySelectorAll('button[data-bs-toggle="tab"]').forEach(function(tabEl) {
        tabEl.addEventListener('shown.bs.tab', function () {
            window.dispatchEvent(new Event('resize'));
        });
    });
});

// ============================================================
// LAPIS 2: Fallback AJAX — fetch stats dari API setiap 30 detik
// ============================================================
function startFallbackPolling() {
    if (fallbackInterval) return; // sudah jalan

    console.log('[Dashboard] Memulai fallback polling setiap 30 detik...');
    fallbackInterval = setInterval(function() {
        if (echoConnected) {
            // Jika Echo sudah konek, tidak perlu polling
            return;
        }
        fetch('/admin/daftar-ulang/stats', {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(function(res) { return res.json(); })
        .then(function(data) {
            if (data.success && data.stats) {
                updateDashboardStats(data.stats);
            }
        })
        .catch(function(err) {
            console.warn('[Dashboard] Fallback polling gagal:', err);
        });
    }, 30000); // 30 detik
}

// ============================================================
// LAPIS 1: WebSocket via Echo — Inisialisasi setelah halaman load
// ============================================================
window.addEventListener('load', function() {
    // Tunggu sampai window.daftarUlangEcho siap (retry tiap 500ms)
    var echoRetryCount = 0;
    var maxRetry = 20; // maksimal 10 detik (20 x 500ms)

    function tryConnectEcho() {
        echoRetryCount++;

        if (echoRetryCount > maxRetry) {
            console.warn('[Dashboard] Echo tidak berhasil diinisialisasi setelah ' + maxRetry + ' percobaan. Fallback polling aktif.');
            startFallbackPolling();
            return;
        }

        if (typeof window.daftarUlangEcho === 'undefined' || typeof window.daftarUlangEcho.init !== 'function') {
            console.log('[Dashboard] Menunggu modul Echo... percobaan ke-' + echoRetryCount);
            setTimeout(tryConnectEcho, 500);
            return;
        }

        // Modul siap — inisialisasi listener
        window.daftarUlangEcho.init({
            onChecklistUpdated: function(payload) {
                echoConnected = true;
                console.log('[Dashboard] Event diterima via WebSocket:', payload);

                if (payload.siswa_id === null) {
                    // Reset global — muat ulang semua stats
                    fetch('/admin/daftar-ulang/stats', {
                        method: 'GET',
                        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                    })
                    .then(function(r) { return r.json(); })
                    .then(function(d) { if (d.success && d.stats) updateDashboardStats(d.stats); })
                    .catch(function() { location.reload(); });
                    return;
                }

                if (payload.stats) {
                    updateDashboardStats(payload.stats);
                }
            },

            onStatsUpdated: function(stats) {
                echoConnected = true;
                updateDashboardStats(stats);
            }
        });

        console.log('[Dashboard] Echo listener berhasil diinisialisasi!');

        // Jika setelah 5 detik Echo belum pernah menerima event, aktifkan fallback
        setTimeout(function() {
            if (!echoConnected) {
                console.warn('[Dashboard] Echo terhubung tapi belum ada event. Fallback polling standby...');
                startFallbackPolling();
            }
        }, 5000);
    }

    tryConnectEcho();
});
</script>
@endsection
