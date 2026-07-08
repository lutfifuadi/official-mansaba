@extends('layouts/contentNavbarLayout')

@section('title', 'Verifikasi Checklist Daftar Ulang')

@section('content')
<!-- SweetAlert2 CSS & JS CDN -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Header Page / Judul Halaman -->
<div class="d-flex justify-content-between align-items-md-center flex-column flex-md-row mb-5 gap-4">
  <div>
    <h4 class="mb-1">Verifikasi Dokumen Daftar Ulang</h4>
    <p class="text-muted mb-0" id="periode-aktif-text">
      @if($kelas === 'XI' && $periodeXI)
        Periode Aktif: {{ $periodeXI->tanggal_buka->format('d M Y') }} s/d {{ $periodeXI->tanggal_tutup->format('d M Y') }}
      @elseif($kelas === 'XII' && $periodeXII)
        Periode Aktif: {{ $periodeXII->tanggal_buka->format('d M Y') }} s/d {{ $periodeXII->tanggal_tutup->format('d M Y') }}
      @else
        Periode Aktif: Tidak Ada Periode Aktif
      @endif
    </p>
  </div>
  @if(auth()->user() && in_array(auth()->user()->role, ['super_admin', 'admin', 'operator']))
    <button type="button" class="btn btn-label-danger" id="btn-reset-data">
      <i class="icon-base ti tabler-trash me-1"></i> Reset Data
    </button>
  @endif
</div>

<div class="row g-5 mb-4">
  <!-- Statistik -->
  <div class="col-sm-6 col-xl-3">
    <div class="card h-100">
      <div class="card-body pb-3 pt-3">
        <div class="d-flex align-items-start justify-content-between">
          <div class="content-left">
            <span class="text-heading fw-medium d-block mb-0">Total Siswa</span>
            <div class="d-flex align-items-end mt-1">
              <h3 class="mb-0 me-2" id="stat-total">{{ $totalSiswa }}</h3>
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
            <span class="text-heading fw-medium d-block mb-0">Lengkap</span>
            <div class="d-flex align-items-end mt-1">
              <h3 class="mb-0 me-2 text-success" id="stat-lengkap">{{ $jumlahLengkap }}</h3>
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
              <h3 class="mb-0 me-2 text-danger" id="stat-belum">{{ $jumlahBelumLengkap }}</h3>
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
            <span class="text-heading fw-medium d-block mb-0">Progress</span>
            <div class="d-flex align-items-end mt-1">
              <h3 class="mb-0 me-2" id="stat-persen">{{ $progressPersen }}%</h3>
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

<!-- Warning Periode Jika Tidak Aktif / Aktif -->
<div class="alert alert-warning alert-dismissible d-none" role="alert" id="periode-warning-alert">
  <div class="d-flex align-items-center">
    <i class="icon-base ti tabler-alert-triangle me-2 icon-lg"></i>
    <div>
      <strong>Perhatian!</strong> Periode Daftar Ulang saat ini <strong>Tidak Aktif</strong>. Perubahan checklist tidak dapat disimpan ke server. Silakan hubungi Super Admin untuk mengaktifkan periode.
    </div>
  </div>
</div>

