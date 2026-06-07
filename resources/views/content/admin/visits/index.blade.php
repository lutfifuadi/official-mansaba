@php
$configData = Helper::appClasses();
@endphp

@extends('layouts/contentNavbarLayout')

@section('title', 'Statistik Kunjungan')

@section('vendor-style')
@vite(['resources/assets/vendor/libs/apex-charts/apex-charts.scss', 'resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss'])
@endsection

@section('vendor-script')
@vite(['resources/assets/vendor/libs/apex-charts/apexcharts.js', 'resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js'])
@endsection

@section('content')
@php
$periods = ['today' => 'Hari Ini', '7days' => '7 Hari', '30days' => '30 Hari', '90days' => '90 Hari', '1year' => '1 Tahun'];
@endphp

<div class="row g-6 mb-6">
  <div class="col-12">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
      <div>
        <h4 class="mb-1">Statistik Kunjungan Website</h4>
        <p class="text-muted mb-0">Pantau traffic pengunjung website MAN 1 Kota Bandung</p>
      </div>
      <div class="d-flex gap-2 align-items-center">
        <div class="btn-group">
          @foreach($periods as $key => $label)
            <a href="{{ request()->fullUrlWithQuery(['period' => $key]) }}"
               class="btn btn-sm {{ $period === $key ? 'btn-primary' : 'btn-outline-primary' }}">
              {{ $label }}
            </a>
          @endforeach
        </div>
        <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#resetModal">
          <i class="icon-base ti tabler-trash me-1"></i> Reset Data
        </button>
      </div>
    </div>
  </div>
</div>

<div class="row g-6 mb-6">
  <div class="col-sm-6 col-xl-3">
    <div class="card h-100">
      <div class="card-body">
        <div class="d-flex align-items-start justify-content-between">
          <div>
            <span class="fw-medium d-block mb-1">Kunjungan Hari Ini</span>
            <h3 class="mb-0 me-2">{{ number_format($todayVisits) }}</h3>
            <small class="text-body-secondary">{{ number_format($todayUnique) }} pengunjung unik</small>
          </div>
          <div class="avatar"><span class="avatar-initial rounded bg-label-primary"><i class="icon-base ti tabler-users icon-lg"></i></span></div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-sm-6 col-xl-3">
    <div class="card h-100">
      <div class="card-body">
        <div class="d-flex align-items-start justify-content-between">
          <div>
            <span class="fw-medium d-block mb-1">Periode Ini</span>
            <h3 class="mb-0 me-2">{{ number_format($totalVisits) }}</h3>
            <small class="text-body-secondary">{{ number_format($uniqueVisitors) }} pengunjung unik</small>
          </div>
          <div class="avatar"><span class="avatar-initial rounded bg-label-success"><i class="icon-base ti tabler-trending-up icon-lg"></i></span></div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-sm-6 col-xl-3">
    <div class="card h-100">
      <div class="card-body">
        <div class="d-flex align-items-start justify-content-between">
          <div>
            <span class="fw-medium d-block mb-1">Rata-rata Harian</span>
            <h3 class="mb-0 me-2">{{ number_format($avgDaily, 1) }}</h3>
            <small class="text-body-secondary">kunjungan/hari</small>
          </div>
          <div class="avatar"><span class="avatar-initial rounded bg-label-info"><i class="icon-base ti tabler-chart-bar icon-lg"></i></span></div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-sm-6 col-xl-3">
    <div class="card h-100">
      <div class="card-body">
        <div class="d-flex align-items-start justify-content-between">
          <div>
            <span class="fw-medium d-block mb-1">Total (Semua Waktu)</span>
            <h3 class="mb-0 me-2">{{ number_format($totalAllTime) }}</h3>
            <small class="text-body-secondary">{{ number_format($uniqueAllTime) }} pengunjung unik</small>
          </div>
          <div class="avatar"><span class="avatar-initial rounded bg-label-warning"><i class="icon-base ti tabler-globe icon-lg"></i></span></div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row g-6 mb-6">
  <div class="col-12">
    <div class="card">
      <div class="card-header d-flex justify-content-between">
        <h5 class="card-title mb-0">Tren Kunjungan Harian</h5>
        @if($peakDay)
          <small class="text-muted">Puncak: {{ \Carbon\Carbon::parse($peakDay->date)->format('d M Y') }} ({{ number_format($peakDay->total) }} kunjungan)</small>
        @endif
      </div>
      <div class="card-body">
        <div id="dailyChart"></div>
      </div>
    </div>
  </div>
