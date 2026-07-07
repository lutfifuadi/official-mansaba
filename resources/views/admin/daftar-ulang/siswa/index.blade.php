@extends('layouts/contentNavbarLayout')

@section('title', 'Kelola Data Siswa Daftar Ulang')

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

<div class="card">
  <!-- Card Header -->
  <div class="card-header border-bottom d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-4">
    <div class="d-flex align-items-center">
      <div class="avatar avatar-sm me-3">
        <span class="avatar-initial rounded bg-label-primary">
          <i class="icon-base ti tabler-users icon-lg"></i>
        </span>
      </div>
      <div>
        <h5 class="card-title mb-0">Daftar Siswa Daftar Ulang</h5>
        <p class="text-muted mb-0 small">Total terdaftar: {{ $siswas->total() }} siswa</p>
      </div>
    </div>
    <div class="d-flex flex-column flex-sm-row gap-3">
      <!-- Search Input -->
      <form action="" method="GET" class="d-flex gap-2">
        <div class="input-group input-group-merge" style="min-width: 250px;">
          <span class="input-group-text"><i class="icon-base ti tabler-search"></i></span>
          <input type="text" name="search" class="form-control" placeholder="Cari Nama atau NIS..." value="{{ request('search') }}">
        </div>
        <button type="submit" class="btn btn-secondary">Cari</button>
      </form>
      <!-- Button Tambah Siswa -->
      <a href="{{ route('admin.daftar-ulang-siswa.create') }}" class="btn btn-primary">
        <i class="icon-base ti tabler-plus me-1"></i> Tambah Siswa Baru
      </a>
    </div>
  </div>

  <!-- Table Body -->
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover table-striped mb-0">
        <thead>
          <tr>
            <th width="50">No</th>
            <th>NIS</th>
            <th>Nama Lengkap</th>
            <th>Kelas Asal</th>
            <th>Kelas Tujuan</th>
            <th>Jurusan</th>
            <th>Periode Target</th>
            <th width="120">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($siswas as $index => $siswa)
            <tr>
              <td>{{ $siswas->firstItem() + $index }}</td>
              <td><span class="fw-bold">{{ $siswa->nis }}</span></td>
              <td>
                <span class="fw-semibold text-heading">{{ $siswa->nama_lengkap }}</span>
              </td>
              <td>{{ $siswa->kelas_asal }}</td>
              <td>{{ $siswa->kelas_tujuan }}</td>
              <td>{{ $siswa->jurusan ?? '-' }}</td>
              <td>{{ $siswa->periode->tahun_ajaran ?? '-' }} ({{ $siswa->kelas_tujuan }})</td>
              <td>
                <div class="d-flex align-items-center gap-1">
                  <a href="{{ route('admin.daftar-ulang-siswa.edit', $siswa->id) }}" class="btn btn-sm btn-icon btn-text-secondary rounded-pill waves-effect" title="Edit">
                    <i class="icon-base ti tabler-pencil"></i>
                  </a>
                  @if(auth()->user()->isSuperAdmin())
                    <button type="button" class="btn btn-sm btn-icon btn-text-secondary rounded-pill waves-effect text-danger btn-delete-siswa" data-siswa-id="{{ $siswa->id }}" data-nama="{{ $siswa->nama_lengkap }}" title="Hapus">
                      <i class="icon-base ti tabler-trash"></i>
                    </button>
                  @endif
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="8" class="text-center py-4 text-muted">
                Tidak ada data siswa ditemukan.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    <div class="p-4 border-top">
      {{ $siswas->links() }}
    </div>
  </div>
</div>

<!-- Form hapus tersembunyi untuk integrasi delete -->
<form id="delete-form" action="" method="POST" class="d-none">
  @csrf
  @method('DELETE')
</form>
@endsection

@section('page-script')
<!-- SweetAlert2 integration -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
  const deleteButtons = document.querySelectorAll('.btn-delete-siswa');
  const deleteForm = document.getElementById('delete-form');

  deleteButtons.forEach(btn => {
    btn.addEventListener('click', function() {
      const id = this.getAttribute('data-siswa-id');
      const nama = this.getAttribute('data-nama');
      
      Swal.fire({
        title: 'Apakah Anda yakin?',
        text: `Data siswa "${nama}" akan dihapus permanen dari sistem!`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ea5455',
        cancelButtonColor: '#8592a3',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
      }).then((result) => {
        if (result.isConfirmed) {
          deleteForm.action = `/admin/daftar-ulang-siswa/${id}`;
          deleteForm.submit();
        }
      });
    });
  });
});
</script>
@endsection