<div class="card">
  <div class="card-header border-bottom">
    <form action="{{ route('admin.daftar-ulang.index') }}" method="GET" id="filter-form">
      <input type="hidden" name="kelas" value="{{ $kelas }}">
      <div class="row g-3">
        <div class="col-12 col-md-4 col-lg-3">
          <div class="input-group input-group-merge">
            <span class="input-group-text"><i class="icon-base ti tabler-search"></i></span>
            <input type="text" name="search" class="form-control" placeholder="Cari Nama atau NIS..." id="siswa-search" value="{{ request('search') }}">
          </div>
        </div>
        <div class="col-12 col-md-4 col-lg-2">
          <select name="status" class="form-select" id="status-filter">
            <option value="">Semua Status</option>
            <option value="lengkap" {{ request('status') === 'lengkap' ? 'selected' : '' }}>Lengkap</option>
            <option value="belum_lengkap" {{ request('status') === 'belum_lengkap' ? 'selected' : '' }}>Belum Lengkap</option>
          </select>
        </div>
        <div class="col-12 col-md-4 col-lg-3">
          <select name="kelompok" class="form-select" id="kelompok-filter">
            <option value="">Semua Kelompok</option>
            <option value="lengkap" {{ request('kelompok') === 'lengkap' ? 'selected' : '' }}>✅ Lengkap (4/4)</option>
            <option value="hampir_lengkap" {{ request('kelompok') === 'hampir_lengkap' ? 'selected' : '' }}>🟢 Hampir Lengkap (3/4)</option>
            <option value="setengah_lengkap" {{ request('kelompok') === 'setengah_lengkap' ? 'selected' : '' }}>🟡 Setengah Lengkap (2/4)</option>
            <option value="baru_memulai" {{ request('kelompok') === 'baru_memulai' ? 'selected' : '' }}>🟠 Baru Memulai (1/4)</option>
            <option value="belum_kumpul" {{ request('kelompok') === 'belum_kumpul' ? 'selected' : '' }}>🔴 Belum Kumpul (0/4)</option>
          </select>
        </div>
        <div class="col-12 col-md-9 col-lg-3">
          <select name="kurang_berkas" class="form-select" id="kurang-berkas-filter">
            <option value="">Semua Berkas Kurang</option>
            <option value="raport" {{ request('kurang_berkas') === 'raport' ? 'selected' : '' }}>📚 Kurang Raport</option>
            <option value="kartu_keluarga" {{ request('kurang_berkas') === 'kartu_keluarga' ? 'selected' : '' }}>👨‍👩‍👧‍👦 Kurang KK</option>
            <option value="akte_kelahiran" {{ request('kurang_berkas') === 'akte_kelahiran' ? 'selected' : '' }}>👶 Kurang Akte</option>
            <option value="ijazah" {{ request('kurang_berkas') === 'ijazah' ? 'selected' : '' }}>📄 Kurang Ijazah</option>
          </select>
        </div>
        <div class="col-12 col-md-3 col-lg-1">
          <button type="submit" class="btn btn-primary w-100">Cari</button>
        </div>
      </div>
    </form>
  </div>

  @if(auth()->user() && in_array(auth()->user()->role, ['super_admin', 'admin', 'operator']))
    <form id="reset-form" action="{{ route('admin.daftar-ulang.reset') }}" method="POST" class="d-none">
      @csrf
    </form>
  @endif

  <!-- Tab Kelas -->
  <div class="nav-align-top">
    <ul class="nav nav-tabs" role="tablist">
      <li class="nav-item">
        <a href="{{ route('admin.daftar-ulang.index', array_merge(request()->except('page'), ['kelas' => 'XI'])) }}" class="nav-link tab-kelas {{ $kelas === 'XI' ? 'active' : '' }}" data-kelas="XI">
          <i class="icon-base ti tabler-school me-1_5"></i> Kelas XI
        </a>
      </li>
      <li class="nav-item">
        <a href="{{ route('admin.daftar-ulang.index', array_merge(request()->except('page'), ['kelas' => 'XII'])) }}" class="nav-link tab-kelas {{ $kelas === 'XII' ? 'active' : '' }}" data-kelas="XII">
          <i class="icon-base ti tabler-school me-1_5"></i> Kelas XII
        </a>
      </li>
    </ul>
    
    <div class="tab-content p-0">
      <!-- Tab Aktif -->
      <div class="tab-pane fade show active" role="tabpanel">
        <div class="table-responsive">
          <table class="table table-hover mb-0">
            <thead>
              <tr>
                <th width="50">No</th>
                <th>NIS</th>
                <th>Nama Lengkap</th>
                <th class="text-center">Raport</th>
                <th class="text-center">KK</th>
                <th class="text-center">Akte</th>
                <th class="text-center">Ijazah</th>
                <th class="text-center">Status</th>
                <th>Verifikator</th>
                <th>Tgl Verifikasi</th>
                <th class="text-center">Kelompok</th>
                <th>Kekurangan Berkas</th>
              </tr>
            </thead>
            <tbody id="table-body" data-periode-active="{{ ($kelas === 'XI' ? ($periodeXI && today()->between($periodeXI->tanggal_buka, $periodeXI->tanggal_tutup) && $periodeXI->is_active) : ($periodeXII && today()->between($periodeXII->tanggal_buka, $periodeXII->tanggal_tutup) && $periodeXII->is_active)) ? 'true' : 'false' }}">
              @forelse($siswas as $index => $siswa)
                @php
                  $checklist = $siswa->checklist;
                  $isLengkap = $checklist && $checklist->raport && $checklist->kartu_keluarga && $checklist->akte_kelahiran && $checklist->ijazah;

                  // Data segmentasi kelompok
                  $kelompok = $checklist ? $checklist->nama_kelompok : 'Belum Kumpul';
                  $kurangItems = $checklist ? $checklist->kurang_item : ['Raport', 'Kartu Keluarga', 'Akte Kelahiran', 'Ijazah'];
                  $kelompokBadgeMap = [
                    'Lengkap' => 'bg-label-success',
                    'Hampir Lengkap' => 'bg-label-primary',
                    'Setengah Lengkap' => 'bg-label-warning',
                    'Baru Memulai' => 'bg-label-info',
                    'Belum Kumpul' => 'bg-label-danger',
                  ];
                  $kelompokBadge = $kelompokBadgeMap[$kelompok] ?? 'bg-label-secondary';
                @endphp
                <tr data-siswa-id="{{ $siswa->id }}" data-kelas="{{ $siswa->kelas_tujuan }}" data-nama="{{ $siswa->nama_lengkap }}" data-nis="{{ $siswa->nis }}" data-status="{{ $checklist->status ?? 'belum_lengkap' }}">
                  <td>{{ $siswas->firstItem() + $index }}</td>
                  <td>{{ $siswa->nis }}</td>
                  <td class="text-nowrap">
                    <span class="fw-semibold text-heading text-nowrap">{{ $siswa->nama_lengkap }}</span>
                    <div class="text-muted small">Kelas: {{ $siswa->kelas_tujuan }} (Asal: {{ $siswa->kelas_asal }})</div>
                  </td>
                  <td class="text-center">
                    <input class="form-check-input checklist-cb" type="checkbox" data-doc="raport" {{ $checklist && $checklist->raport ? 'checked' : '' }}>
                  </td>
                  <td class="text-center">
                    <input class="form-check-input checklist-cb" type="checkbox" data-doc="kk" {{ $checklist && $checklist->kartu_keluarga ? 'checked' : '' }}>
                  </td>
                  <td class="text-center">
                    <input class="form-check-input checklist-cb" type="checkbox" data-doc="akte" {{ $checklist && $checklist->akte_kelahiran ? 'checked' : '' }}>
                  </td>
                  <td class="text-center">
                    <input class="form-check-input checklist-cb" type="checkbox" data-doc="ijazah" {{ $checklist && $checklist->ijazah ? 'checked' : '' }}>
                  </td>
                  <td class="text-center status-badge-cell">
                    @if($isLengkap)
                      <span class="badge bg-label-success">Lengkap</span>
                    @else
                      <span class="badge bg-label-danger">Belum Lengkap</span>
                    @endif
                  </td>
                  <td class="verifikator-cell text-nowrap">{{ $checklist->verifiedBy->name ?? '-' }}</td>
                  <td class="tgl-verifikasi-cell text-nowrap">{{ $checklist->verified_at ? $checklist->verified_at->format('d M Y H:i') : '-' }}</td>
                  <td class="text-center kelompok-cell">
                    <span class="badge {{ $kelompokBadge }}">{{ $kelompok }}</span>
                  </td>
                  <td class="kekurangan-cell">
                    @if(empty($kurangItems))
                      <span class="text-success fw-medium">✓ Lengkap</span>
                    @else
                      @foreach($kurangItems as $item)
                        <span class="badge bg-label-danger me-1">{{ $item }}</span>
                      @endforeach
                    @endif
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="12" class="text-center py-4 text-muted">
                    Tidak ada data siswa ditemukan untuk kriteria ini.
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
        
        <div class="p-3 border-top d-flex justify-content-end" id="pagination-container">
          {{ $siswas->links() }}
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Toast Container untuk Notifikasi -->
<div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 10000;">
  <div id="liveToast" class="toast align-items-center border-0 text-white" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="d-flex">
      <div class="toast-body" id="toast-message">
        Checklist berhasil diperbarui!
      </div>
      <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
  </div>
