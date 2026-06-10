@php use Illuminate\Support\Str; @endphp
@extends('layouts/layoutFront')
@section('title', 'Prestasi')
@php $breadcrumbs = [['name' => 'Beranda', 'url' => url('/')], ['name' => 'Prestasi']]; @endphp

@section('content')

{{-- Page Header --}}
<div class="mansaba-page-header">
  <div class="container text-center position-relative" style="z-index:1;">
    <nav aria-label="breadcrumb" class="breadcrumb-islami mb-2">
      <ol class="breadcrumb justify-content-center">
        <li class="breadcrumb-item"><a href="{{ route('home') }}" style="color:rgba(255,255,255,0.6);text-decoration:none;">Beranda</a></li>
        <li class="breadcrumb-item active">Prestasi</li>
      </ol>
    </nav>
    <h1 class="mb-2">🏆 Prestasi Membanggakan</h1>
    <p class="header-subtitle">Pencapaian terbaik siswa-siswi MAN 1 Kota Bandung</p>
  </div>
</div>

<section class="section-bg-cream py-6">
  <div class="container-xxl">

  {{-- Quote --}}
  <div class="text-center mb-5">
    <div class="d-inline-block px-4 py-3" style="background:linear-gradient(135deg,rgba(201,151,43,0.08),rgba(201,151,43,0.04));border-radius:5px;border:1px solid rgba(201,151,43,0.2);">
      <p class="mb-1" style="font-family:'Amiri',serif;font-size:1.3rem;color:var(--mansaba-gold);">
        مَنْ جَدَّ وَجَدَ
      </p>
      <small class="text-muted fst-italic">"Barangsiapa bersungguh-sungguh, ia akan berhasil"</small>
    </div>
  </div>

  {{-- Stats --}}
  <div class="row g-3 mb-5">
    <div class="col-6 col-md-3">
      <div class="mansaba-card text-center p-3">
        <div style="font-size:2rem;font-weight:800;color:var(--mansaba-gold);">🥇</div>
        <div style="font-size:1.5rem;font-weight:800;color:var(--mansaba-dark);">45+</div>
        <small class="text-muted">Juara Nasional</small>
      </div>
    </div>
    <div class="col-6 col-md-3">
      <div class="mansaba-card text-center p-3">
        <div style="font-size:2rem;">🥈</div>
        <div style="font-size:1.5rem;font-weight:800;color:var(--mansaba-dark);">80+</div>
        <small class="text-muted">Juara Provinsi</small>
      </div>
    </div>
    <div class="col-6 col-md-3">
      <div class="mansaba-card text-center p-3">
        <div style="font-size:2rem;">🥉</div>
        <div style="font-size:1.5rem;font-weight:800;color:var(--mansaba-dark);">120+</div>
        <small class="text-muted">Juara Kota</small>
      </div>
    </div>
    <div class="col-6 col-md-3">
      <div class="mansaba-card text-center p-3">
        <div style="font-size:2rem;">🌍</div>
        <div style="font-size:1.5rem;font-weight:800;color:var(--mansaba-dark);">5+</div>
        <small class="text-muted">Prestasi Internasional</small>
      </div>
    </div>
  </div>

  {{-- Tabel Prestasi --}}
  <div class="mansaba-card mb-5">
    <div class="card-body p-0">
      <div class="p-4 border-bottom" style="border-color:rgba(27,94,66,0.1)!important;">
        <h5 class="fw-bold mb-0" style="color:var(--mansaba-dark);">
          <i class="ti tabler-trophy" style="color:var(--mansaba-gold);margin-right:0.5rem;"></i>
          Daftar Prestasi
        </h5>
      </div>
      <div class="table-responsive">
        <table class="table mansaba-table-profile table-hover mb-0">
          <thead>
            <tr>
              <th style="width:50px;">#</th>
              <th>Prestasi</th>
              <th>Peserta</th>
              <th>Kategori</th>
              <th>Ekstrakurikuler</th>
              <th>Tingkat</th>
              <th>Tahun</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($achievements ?? [] as $item)
              <tr>
                <td class="fw-bold" style="color:var(--mansaba-green);">{{ $loop->iteration }}</td>
                <td>
                  <div class="d-flex align-items-start gap-2">
                    <span style="font-size:1.1rem;">🏅</span>
                    <div>
                      <div class="fw-bold" style="color:var(--mansaba-dark);font-size:0.9rem;">{{ $item->title ?? 'Judul Prestasi' }}</div>
                      <small class="text-muted">{{ Str::limit(strip_tags($item->description ?? ''), 80) }}</small>
                    </div>
                  </div>
                </td>
                <td style="font-size:0.88rem;">{{ $item->participant ?? $item->student_name ?? '-' }}</td>
                <td><span class="badge-mansaba-green badge px-2 py-1">{{ $item->category ?? '-' }}</span></td>
                <td>
                  @if($item->extracurriculars->isNotEmpty())
                    @foreach($item->extracurriculars as $ekskul)
                      <span class="badge bg-label-info" style="font-size:0.75rem;">{{ $ekskul->name }}</span>
                    @endforeach
                  @else
                    <span class="text-muted" style="font-size:0.85rem;">-</span>
                  @endif
                </td>
                <td><span class="badge-mansaba-gold badge px-2 py-1">{{ $item->level ?? '-' }}</span></td>
                <td style="font-size:0.88rem;white-space:nowrap;">{{ $item->date ?? $item->year ?? '-' }}</td>
              </tr>
            @empty
              <tr>
                <td colspan="7" class="text-center py-5">
                  <i class="ti tabler-trophy" style="font-size:3rem;color:#ccc;"></i>
                  <p class="text-muted mt-3 mb-0">Belum ada data prestasi.</p>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  @if (isset($achievements) && method_exists($achievements, 'links'))
    <div class="mt-4 d-flex justify-content-center">{{ $achievements->links() }}</div>
  @endif

  <div class="mb-5"></div>
  </div>
</section>

@endsection