</div>

<div class="row g-6 mb-6">
  <div class="col-xl-4">
    <div class="card h-100">
      <div class="card-header"><h5 class="card-title mb-0">Kunjungan per Halaman</h5></div>
      <div class="card-body"><div id="pageTypeChart"></div></div>
    </div>
  </div>
  <div class="col-xl-4">
    <div class="card h-100">
      <div class="card-header"><h5 class="card-title mb-0">Perangkat</h5></div>
      <div class="card-body"><div id="deviceChart"></div></div>
    </div>
  </div>
  <div class="col-xl-4">
    <div class="card h-100">
      <div class="card-header"><h5 class="card-title mb-0">Browser</h5></div>
      <div class="card-body"><div id="browserChart"></div></div>
    </div>
  </div>
</div>

<div class="row g-6 mb-6">
  <div class="col-xl-6">
    <div class="card h-100">
      <div class="card-header"><h5 class="card-title mb-0">Jam Kunjungan</h5></div>
      <div class="card-body"><div id="hourlyChart"></div></div>
    </div>
  </div>
  <div class="col-xl-6">
    <div class="card h-100">
      <div class="card-header"><h5 class="card-title mb-0">Sistem Operasi</h5></div>
      <div class="card-body"><div id="platformChart"></div></div>
    </div>
  </div>
</div>

<div class="row g-6 mb-6">
  <div class="col-xl-7">
    <div class="card h-100">
      <div class="card-header"><h5 class="card-title mb-0">Halaman Terpopuler</h5></div>
      <div class="table-responsive">
        <table class="table table-hover">
          <thead class="border-top"><tr><th>#</th><th>URL</th><th>Kunjungan</th><th>%</th></tr></thead>
          <tbody>
            @forelse($mostVisitedPages as $i => $page)
              <tr>
                <td>{{ $i + 1 }}</td>
                <td class="text-truncate" style="max-width:350px">
                  @php $pageLabels = ['/' => 'Beranda', '/berita' => 'Berita', '/galeri' => 'Galeri', '/prestasi' => 'Prestasi', '/ekstrakurikuler' => 'Ekstrakurikuler', '/profil' => 'Profil']; @endphp
                  <span title="{{ $page->url }}">{{ $pageLabels[$page->url] ?? $page->url }}</span>
                </td>
                <td><strong>{{ number_format($page->total) }}</strong></td>
                <td>{{ $totalVisits > 0 ? round($page->total / $totalVisits * 100, 1) : 0 }}%</td>
              </tr>
            @empty
              <tr><td colspan="4" class="text-center text-muted py-4">Belum ada data kunjungan</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <div class="col-xl-5">
    <div class="card h-100">
      <div class="card-header"><h5 class="card-title mb-0">Referer</h5></div>
      <div class="table-responsive">
        <table class="table table-hover">
          <thead class="border-top"><tr><th>#</th><th>Sumber</th><th>Kunjungan</th></tr></thead>
          <tbody>
            @forelse($refererData as $i => $ref)
              <tr>
                <td>{{ $i + 1 }}</td>
                <td class="text-truncate" style="max-width:300px">{{ $ref->referer_url }}</td>
                <td><strong>{{ number_format($ref->total) }}</strong></td>
              </tr>
            @empty
              <tr><td colspan="3" class="text-center text-muted py-4">Belum ada data referer</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<div class="row g-6">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h5 class="card-title mb-0">Kunjungan Terbaru</h5>
      </div>
      <div class="table-responsive">
        <table class="table table-hover">
          <thead class="border-top"><tr><th>Waktu</th><th>Halaman</th><th>Perangkat</th><th>Browser</th><th>OS</th><th>IP</th></tr></thead>
          <tbody>
            @forelse($recentVisits as $v)
              <tr>
                <td><small>{{ $v->visited_at->format('d M H:i') }}</small></td>
                <td class="text-truncate" style="max-width:200px"><small>{{ $v->url }}</small></td>
                <td><span class="badge bg-label-{{ $v->device_type === 'mobile' ? 'info' : ($v->device_type === 'tablet' ? 'warning' : 'secondary') }}">{{ $v->device_type ?? '-' }}</span></td>
                <td><small>{{ $v->browser ?? '-' }}</small></td>
                <td><small>{{ $v->platform ?? '-' }}</small></td>
                <td><small class="text-muted">{{ $v->ip_address }}</small></td>
              </tr>
            @empty
              <tr><td colspan="6" class="text-center text-muted py-4">Belum ada kunjungan tercatat</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
      <div class="card-footer border-top d-flex justify-content-center py-3">
        {{ $recentVisits->onEachSide(1)->links() }}
      </div>
    </div>
  </div>