</div>

<style>
  #pagination-container .page-item .page-link,
  #pagination-container .page-item span.page-link {
    border-radius: 0.375rem !important;
    margin: 0 3px !important;
  }
</style>
@endsection

@section('page-script')
<script>
  window.periodeConfig = {
    XI: {
      active: @json($periodeXI && today()->between($periodeXI->tanggal_buka, $periodeXI->tanggal_tutup) && $periodeXI->is_active),
      text: @json($periodeXI ? 'Periode Aktif: ' . $periodeXI->tanggal_buka->format('d M Y') . ' s/d ' . $periodeXI->tanggal_tutup->format('d M Y') : 'Periode Aktif: Tidak Ada Periode Aktif')
    },
    XII: {
      active: @json($periodeXII && today()->between($periodeXII->tanggal_buka, $periodeXII->tanggal_tutup) && $periodeXII->is_active),
      text: @json($periodeXII ? 'Periode Aktif: ' . $periodeXII->tanggal_buka->format('d M Y') . ' s/d ' . $periodeXII->tanggal_tutup->format('d M Y') : 'Periode Aktif: Tidak Ada Periode Aktif')
    }
  };
</script>

@vite('resources/js/daftar-ulang-echo.js')

<script>
// Inisialisasi Echo Real-Time Listener (polling sampai modul siap)
(function initEcho() {
    if (window.daftarUlangEcho) {
        window.daftarUlangEcho.init({
            onChecklistUpdated: function(payload) {
                if (payload.siswa_id === null) {
                    // Event reset global — refresh seluruh data tabel
                    fetchData();
                    showToast('Semua data checklist direset oleh admin.', 'info');
                    return;
                }

                // Update baris checklist spesifik tanpa reload halaman
                const row = document.querySelector('tr[data-siswa-id="' + payload.siswa_id + '"]');
                if (row) {
                    // Update checkboxes
                    row.querySelector('[data-doc="raport"]').checked = payload.raport;
                    row.querySelector('[data-doc="kk"]').checked = payload.kartu_keluarga;
                    row.querySelector('[data-doc="akte"]').checked = payload.akte_kelahiran;
                    row.querySelector('[data-doc="ijazah"]').checked = payload.ijazah;

                    // Format tanggal verifikasi
                    let formattedDate = '-';
                    if (payload.verified_at) {
                        const dateObj = new Date(payload.verified_at);
                        formattedDate = dateObj.toLocaleDateString('id-ID', {
                            day: 'numeric', month: 'short', year: 'numeric',
                            hour: '2-digit', minute: '2-digit'
                        });
                    }

                    // Update status badge, verifikator, dan tanggal
                    updateRowVisual(
                        row,
                        payload.raport,
                        payload.kartu_keluarga,
                        payload.akte_kelahiran,
                        payload.ijazah,
                        payload.verified_by_name || 'Admin',
                        formattedDate,
                        payload.nama_kelompok,
                        payload.kurang_item
                    );
                }

                showToast('Data siswa diperbarui oleh ' + (payload.verified_by_name || 'Admin'), 'success');
            },

            onStatsUpdated: function(stats) {
                recalculateStats(stats);
            }
        });
    } else {
        setTimeout(initEcho, 500);
    }
})();

