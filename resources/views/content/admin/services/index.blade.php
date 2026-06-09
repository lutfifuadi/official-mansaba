@extends('layouts/contentNavbarLayout')

@section('title', 'Kelola Layanan Online')

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
          <span class="avatar-initial rounded bg-label-danger">
            <i class="icon-base ti tabler-world"></i>
          </span>
        </div>
        <h5 class="card-title mb-0">Daftar Layanan Online</h5>
      </div>
      <a href="{{ route('admin.services.create') }}" class="btn btn-primary">
        <i class="icon-base ti tabler-plus me-1"></i> Tambah Layanan
      </a>
    </div>
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover mb-0">
          <thead class="border-top">
            <tr>
              <th style="width:50px;">Urutan</th>
              <th>Nama</th>
              <th>Ikon</th>
              <th>URL</th>
              <th>Status</th>
              <th style="width:120px;">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse($services as $item)
              <tr>
                <td><span class="badge bg-label-secondary">{{ $item->sort_order }}</span></td>
                <td class="fw-semibold">{{ $item->name }}</td>
                <td><code>{{ $item->icon }}</code></td>
                <td>
                  @if($item->url && $item->url !== '#')
                    <a href="{{ $item->url }}" target="_blank" class="text-truncate d-inline-block" style="max-width:250px;">
                      <small>{{ $item->url }}</small>
                    </a>
                  @else
                    <span class="text-muted"><small>—</small></span>
                  @endif
                </td>
                <td>
                  @if($item->is_active)
                    <span class="badge bg-label-success">Aktif</span>
                  @else
                    <span class="badge bg-label-secondary">Nonaktif</span>
                  @endif
                </td>
                <td>
                  <div class="d-flex align-items-center gap-1">
                    <a href="{{ route('admin.services.edit', $item->id) }}" class="btn btn-sm btn-icon btn-text-secondary rounded-pill waves-effect" title="Edit">
                      <i class="icon-base ti tabler-pencil"></i>
                    </a>
                    <form action="{{ route('admin.services.destroy', $item->id) }}" method="POST" class="d-inline mb-0" onsubmit="return confirm('Yakin ingin menghapus layanan {{ $item->name }}?')">
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
                      <i class="icon-base ti tabler-world-off icon-lg"></i>
                    </span>
                  </div>
                  <p class="text-muted mb-0">Belum ada layanan online.</p>
                  <a href="{{ route('admin.services.create') }}" class="btn btn-primary mt-3">Tambah Layanan</a>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
      <div class="p-3 border-top">{{ $services->links() }}</div>
    </div>
  </div>
@endsection