</div>
@endsection

@section('page-script')
<script>
  document.addEventListener('DOMContentLoaded', function() {
    var isDark = document.documentElement.getAttribute('data-bs-theme') === 'dark';
    var textColor = isDark ? '#aab3c3' : '#6b7886';
    var headingColor = isDark ? '#d3d7de' : '#384551';

    @if($dailyVisits->count() > 0)
    new ApexCharts(document.querySelector('#dailyChart'), {
      chart: { type: 'area', height: 350, toolbar: { show: false } },
      series: [
        { name: 'Kunjungan', data: [{{ $dailyVisits->pluck('total')->implode(',') }}] },
        { name: 'Unik', data: [{{ $dailyVisits->pluck('unique_count')->implode(',') }}] }
      ],
      xaxis: {
        categories: [@foreach($dailyVisits as $d) '{{ \Carbon\Carbon::parse($d->date)->format('d M') }}', @endforeach],
        labels: { style: { colors: textColor } }
      },
      yaxis: { labels: { style: { colors: textColor } } },
      colors: ['#7367f0', '#28c76f'],
      stroke: { curve: 'smooth', width: 2 },
      fill: { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.45, opacityTo: 0.05 } },
      dataLabels: { enabled: false },
      legend: { labels: { colors: headingColor } },
      grid: { borderColor: isDark ? '#404854' : '#eaecf0' }
    }).render();
    @endif

    @if($visitsByPageType->count() > 0)
    new ApexCharts(document.querySelector('#pageTypeChart'), {
      chart: { type: 'donut', height: 280 },
      series: [{{ $visitsByPageType->values()->implode(',') }}],
      labels: [@foreach($visitsByPageType as $key => $val) @php
        $labels = ['home' => 'Beranda', 'news' => 'Berita', 'news-detail' => 'Detail Berita', 'gallery' => 'Galeri', 'achievement' => 'Prestasi', 'extracurricular' => 'Ekskul', 'extracurricular-detail' => 'Detail Ekskul', 'profile' => 'Profil', 'other' => 'Lainnya'];
      @endphp '{{ $labels[$key] ?? $key }}', @endforeach],
      colors: ['#7367f0', '#28c76f', '#ea5455', '#ff9f43', '#00cfe8', '#4b4b4b', '#a8aaae'],
      dataLabels: { enabled: true, formatter: function(v) { return Math.round(v) + '%' } },
      legend: { position: 'bottom', labels: { colors: headingColor } },
      plotOptions: { pie: { donut: { size: '75%' } } }
    }).render();
    @endif

    @if($deviceBreakdown->count() > 0)
    new ApexCharts(document.querySelector('#deviceChart'), {
      chart: { type: 'donut', height: 280 },
      series: [{{ $deviceBreakdown->values()->implode(',') }}],
      labels: [@foreach($deviceBreakdown as $key => $val) '{{ ucfirst($key) }}', @endforeach],
      colors: ['#7367f0', '#28c76f', '#ff9f43'],
      dataLabels: { enabled: true, formatter: function(v) { return Math.round(v) + '%' } },
      legend: { position: 'bottom', labels: { colors: headingColor } },
      plotOptions: { pie: { donut: { size: '75%' } } }
    }).render();
    @endif

    @if($browserBreakdown->count() > 0)
    new ApexCharts(document.querySelector('#browserChart'), {
      chart: { type: 'bar', height: 280, toolbar: { show: false } },
      series: [{ name: 'Kunjungan', data: [{{ $browserBreakdown->values()->implode(',') }}] }],
      xaxis: { categories: [@foreach($browserBreakdown as $key => $val) '{{ $key }}', @endforeach], labels: { style: { colors: textColor } } },
      yaxis: { labels: { style: { colors: textColor } } },
      colors: ['#7367f0'],
      grid: { borderColor: isDark ? '#404854' : '#eaecf0' },
      plotOptions: { bar: { borderRadius: 4, horizontal: true } }
    }).render();
    @endif

    @if($hourlyTraffic->count() > 0)
    new ApexCharts(document.querySelector('#hourlyChart'), {
      chart: { type: 'bar', height: 280, toolbar: { show: false } },
      series: [{ name: 'Kunjungan', data: [@for($h = 0; $h < 24; $h++){{ $hourlyTraffic[$h] ?? 0 }}, @endfor] }],
      xaxis: { categories: [@for($h = 0; $h < 24; $h++)'{{ sprintf("%02d:00", $h) }}', @endfor], labels: { style: { colors: textColor }, rotate: -45 } },
      yaxis: { labels: { style: { colors: textColor } } },
      colors: ['#00cfe8'],
      grid: { borderColor: isDark ? '#404854' : '#eaecf0' },
      plotOptions: { bar: { borderRadius: 2 } }
    }).render();
    @endif

    @if($platformBreakdown->count() > 0)
    new ApexCharts(document.querySelector('#platformChart'), {
      chart: { type: 'bar', height: 280, toolbar: { show: false } },
      series: [{ name: 'Kunjungan', data: [{{ $platformBreakdown->values()->implode(',') }}] }],
      xaxis: { categories: [@foreach($platformBreakdown as $key => $val) '{{ $key }}', @endforeach], labels: { style: { colors: textColor } } },
      yaxis: { labels: { style: { colors: textColor } } },
      colors: ['#ea5455'],
      grid: { borderColor: isDark ? '#404854' : '#eaecf0' },
      plotOptions: { bar: { borderRadius: 4, horizontal: true } }
    }).render();
    @endif
  });