document.addEventListener('DOMContentLoaded', function() {
  // 1. State
  let currentKelas = '{{ $kelas }}';
  let currentStatus = '{{ request('status') }}';
  let currentSearch = '{{ request('search') }}';
  let currentPage = {{ $siswas->currentPage() }};
  let currentKelompok = '{{ request('kelompok') }}';
  let currentKurangBerkas = '{{ request('kurang_berkas') }}';
  let abortController = null;

  const tableBody = document.getElementById('table-body');
  const warningAlert = document.getElementById('periode-warning-alert');
  const periodeText = document.getElementById('periode-aktif-text');
  const siswaSearchInput = document.getElementById('siswa-search');
  const statusFilterSelect = document.getElementById('status-filter');
  const filterForm = document.getElementById('filter-form');
  const paginationContainer = document.getElementById('pagination-container');

  // Initial check & setup
  updatePeriodeUI();

  // 2. Debounce helper
  function debounce(func, delay) {
    let debounceTimer;
    return function() {
      const context = this;
      const args = arguments;
      clearTimeout(debounceTimer);
      debounceTimer = setTimeout(() => func.apply(context, args), delay);
    }
  }

  // Event handler for search input with debounce
  const handleSearch = debounce(function() {
    const val = siswaSearchInput.value.trim();
    if (val === '' || val.length >= 3) {
      currentSearch = val;
      currentPage = 1;
      fetchData();
    }
  }, 300);

  siswaSearchInput.addEventListener('input', handleSearch);

  // Status Filter change
  statusFilterSelect.addEventListener('change', function() {
    currentStatus = this.value;
    currentPage = 1;
    fetchData();
  });

  // Kelompok Filter change
  document.getElementById('kelompok-filter').addEventListener('change', function() {
    currentKelompok = this.value;
    currentPage = 1;
    fetchData();
  });

  // Kurang Berkas Filter change
  document.getElementById('kurang-berkas-filter').addEventListener('change', function() {
    currentKurangBerkas = this.value;
    currentPage = 1;
    fetchData();
  });

  // Tab Kelas Click
  document.querySelectorAll('.tab-kelas').forEach(tab => {
    tab.addEventListener('click', function(e) {
      e.preventDefault();
      
      // Update active tab visual
      document.querySelectorAll('.tab-kelas').forEach(t => t.classList.remove('active'));
      this.classList.add('active');

      currentKelas = this.getAttribute('data-kelas');
      currentPage = 1;
      
      // Update hidden input in form
      const hiddenKelasInput = filterForm.querySelector('input[name="kelas"]');
      if (hiddenKelasInput) {
        hiddenKelasInput.value = currentKelas;
      }

      // Update URL with window.history.pushState to keep history clean/updated
      updateUrl();
      
      updatePeriodeUI();
      fetchData();
    });
  });

  // Intercept form submit (Cari button click or enter)
  filterForm.addEventListener('submit', function(e) {
    e.preventDefault();
    currentSearch = siswaSearchInput.value.trim();
    currentStatus = statusFilterSelect.value;
    currentKelompok = document.getElementById('kelompok-filter').value;
    currentKurangBerkas = document.getElementById('kurang-berkas-filter').value;
    currentPage = 1;
    fetchData();
  });

  // 3. Event delegation for checklist checkboxes
  tableBody.addEventListener('change', function(e) {
    if (e.target && e.target.classList.contains('checklist-cb')) {
      const cb = e.target;
      const config = window.periodeConfig[currentKelas];
      const isCurrentlyActive = config ? config.active : false;

      if (!isCurrentlyActive) {
        alert('Periode daftar ulang untuk kelas ' + currentKelas + ' saat ini sedang tidak aktif.');
        cb.checked = !cb.checked;
        return;
      }

      const row = cb.closest('tr');
      const siswaId = row.getAttribute('data-siswa-id');
      
      // Ambil data checklist dari row
      const raport = row.querySelector('[data-doc="raport"]').checked;
      const kk = row.querySelector('[data-doc="kk"]').checked;
      const akte = row.querySelector('[data-doc="akte"]').checked;
      const ijazah = row.querySelector('[data-doc="ijazah"]').checked;

      // Update client-side UI temporarily
      updateRowVisual(row, raport, kk, akte, ijazah, 'Mengupdate...', '-');

      // Kirim request ke server via Fetch API
      fetch(`/admin/daftar-ulang/${siswaId}/checklist`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'X-Requested-With': 'XMLHttpRequest',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
          raport: raport,
          kartu_keluarga: kk,
          akte_kelahiran: akte,
          ijazah: ijazah
        })
      })
      .then(response => {
        if (!response.ok) {
          return response.json().then(err => {
            throw new Error(err.message || 'Gagal memperbarui checklist');
          });
        }
        return response.json();
      })
      .then(res => {
        const responseData = res.data;
        
        let formattedDate = '-';
        if (responseData.verified_at) {
          const dateObj = new Date(responseData.verified_at);
          formattedDate = dateObj.toLocaleDateString('id-ID', {
            day: 'numeric',
            month: 'short',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
          });
        }

        updateRowVisual(
          row, 
          responseData.raport, 
          responseData.kartu_keluarga, 
          responseData.akte_kelahiran, 
          responseData.ijazah, 
          responseData.verified_by_name || 'Admin', 
          formattedDate,
          responseData.nama_kelompok,
          responseData.kurang_item
        );
        showToast('Checklist berhasil disimpan!', 'success');
        
        // Recalculate stats based on JSON response
        recalculateStats(res.stats);
      })
      .catch(error => {
        console.error(error);
        showToast(error.message || 'Terjadi kesalahan sistem', 'danger');
        
        // Rollback visual
        cb.checked = !cb.checked;
        const r = row.querySelector('[data-doc="raport"]').checked;
        const k = row.querySelector('[data-doc="kk"]').checked;
        const a = row.querySelector('[data-doc="akte"]').checked;
        const i = row.querySelector('[data-doc="ijazah"]').checked;
        updateRowVisual(row, r, k, a, i, '-', '-');
      });
    }
  });

  // Handle pagination link clicks dynamically
  paginationContainer.addEventListener('click', function(e) {
    const link = e.target.closest('a.page-link');
    if (link) {
      e.preventDefault();
      const url = new URL(link.href);
      const page = url.searchParams.get('page');
      if (page) {
        currentPage = parseInt(page);
        fetchData();
      }
    }
  });

  // 4. Fetch API AJAX Function
  function fetchData() {
    if (abortController) {
      abortController.abort();
    }
    abortController = new AbortController();

    // Show skeleton loader
    renderSkeleton();

    // Build URL query parameters
    const params = new URLSearchParams();
    params.append('kelas', currentKelas);
    if (currentStatus) params.append('status', currentStatus);
    if (currentSearch) params.append('search', currentSearch);
    if (currentKelompok) params.append('kelompok', currentKelompok);
    if (currentKurangBerkas) params.append('kurang_berkas', currentKurangBerkas);
    params.append('page', currentPage);

    updateUrl();

    fetch(`/admin/daftar-ulang?${params.toString()}`, {
      method: 'GET',
      headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'Accept': 'application/json'
      },
      signal: abortController.signal
    })
    .then(response => {
      if (!response.ok) {
        throw new Error('Gagal mengambil data');
      }
      return response.json();
    })
    .then(res => {
      if (res.success) {
        const paginator = res.data;
        renderTable(paginator.data, paginator.from);
        renderPagination(paginator);
        recalculateStats(res.stats);
      }
    })
    .catch(err => {
      if (err.name !== 'AbortError') {
        console.error(err);
        tableBody.innerHTML = `
          <tr>
            <td colspan="12" class="text-center py-4 text-danger">
              Gagal memuat data. Silakan coba lagi.
            </td>
          </tr>
        `;
      }
    });
  }

  // 5. Loading State: Skeleton Loader
  function renderSkeleton() {
    let rowsHtml = '';
    for (let i = 0; i < 5; i++) {
      rowsHtml += `
        <tr class="skeleton-row">
          <td><div class="skeleton-line" style="width: 20px;"></div></td>
          <td><div class="skeleton-line" style="width: 80px;"></div></td>
          <td>
            <div class="skeleton-line" style="width: 150px; height: 16px; margin-bottom: 5px;"></div>
            <div class="skeleton-line" style="width: 100px;"></div>
          </td>
          <td class="text-center"><div class="skeleton-cb"></div></td>
          <td class="text-center"><div class="skeleton-cb"></div></td>
          <td class="text-center"><div class="skeleton-cb"></div></td>
          <td class="text-center"><div class="skeleton-cb"></div></td>
          <td class="text-center"><div class="skeleton-line" style="width: 80px; height: 24px; border-radius: 4px; margin: 0 auto;"></div></td>
          <td><div class="skeleton-line" style="width: 80px;"></div></td>
          <td><div class="skeleton-line" style="width: 100px;"></div></td>
          <td class="text-center"><div class="skeleton-line" style="width: 100px; height: 24px; border-radius: 4px; margin: 0 auto;"></div></td>
          <td><div class="skeleton-line" style="width: 120px;"></div></td>
        </tr>
      `;
    }
    tableBody.innerHTML = rowsHtml;
  }

  // Style helper for skeleton
  const style = document.createElement('style');
  style.innerHTML = `
    .skeleton-line {
      background: #e0e0e0;
      background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
      background-size: 200% 100%;
      animation: skeleton-loading 1.5s infinite;
      height: 12px;
      border-radius: 2px;
    }
    .skeleton-cb {
      background: #e0e0e0;
      background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
      background-size: 200% 100%;
      animation: skeleton-loading 1.5s infinite;
      height: 18px;
      width: 18px;
      border-radius: 4px;
      margin: 0 auto;
    }
    @keyframes skeleton-loading {
      0% { background-position: 200% 0; }
      100% { background-position: -200% 0; }
    }
  `;
  document.head.appendChild(style);

  // 6. Render Table
  function renderTable(data, from) {
    if (!data || data.length === 0) {
      tableBody.innerHTML = `
        <tr>
          <td colspan="12" class="text-center py-4 text-muted">
            Tidak ada data siswa ditemukan untuk kriteria ini.
          </td>
        </tr>
      `;
      return;
    }

    const config = window.periodeConfig[currentKelas];
    const isCurrentlyActive = config ? config.active : false;

    let rowsHtml = '';
    data.forEach((siswa, index) => {
      const ch = siswa.checklist;
      const isLengkap = ch && ch.raport && ch.kartu_keluarga && ch.akte_kelahiran && ch.ijazah;
      const statusBadge = isLengkap 
        ? '<span class="badge bg-label-success">Lengkap</span>' 
        : '<span class="badge bg-label-danger">Belum Lengkap</span>';
      
      const verifiedBy = siswa.verified_by_name || '-';
      let verifiedAt = '-';
      if (ch && ch.verified_at) {
        const dateObj = new Date(ch.verified_at);
        verifiedAt = dateObj.toLocaleDateString('id-ID', {
          day: 'numeric',
          month: 'short',
          year: 'numeric',
          hour: '2-digit',
          minute: '2-digit'
        });
      }

      // Data segmentasi kelompok
      const namaKelompok = (ch && ch.nama_kelompok) ? ch.nama_kelompok : 'Belum Kumpul';
      const kurangItems = (ch && ch.kurang_item) ? ch.kurang_item : ['Raport', 'Kartu Keluarga', 'Akte Kelahiran', 'Ijazah'];
      const kelompokBadgeHtml = renderKelompokBadge(namaKelompok);
      const kekuranganHtml = renderKekuranganBadge(kurangItems);

      const disabledAttr = isCurrentlyActive ? '' : 'disabled';
      const checklistStatusAttr = (ch && ch.status) ? ch.status : 'belum_lengkap';

      rowsHtml += `
        <tr data-siswa-id="${siswa.id}" data-kelas="${siswa.kelas_tujuan}" data-nama="${siswa.nama_lengkap}" data-nis="${siswa.nis}" data-status="${checklistStatusAttr}">
          <td>${(from || 1) + index}</td>
          <td>${siswa.nis}</td>
          <td class="text-nowrap">
            <span class="fw-semibold text-heading text-nowrap">${siswa.nama_lengkap}</span>
            <div class="text-muted small">Kelas: ${siswa.kelas_tujuan} (Asal: ${siswa.kelas_asal})</div>
          </td>
          <td class="text-center">
            <input class="form-check-input checklist-cb" type="checkbox" data-doc="raport" ${ch && ch.raport ? 'checked' : ''} ${disabledAttr}>
          </td>
          <td class="text-center">
            <input class="form-check-input checklist-cb" type="checkbox" data-doc="kk" ${ch && ch.kartu_keluarga ? 'checked' : ''} ${disabledAttr}>
          </td>
          <td class="text-center">
            <input class="form-check-input checklist-cb" type="checkbox" data-doc="akte" ${ch && ch.akte_kelahiran ? 'checked' : ''} ${disabledAttr}>
          </td>
          <td class="text-center">
            <input class="form-check-input checklist-cb" type="checkbox" data-doc="ijazah" ${ch && ch.ijazah ? 'checked' : ''} ${disabledAttr}>
          </td>
          <td class="text-center status-badge-cell">
            ${statusBadge}
          </td>
          <td class="verifikator-cell text-nowrap">${verifiedBy}</td>
          <td class="tgl-verifikasi-cell text-nowrap">${verifiedAt}</td>
          <td class="text-center kelompok-cell">
            ${kelompokBadgeHtml}
          </td>
          <td class="kekurangan-cell">
            ${kekuranganHtml}
          </td>
        </tr>
      `;
    });

    tableBody.innerHTML = rowsHtml;
  }

  // 7. Render Pagination
  function renderPagination(paginator) {
    if (paginator.last_page <= 1) {
      paginationContainer.innerHTML = '';
      return;
    }

    let linksHtml = '<nav><ul class="pagination justify-content-end mb-0">';
    
    // Previous Link
    if (paginator.prev_page_url) {
      const prevUrl = new URL(paginator.prev_page_url);
      prevUrl.searchParams.set('kelas', currentKelas);
      if (currentStatus) prevUrl.searchParams.set('status', currentStatus);
      if (currentSearch) prevUrl.searchParams.set('search', currentSearch);
      linksHtml += `<li class="page-item"><a class="page-link" href="${prevUrl.pathname + prevUrl.search}" rel="prev" aria-label="&laquo; Previous">&lsaquo;</a></li>`;
    } else {
      linksHtml += `<li class="page-item disabled" aria-disabled="true" aria-label="&laquo; Previous"><span class="page-link" aria-hidden="true">&lsaquo;</span></li>`;
    }

    // Sliding Window Logic
    let startPage = Math.max(1, paginator.current_page - 2);
    let endPage = Math.min(paginator.last_page, paginator.current_page + 2);

    // Sesuaikan window jika di ujung awal
    if (paginator.current_page <= 3) {
      startPage = 1;
      endPage = Math.min(5, paginator.last_page);
    }

    // Sesuaikan window jika di ujung akhir
    if (paginator.current_page > paginator.last_page - 3) {
      startPage = Math.max(1, paginator.last_page - 4);
      endPage = paginator.last_page;
    }

    // First Page Link & Ellipsis
    if (startPage > 1) {
      const firstUrl = new URL(paginator.path);
      firstUrl.searchParams.set('page', 1);
      firstUrl.searchParams.set('kelas', currentKelas);
      if (currentStatus) firstUrl.searchParams.set('status', currentStatus);
      if (currentSearch) firstUrl.searchParams.set('search', currentSearch);
      
      linksHtml += `<li class="page-item"><a class="page-link" href="${firstUrl.pathname + firstUrl.search}">1</a></li>`;
      
      if (startPage > 2) {
        linksHtml += `<li class="page-item disabled" aria-disabled="true"><span class="page-link">...</span></li>`;
      }
    }

    // Page Numbers (sliding window)
    for (let i = startPage; i <= endPage; i++) {
      const pageUrl = new URL(paginator.path);
      pageUrl.searchParams.set('page', i);
      pageUrl.searchParams.set('kelas', currentKelas);
      if (currentStatus) pageUrl.searchParams.set('status', currentStatus);
      if (currentSearch) pageUrl.searchParams.set('search', currentSearch);

      if (i === paginator.current_page) {
        linksHtml += `<li class="page-item active" aria-current="page"><span class="page-link">${i}</span></li>`;
      } else {
        linksHtml += `<li class="page-item"><a class="page-link" href="${pageUrl.pathname + pageUrl.search}">${i}</a></li>`;
      }
    }

    // Last Page Link & Ellipsis
    if (endPage < paginator.last_page) {
      if (endPage < paginator.last_page - 1) {
        linksHtml += `<li class="page-item disabled" aria-disabled="true"><span class="page-link">...</span></li>`;
      }
      
      const lastUrl = new URL(paginator.path);
      lastUrl.searchParams.set('page', paginator.last_page);
      lastUrl.searchParams.set('kelas', currentKelas);
      if (currentStatus) lastUrl.searchParams.set('status', currentStatus);
      if (currentSearch) lastUrl.searchParams.set('search', currentSearch);
      
      linksHtml += `<li class="page-item"><a class="page-link" href="${lastUrl.pathname + lastUrl.search}">${paginator.last_page}</a></li>`;
    }

    // Next Link
    if (paginator.next_page_url) {
      const nextUrl = new URL(paginator.next_page_url);
      nextUrl.searchParams.set('kelas', currentKelas);
      if (currentStatus) nextUrl.searchParams.set('status', currentStatus);
      if (currentSearch) nextUrl.searchParams.set('search', currentSearch);
      linksHtml += `<li class="page-item"><a class="page-link" href="${nextUrl.pathname + nextUrl.search}" rel="next" aria-label="Next &raquo;">&rsaquo;</a></li>`;
    } else {
      linksHtml += `<li class="page-item disabled" aria-disabled="true" aria-label="Next &raquo;"><span class="page-link" aria-hidden="true">&rsaquo;</span></li>`;
    }

    linksHtml += '</ul></nav>';
    paginationContainer.innerHTML = linksHtml;
  }

  // 8. Recalculate statistics (client side)
  function recalculateStats(stats) {
    if (stats) {
      document.getElementById('stat-total').textContent = stats.total;
      document.getElementById('stat-lengkap').textContent = stats.lengkap;
      document.getElementById('stat-belum').textContent = stats.belum;
      document.getElementById('stat-persen').textContent = stats.persen + '%';
    }
  }

  // Update Periode UI Warning/Text
  function updatePeriodeUI() {
    const config = window.periodeConfig[currentKelas];
    if (config) {
      periodeText.textContent = config.text;
      if (config.active) {
        warningAlert.classList.add('d-none');
      } else {
        warningAlert.classList.remove('d-none');
      }
    }
  }

  // Helper to render kelompok badge
  function renderKelompokBadge(namaKelompok) {
    const kelompokBadgeMap = {
      'Lengkap': 'bg-label-success',
      'Hampir Lengkap': 'bg-label-primary',
      'Setengah Lengkap': 'bg-label-warning',
      'Baru Memulai': 'bg-label-info',
      'Belum Kumpul': 'bg-label-danger'
    };
    const badgeClass = kelompokBadgeMap[namaKelompok] || 'bg-label-secondary';
    return `<span class="badge ${badgeClass}">${namaKelompok}</span>`;
  }

  // Helper to render kekurangan badge
  function renderKekuranganBadge(kurangItems) {
    if (!kurangItems || kurangItems.length === 0) {
      return '<span class="text-success fw-medium">✓ Lengkap</span>';
    }
    let html = '';
    kurangItems.forEach(item => {
      html += `<span class="badge bg-label-danger me-1">${item}</span>`;
    });
    return html;
  }

  // Helper to update visual row state on checklist update
  function updateRowVisual(row, raport, kk, akte, ijazah, verifikator, tanggal, namaKelompok = null, kurangItems = null) {
    const statusCell = row.querySelector('.status-badge-cell');
    const verifikatorCell = row.querySelector('.verifikator-cell');
    const tglCell = row.querySelector('.tgl-verifikasi-cell');
    const kelompokCell = row.querySelector('.kelompok-cell');
    const kekuranganCell = row.querySelector('.kekurangan-cell');
    
    const isLengkap = raport && kk && akte && ijazah;
    
    if (isLengkap) {
      statusCell.innerHTML = '<span class="badge bg-label-success">Lengkap</span>';
      row.setAttribute('data-status', 'lengkap');
    } else {
      statusCell.innerHTML = '<span class="badge bg-label-danger">Belum Lengkap</span>';
      row.setAttribute('data-status', 'belum_lengkap');
    }
    verifikatorCell.textContent = verifikator;
    tglCell.textContent = tanggal;

    // Hitung kelompok dan kekurangan secara client-side jika tidak disediakan (e.g. loading state atau error rollback)
    if (!namaKelompok || !kurangItems) {
      const items = [];
      let score = 0;
      if (raport) score++; else items.push('Raport');
      if (kk) score++; else items.push('Kartu Keluarga');
      if (akte) score++; else items.push('Akte Kelahiran');
      if (ijazah) score++; else items.push('Ijazah');

      kurangItems = items;

      const kelompokNames = {
        4: 'Lengkap',
        3: 'Hampir Lengkap',
        2: 'Setengah Lengkap',
        1: 'Baru Memulai',
        0: 'Belum Kumpul'
      };
      namaKelompok = kelompokNames[score];
    }

    if (kelompokCell) {
      kelompokCell.innerHTML = renderKelompokBadge(namaKelompok);
    }
    if (kekuranganCell) {
      kekuranganCell.innerHTML = renderKekuranganBadge(kurangItems);
    }
  }

  // Update browser URL query string
  function updateUrl() {
    const params = new URLSearchParams(window.location.search);
    params.set('kelas', currentKelas);
    if (currentStatus) {
      params.set('status', currentStatus);
    } else {
      params.delete('status');
    }
    if (currentSearch) {
      params.set('search', currentSearch);
    } else {
      params.delete('search');
    }
    if (currentPage && currentPage > 1) {
      params.set('page', currentPage);
    } else {
      params.delete('page');
    }

    const newUrl = window.location.pathname + '?' + params.toString();
    window.history.pushState({ path: newUrl }, '', newUrl);
  }

  // Toast Notifikasi
  function showToast(message, type) {
    const toastEl = document.getElementById('liveToast');
    const messageEl = document.getElementById('toast-message');
    
    toastEl.className = 'toast align-items-center border-0 text-white';
    if (type === 'success') {
      toastEl.classList.add('bg-success');
    } else {
      toastEl.classList.add('bg-danger');
    }
    
    messageEl.textContent = message;
    
    const toast = new bootstrap.Toast(toastEl);
    toast.show();
  }

  // Reset Data Handler
  const btnReset = document.getElementById('btn-reset-data');
  if (btnReset) {
    btnReset.addEventListener('click', function() {
      Swal.fire({
        title: 'Apakah Anda Yakin?',
        text: 'Tindakan ini akan menghapus SELURUH data siswa dan checklist daftar ulang kelas XI & XII secara permanen!',
        icon: 'warning',
        input: 'text',
        inputPlaceholder: 'Ketik "RESET DATA" untuk mengonfirmasi',
        showCancelButton: true,
        confirmButtonText: 'Ya, Hapus Semua!',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#ea5455', // warna merah danger
        cancelButtonColor: '#8592a3',
        customClass: {
          confirmButton: 'btn btn-danger me-3',
          cancelButton: 'btn btn-label-secondary'
        },
        buttonsStyling: false,
        inputValidator: (value) => {
          if (value !== 'RESET DATA') {
            return 'Anda harus mengetik "RESET DATA" (huruf kapital) secara tepat!';
          }
        }
      }).then((result) => {
        if (result.isConfirmed) {
          Swal.fire({
            title: 'Memproses...',
            text: 'Mohon tunggu sebentar.',
            allowOutsideClick: false,
            didOpen: () => {
              Swal.showLoading();
            }
          });
          document.getElementById('reset-form').submit();
        }
      });
    });
  }
});
</script>
@endsection
