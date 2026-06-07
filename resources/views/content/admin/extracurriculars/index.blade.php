@extends('layouts/contentNavbarLayout')

@section('title', 'Kelola Ekstrakurikuler')

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
          <span class="avatar-initial rounded bg-label-info">
            <i class="icon-base ti tabler-users-group"></i>
          </span>
        </div>
        <h5 class="card-title mb-0">Daftar Ekstrakurikuler</h5>
      </div>
      <a href="{{ route('admin.extracurriculars.create') }}" class="btn btn-primary">
        <i class="icon-base ti tabler-plus me-1"></i> Tambah Ekskul
      </a>
    </div>
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover datatables-ekskul mb-0">
          <thead class="border-top">
            <tr>
              <th>No</th>
              <th>Nama</th>
              <th>Pembina</th>
              <th>Kategori</th>
              <th>Jadwal</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse($extracurriculars as $item)
              <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $item->name }}</td>
                <td>{{ $item->coach ?? '-' }}</td>
                <td><span class="badge bg-label-{{ $catColors[$item->category] ?? 'secondary' }}">{{ $item->category ?? '-' }}</span></td>
                <td><small class="text-muted">{{ $item->schedule ?? '-' }}</small></td>
                <td>
                  <div class="d-flex align-items-center gap-1">
                    <a href="{{ route('admin.extracurriculars.edit', $item->id) }}" class="btn btn-sm btn-icon btn-text-secondary rounded-pill waves-effect" title="Edit">
                      <i class="icon-base ti tabler-pencil"></i>
                    </a>
                    <form action="{{ route('admin.extracurriculars.destroy', $item->id) }}" method="POST" class="d-inline mb-0" onsubmit="return confirm('Yakin ingin menghapus ekstrakurikuler ini?')">
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
                <td colspan="6" class="text-center py-5">
                  <div class="avatar avatar-lg mb-3">
                    <span class="avatar-initial rounded bg-label-secondary">
                      <i class="icon-base ti tabler-users-minus icon-lg"></i>
                    </span>
                  </div>
                  <p class="text-muted mb-0">Belum ada data ekstrakurikuler.</p>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
      <div class="p-3 border-top">{{ $extracurriculars->links() }}</div>
    </div>
  </div>
@endsection

@section('page-script')
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const table = document.querySelector('.datatables-ekskul');
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