</script>
@endsection

{{-- Modal Reset --}}
<div class="modal fade" id="resetModal" tabindex="-1" aria-hidden="true" style="display:none">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header border-0 pb-0">
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body text-center pt-3 pb-4">
        <div class="avatar avatar-lg mb-3">
          <span class="avatar-initial rounded bg-label-danger">
            <i class="icon-base ti tabler-alert-triangle icon-lg"></i>
          </span>
        </div>
        <h5 class="mb-2">Hapus Semua Data Statistik?</h5>
        <p class="text-muted mb-0">
          Tindakan ini akan menghapus <strong>seluruh</strong> data kunjungan website secara permanen.
          <br>Data yang sudah dihapus <strong>tidak dapat dikembalikan</strong>.
        </p>
        <div id="resetSuccess" hidden class="mt-3">
          <div class="alert alert-success mb-0 d-flex align-items-center gap-2 justify-content-center">
            <i class="icon-base ti tabler-check"></i> Semua data statistik berhasil direset!
          </div>
        </div>
      </div>
      <div class="modal-footer border-0 pt-0 justify-content-center gap-2" id="resetFooter">
        <button type="button" class="btn btn-label-secondary px-4" data-bs-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-danger px-4" id="resetBtn" onclick="resetStats()">
          <i class="icon-base ti tabler-trash me-1"></i> Ya, Reset Semua
        </button>
      </div>
    </div>
  </div>
</div>

<script>
function resetStats() {
  var btn = document.getElementById('resetBtn');
  btn.disabled = true;
  btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Mereset...';

  fetch('{{ route('admin.visits.reset') }}', {
    method: 'DELETE',
    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
  })
  .then(function(r) { return r.json(); })
  .then(function(data) {
    if (data.success) {
      document.getElementById('resetSuccess').removeAttribute('hidden');
      document.getElementById('resetFooter').setAttribute('hidden', '');
      setTimeout(function() {
        location.reload();
      }, 1500);
    }
  })
  .catch(function() {
    btn.disabled = false;
    btn.innerHTML = '<i class="icon-base ti tabler-trash me-1"></i> Ya, Reset Semua';
  });
}
</script>
