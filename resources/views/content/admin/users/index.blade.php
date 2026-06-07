@extends('layouts/contentNavbarLayout')

@section('title', 'Kelola Pengguna')

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
          <span class="avatar-initial rounded bg-label-secondary">
            <i class="icon-base ti tabler-users"></i>
          </span>
        </div>
        <h5 class="card-title mb-0">Daftar Pengguna</h5>
      </div>
      <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
        <i class="icon-base ti tabler-plus me-1"></i> Tambah Pengguna
      </a>
    </div>
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover datatables-users mb-0">
          <thead class="border-top">
            <tr>
              <th>No</th>
              <th>Nama</th>
              <th>Email</th>
              <th>Role</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse($users as $item)
              <tr>
                <td>{{ $loop->iteration }}</td>
                <td>
                  <div class="d-flex align-items-center">
                    <div class="avatar avatar-xs me-2 flex-shrink-0">
                      <span class="avatar-initial rounded bg-label-primary">
                        {{ strtoupper(substr($item->name, 0, 1)) }}
                      </span>
                    </div>
                    {{ $item->name }}
                  </div>
                </td>
                <td>{{ $item->email }}</td>
                <td>
                  @if($item->role == 'super_admin')
                    <span class="badge bg-label-danger">Super Admin</span>
                  @elseif($item->role == 'admin')
                    <span class="badge bg-label-primary">Admin</span>
                  @elseif($item->role == 'operator')
                    <span class="badge bg-label-info">Operator</span>
                  @elseif($item->role == 'editor')
                    <span class="badge bg-label-secondary">Editor</span>
                  @else
                    <span class="badge bg-label-secondary">{{ $item->role }}</span>
                  @endif
                </td>
                <td>
                  <div class="d-flex align-items-center gap-1">
                    <a href="{{ route('admin.users.edit', $item->id) }}" class="btn btn-sm btn-icon btn-text-secondary rounded-pill waves-effect" title="Edit">
                      <i class="icon-base ti tabler-pencil"></i>
                    </a>
                    <form action="{{ route('admin.users.destroy', $item->id) }}" method="POST" class="d-inline mb-0" onsubmit="return confirm('Yakin ingin menghapus pengguna ini?')">
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
                <td colspan="5" class="text-center py-5">
                  <div class="avatar avatar-lg mb-3">
                    <span class="avatar-initial rounded bg-label-secondary">
                      <i class="icon-base ti tabler-users-minus icon-lg"></i>
                    </span>
                  </div>
                  <p class="text-muted mb-0">Belum ada data pengguna.</p>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
      <div class="p-3 border-top">{{ $users->links() }}</div>
    </div>
  </div>
@endsection

@section('page-script')
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const table = document.querySelector('.datatables-users');
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
