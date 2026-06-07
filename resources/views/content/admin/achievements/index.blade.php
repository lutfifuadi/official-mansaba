@extends('layouts/contentNavbarLayout')

@section('title', 'Kelola Prestasi')

@section('vendor-style')
  @vite(['resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss'])
@endsection

@section('vendor-script')
  @vite(['resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js'])
@endsection

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
    <div class="card-header border-bottom d-flex justify-content-between align-items-center">
      <div class="d-flex align-items-center">
        <div class="avatar avatar-sm me-3">
          <span class="avatar-initial rounded bg-label-warning">
            <i class="icon-base ti tabler-trophy"></i>
          </span>
        </div>
        <h5 class="card-title mb-0">Daftar Prestasi</h5>
      </div>
      <a href="{{ route('admin.achievements.create') }}" class="btn btn-primary">
        <i class="icon-base ti tabler-plus me-1"></i> Tambah Prestasi
      </a>
    </div>
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover datatables-achievements mb-0">
          <thead class="border-top">
            <tr>
              <th>No</th>
              <th>Judul</th>
              <th>Nama Siswa</th>
              <th>Kategori</th>
              <th>Tingkat</th>
              <th>Tanggal</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse($achievements as $item)
              <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $item->title }}</td>
                <td>{{ $item->student_name }}</td>
                <td><span class="badge bg-label-{{ $catColors[$item->category] ?? 'secondary' }}">{{ $item->category ?? '-' }}</span></td>
                <td>
                  @if($item->level == 'Internasional')
                    <span class="badge bg-label-success">{{ $item->level }}</span>
                  @elseif($item->level == 'Nasional')
                    <span class="badge bg-label-primary">{{ $item->level }}</span>
                  @elseif($item->level == 'Provinsi')
                    <span class="badge bg-label-warning">{{ $item->level }}</span>
                  @elseif($item->level == 'Kota')
                    <span class="badge bg-label-info">{{ $item->level }}</span>
                  @else
                    <span class="badge bg-label-secondary">{{ $item->level }}</span>
                  @endif
                </td>
                <td>{{ $item->achievement_date ? $item->achievement_date->format('d M Y') : '-' }}</td>
                <td>
                  <div class="d-flex align-items-center gap-1">
                    <a href="{{ route('admin.achievements.edit', $item->id) }}" class="btn btn-sm btn-icon btn-text-secondary rounded-pill waves-effect" title="Edit">
                      <i class="icon-base ti tabler-pencil"></i>
                    </a>
                    <form action="{{ route('admin.achievements.destroy', $item->id) }}" method="POST" class="d-inline mb-0" onsubmit="return confirm('Yakin ingin menghapus prestasi ini?')">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-sm btn-icon btn-text-secondary rounded-pill waves-effect text-danger" title="Hapus">
                        <i class="icon-base ti tabler-trash"></i>
                      </button>
                    </form>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="7" class="text-center py-5">
                  <div class="avatar avatar-lg mb-3">
                    <span class="avatar-initial rounded bg-label-secondary">
                      <i class="icon-base ti tabler-trophy-off icon-lg"></i>
                    </span>
                  </div>
                  <p class="text-muted mb-0">Belum ada data prestasi.</p>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
      <div class="p-3 border-top">{{ $achievements->links() }}</div>
    </div>
  </div>
@endsection

@section('page-script')
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const table = document.querySelector('.datatables-achievements');
    if (table) {
      new DataTable(table, {
        responsive: true,
        paging: false,
        info: false,
        searching: false,
        language: {
          url: "{{ asset('assets/json/datatables-id.json') }}",
          paginate: {
            next: '<i class="icon-base ti tabler-chevron-right scaleX-n1-rtl icon-18px"></i>',
            previous: '<i class="icon-base ti tabler-chevron-left scaleX-n1-rtl icon-18px"></i>'
          }
        }
      });
    }
  });
</script>
@endsection
