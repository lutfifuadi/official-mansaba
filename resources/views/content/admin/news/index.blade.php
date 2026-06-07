@php use Illuminate\Support\Str; @endphp
@extends('layouts/contentNavbarLayout')

@section('title', 'Kelola Berita')

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
          <span class="avatar-initial rounded bg-label-primary">
            <i class="icon-base ti tabler-news"></i>
          </span>
        </div>
        <h5 class="card-title mb-0">Daftar Berita</h5>
      </div>
      <a href="{{ route('admin.news.create') }}" class="btn btn-primary">
        <i class="icon-base ti tabler-plus me-1"></i> Tambah Berita
      </a>
    </div>
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover datatables-news mb-0">
          <thead class="border-top">
            <tr>
              <th>No</th>
              <th>Judul</th>
              <th>Kategori</th>
              <th>Penulis</th>
              <th>Publikasi</th>
              <th>Status</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse($news as $item)
              <tr>
                <td>{{ $loop->iteration }}</td>
                <td><span title="{{ $item->title }}" style="display:inline-block;max-width:400px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ Str::limit($item->title, 110) }}</span></td>
                <td><span class="badge bg-label-{{ $catColors[$item->category] ?? 'secondary' }}">{{ $item->category ?? 'Umum' }}</span></td>
                <td><span title="{{ $item->author }}" style="display:inline-block;max-width:150px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ Str::limit($item->author, 20) }}</span></td>
                <td>{{ $item->published_at ? $item->published_at->format('d M Y') : '-' }}</td>
                <td>
                  @if($item->is_published)
                    <span class="badge bg-label-success">Publik</span>
                  @else
                    <span class="badge bg-label-secondary">Draft</span>
                  @endif
                </td>
                <td>
                  <div class="d-flex align-items-center gap-1">
                    <a href="{{ route('public.news-detail', $item->slug) }}" target="_blank" class="btn btn-sm btn-icon btn-text-secondary rounded-pill waves-effect text-info" title="Lihat Hasil">
                      <i class="icon-base ti tabler-eye"></i>
                    </a>
                    <a href="{{ route('admin.news.edit', $item->id) }}" class="btn btn-sm btn-icon btn-text-secondary rounded-pill waves-effect" title="Edit">
                      <i class="icon-base ti tabler-pencil"></i>
                    </a>
                    <form action="{{ route('admin.news.destroy', $item->id) }}" method="POST" class="d-inline mb-0" onsubmit="return confirm('Yakin ingin menghapus berita ini?')">
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
                      <i class="icon-base ti tabler-news-off icon-lg"></i>
                    </span>
                  </div>
                  <p class="text-muted mb-0">Belum ada data berita.</p>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
      <div class="p-3 border-top">{{ $news->links() }}</div>
    </div>
  </div>
@endsection

@section('page-script')
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const table = document.querySelector('.datatables-news');
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
