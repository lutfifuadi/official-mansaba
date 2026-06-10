@extends('layouts/layoutFront')

@section('title', $ekskul->name ?? 'Detail Ekstrakurikuler')

@section('content')
{{-- Page Header --}}
<div class="mansaba-page-header">
  <div class="container text-center position-relative" style="z-index:1;">
    <nav aria-label="breadcrumb" class="breadcrumb-islami mb-2">
      <ol class="breadcrumb justify-content-center">
        <li class="breadcrumb-item"><a href="{{ route('home') }}" style="color:rgba(255,255,255,0.6);text-decoration:none;">Beranda</a></li>
        <li class="breadcrumb-item"><a href="{{ url('public/extracurriculars') }}" style="color:rgba(255,255,255,0.6);text-decoration:none;">Ekstrakurikuler</a></li>
        <li class="breadcrumb-item active">Detail</li>
      </ol>
    </nav>
    <h1 class="mb-2">⚽ Detail Ekstrakurikuler</h1>
    <p class="header-subtitle">{{ $ekskul->name ?? 'Detail' }}</p>
  </div>
</div>

<section class="section-bg-cream py-6">
  <div class="container-xxl">

    <div class="row justify-content-center">
      <div class="col-lg-10">
        <div class="mansaba-card">
          <div class="row g-0">
            <div class="col-md-4 d-flex align-items-center justify-content-center p-5" style="background:linear-gradient(135deg,rgba(27,94,66,0.05),rgba(27,94,66,0.1));border-right:1px solid rgba(27,94,66,0.1);">
              <div class="text-center">
                <div class="mb-3" style="width:100px;height:100px;background:#fff;border-radius:50%;display:inline-flex;align-items:center;justify-content:center;box-shadow:0 10px 25px rgba(27,94,66,0.15);">
                  <i class="ti tabler-{{ $ekskul->icon ?? 'users' }}" style="font-size:3rem;color:var(--mansaba-green);"></i>
                </div>
                <h4 class="mb-0 fw-bold" style="color:var(--mansaba-dark);">{{ $ekskul->name ?? 'Nama Ekskul' }}</h4>
              </div>
            </div>
            <div class="col-md-8">
              <div class="card-body p-4 p-md-5">
                <h5 class="fw-bold mb-3" style="color:var(--mansaba-dark);">Tentang {{ $ekskul->name ?? 'Ekskul' }}</h5>
                <p style="color:#555;line-height:1.7;">{{ $ekskul->description ?? 'Deskripsi tidak tersedia.' }}</p>

                <div class="row g-4 mt-2">
                  @if ($ekskul->coach ?? $ekskul->advisor ?? $ekskul->pembina ?? false)
                    <div class="col-sm-6">
                      <div class="d-flex align-items-center">
                        <div style="width:45px;height:45px;background:rgba(26,102,179,0.1);border-radius:8px;display:flex;align-items:center;justify-content:center;margin-right:15px;">
                          <i class="ti tabler-chalkboard" style="color:#1A66B3;font-size:1.3rem;"></i>
                        </div>
                        <div>
                          <small class="text-muted fw-bold text-uppercase" style="font-size:0.7rem;letter-spacing:1px;">Pembina</small>
                          <p class="mb-0 fw-bold" style="color:var(--mansaba-dark);">{{ $ekskul->coach ?? $ekskul->advisor ?? $ekskul->pembina }}</p>
                        </div>
                      </div>
                    </div>
                  @endif

                  @if ($ekskul->schedule ?? $ekskul->jadwal ?? false)
                    <div class="col-sm-6">
                      <div class="d-flex align-items-center">
                        <div style="width:45px;height:45px;background:rgba(27,94,66,0.1);border-radius:8px;display:flex;align-items:center;justify-content:center;margin-right:15px;">
                          <i class="ti tabler-clock" style="color:var(--mansaba-green);font-size:1.3rem;"></i>
                        </div>
                        <div>
                          <small class="text-muted fw-bold text-uppercase" style="font-size:0.7rem;letter-spacing:1px;">Jadwal</small>
                          <p class="mb-0 fw-bold" style="color:var(--mansaba-dark);">{{ $ekskul->schedule ?? $ekskul->jadwal }}</p>
                        </div>
                      </div>
                    </div>
                  @endif

                  @if ($ekskul->location ?? $ekskul->tempat ?? false)
                    <div class="col-sm-6">
                      <div class="d-flex align-items-center">
                        <div style="width:45px;height:45px;background:rgba(201,151,43,0.1);border-radius:8px;display:flex;align-items:center;justify-content:center;margin-right:15px;">
                          <i class="ti tabler-map-pin" style="color:var(--mansaba-gold);font-size:1.3rem;"></i>
                        </div>
                        <div>
                          <small class="text-muted fw-bold text-uppercase" style="font-size:0.7rem;letter-spacing:1px;">Tempat</small>
                          <p class="mb-0 fw-bold" style="color:var(--mansaba-dark);">{{ $ekskul->location ?? $ekskul->tempat }}</p>
                        </div>
                      </div>
                    </div>
                  @endif

                  @if ($ekskul->member_count ?? $ekskul->members ?? $ekskul->jumlah_anggota ?? false)
                    <div class="col-sm-6">
                      <div class="d-flex align-items-center">
                        <div style="width:45px;height:45px;background:rgba(123,45,62,0.1);border-radius:8px;display:flex;align-items:center;justify-content:center;margin-right:15px;">
                          <i class="ti tabler-users" style="color:var(--mansaba-maroon);font-size:1.3rem;"></i>
                        </div>
                        <div>
                          <small class="text-muted fw-bold text-uppercase" style="font-size:0.7rem;letter-spacing:1px;">Jumlah Anggota</small>
                          <p class="mb-0 fw-bold" style="color:var(--mansaba-dark);">{{ $ekskul->member_count ?? $ekskul->members ?? $ekskul->jumlah_anggota }}</p>
                        </div>
                      </div>
                    </div>
                  @endif
                </div>

                @if($extracurricular->achievements->isNotEmpty())
                  <div class="mt-5 pt-4 border-top" style="border-color:rgba(27,94,66,0.1)!important;">
                    <h5 class="fw-bold mb-4" style="color:var(--mansaba-dark);">
                      <i class="ti tabler-trophy me-2" style="color:var(--mansaba-gold);"></i>
                      Prestasi Terkait
                    </h5>
                    <div class="row g-3">
                      @foreach($extracurricular->achievements as $prestasi)
                        <div class="col-md-6">
                          <div class="d-flex align-items-start gap-3 p-3" style="background:rgba(201,151,43,0.05);border-radius:8px;border:1px solid rgba(201,151,43,0.15);">
                            <div style="width:40px;height:40px;background:var(--mansaba-gold);border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                              <i class="ti tabler-trophy" style="color:#fff;font-size:1.1rem;"></i>
                            </div>
                            <div>
                              <div class="fw-bold" style="color:var(--mansaba-dark);font-size:0.9rem;">{{ $prestasi->title }}</div>
                              <small class="text-muted">{{ $prestasi->student_name }} · {{ $prestasi->level ?? '-' }}</small>
                              @if($prestasi->achievement_date)
                                <br><small style="color:var(--mansaba-green);">{{ $prestasi->achievement_date->format('d M Y') }}</small>
                              @endif
                            </div>
                          </div>
                        </div>
                      @endforeach
                    </div>
                  </div>
                @endif

                <div class="mt-5 pt-4 border-top" style="border-color:rgba(27,94,66,0.1)!important;">
                  <a href="{{ url('public/extracurriculars') }}" class="btn px-4 py-2" style="background:var(--mansaba-green);color:#fff;border-radius:5px;font-weight:600;">
                    <i class="ti tabler-arrow-left me-2"></i>Kembali
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>
</section>
@endsection
