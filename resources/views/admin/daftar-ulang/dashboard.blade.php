@extends('layouts/contentNavbarLayout')

@section('title', 'Dashboard Progress Daftar Ulang')

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
              <h3 class="mb-0 me-2">{{ $totalSiswa }}</h3>
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
              <h3 class="mb-0 me-2 text-success">{{ $jumlahLengkap }}</h3>
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
              <h3 class="mb-0 me-2 text-danger">{{ $jumlahBelumLengkap }}</h3>
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
              <h3 class="mb-0 me-2">{{ $progressGlobal }}%</h3>
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
              <span class="fw-medium text-heading">{{ $jumlahLengkapXI }} dari {{ $totalSiswaXI }} Lengkap</span>
              <span class="fw-semibold text-primary">{{ $progressXI }}%</span>
            </div>
            <div class="progress" style="height: 10px;">
              <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $progressXI }}%;" aria-valuenow="{{ $progressXI }}" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
          </div>
        </div>
        <div class="row text-center g-4">
          <div class="col-6 border-end">
            <p class="mb-1 text-muted">Lengkap</p>
            <h4 class="mb-0 text-success">{{ $jumlahLengkapXI }}</h4>
          </div>
          <div class="col-6">
            <p class="mb-1 text-muted">Belum Lengkap</p>
            <h4 class="mb-0 text-danger">{{ $jumlahBelumLengkapXI }}</h4>
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
              <span class="fw-medium text-heading">{{ $jumlahLengkapXII }} dari {{ $totalSiswaXII }} Lengkap</span>
              <span class="fw-semibold text-success">{{ $progressXII }}%</span>
            </div>
            <div class="progress" style="height: 10px;">
              <div class="progress-bar bg-success" role="progressbar" style="width: {{ $progressXII }}%;" aria-valuenow="{{ $progressXII }}" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
          </div>
        </div>
        <div class="row text-center g-4">
          <div class="col-6 border-end">
            <p class="mb-1 text-muted">Lengkap</p>
            <h4 class="mb-0 text-success">{{ $jumlahLengkapXII }}</h4>
          </div>
          <div class="col-6">
            <p class="mb-1 text-muted">Belum Lengkap</p>
            <h4 class="mb-0 text-danger">{{ $jumlahBelumLengkapXII }}</h4>
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
          <td class="text-center">{{ $totalSiswaXI }}</td>
          <td class="text-center">{{ $jumlahLengkapXI }}</td>
          <td class="text-center">{{ $jumlahBelumLengkapXI }}</td>
          <td>
            <div class="d-flex align-items-center gap-3">
              <span class="fw-medium text-heading">{{ $progressXI }}%</span>
              <div class="progress w-100" style="height: 6px;">
                <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $progressXI }}%;" aria-valuenow="{{ $progressXI }}" aria-valuemin="0" aria-valuemax="100"></div>
              </div>
            </div>
          </td>
        </tr>
        <tr>
          <td><span class="fw-bold">Kelas XII</span></td>
          <td class="text-center">{{ $totalSiswaXII }}</td>
          <td class="text-center">{{ $jumlahLengkapXII }}</td>
          <td class="text-center">{{ $jumlahBelumLengkapXII }}</td>
          <td>
            <div class="d-flex align-items-center gap-3">
              <span class="fw-medium text-heading">{{ $progressXII }}%</span>
              <div class="progress w-100" style="height: 6px;">
                <div class="progress-bar bg-success" role="progressbar" style="width: {{ $progressXII }}%;" aria-valuenow="{{ $progressXII }}" aria-valuemin="0" aria-valuemax="100"></div>
              </div>
            </div>
          </td>
        </tr>
      </tbody>
      <tfoot class="table-light">
        <tr class="fw-bold text-heading">
          <td>Total Keseluruhan</td>
          <td class="text-center">{{ $totalSiswa }}</td>
          <td class="text-center">{{ $jumlahLengkap }}</td>
          <td class="text-center">{{ $jumlahBelumLengkap }}</td>
          <td>
            <div class="d-flex align-items-center gap-3">
              <span>{{ $progressGlobal }}%</span>
              <div class="progress w-100" style="height: 8px;">
                <div class="progress-bar bg-info" role="progressbar" style="width: {{ $progressGlobal }}%;" aria-valuenow="{{ $progressGlobal }}" aria-valuemin="0" aria-valuemax="100"></div>
              </div>
            </div>
          </td>
        </tr>
      </tfoot>
    </table>
  </div>
</div>
@endsection
