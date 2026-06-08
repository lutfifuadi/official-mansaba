@extends('layouts/layoutFront')
@section('title', 'Profil Sekolah')
@php $breadcrumbs = [['name' => 'Beranda', 'url' => url('/')], ['name' => 'Profil Sekolah']]; @endphp

@section('content')

{{-- Page Header --}}
<div class="mansaba-page-header">
  <div class="container text-center position-relative" style="z-index:1;">
    <nav aria-label="breadcrumb" class="breadcrumb-islami mb-2">
      <ol class="breadcrumb justify-content-center">
        <li class="breadcrumb-item"><a href="{{ route('home') }}" style="color:rgba(255,255,255,0.6);text-decoration:none;">Beranda</a></li>
        <li class="breadcrumb-item active">Profil Sekolah</li>
      </ol>
    </nav>
    <h1 class="mb-2">🏫 Profil MAN 1 Kota Bandung</h1>
    <p class="header-subtitle">Mengenal lebih dekat Madrasah Aliyah Negeri 1 Kota Bandung</p>
  </div>
</div>

<section class="section-bg-cream py-6">
  <div class="container-xxl">

  {{-- Quote Islami --}}
  <div class="text-center mb-5">
    <div class="d-inline-block px-4 py-3" style="background:linear-gradient(135deg,rgba(27,94,66,0.06),rgba(201,151,43,0.06));border-radius:5px;border:1px solid rgba(27,94,66,0.1);">
      <p class="mb-1" style="font-family:'Amiri',serif;font-size:1.5rem;color:var(--mansaba-gold);direction:rtl;">
        طَلَبُ الْعِلْمِ فَرِيضَةٌ عَلَى كُلِّ مُسْلِمٍ
      </p>
      <small class="text-muted fst-italic">"Menuntut ilmu adalah kewajiban bagi setiap Muslim" — HR. Ibnu Majah</small>
    </div>
  </div>

  {{-- Sejarah --}}
  <div class="mansaba-card mb-4">
    <div class="card-body p-4">
      <div class="d-flex align-items-center gap-3 mb-3">
        <div style="width:46px;height:46px;background:linear-gradient(135deg,var(--mansaba-green),var(--mansaba-green-mid));border-radius:5px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
          <i class="ti tabler-history" style="color:#fff;font-size:1.3rem;"></i>
        </div>
        <h4 class="fw-bold mb-0" style="color:var(--mansaba-dark);">Sejarah</h4>
      </div>
      <p style="line-height:1.8;color:var(--mansaba-text);">MAN 1 Kota Bandung didirikan pada tanggal 27 Januari 1992 berdasarkan Surat Keputusan Menteri Agama Nomor 42 tahun 1992. Berstatus sebagai Madrasah Aliyah Negeri, madrasah ini hadir di bawah naungan Kementerian Agama dengan komitmen mencetak generasi muda yang beriman, berilmu, dan berakhlak mulia.</p>
      <p style="line-height:1.8;color:var(--mansaba-text);">Terletak di Jalan Haji Alpi Cijeerah, Kelurahan Cibuntu, Kecamatan Bandung Kulon, Kota Bandung, madrasah ini berdiri di atas lahan seluas 26.070 meter persegi — menjadikannya salah satu madrasah dengan lingkungan terluas dan paling representatif di Kota Bandung. Dengan akreditasi A yang diraih pada 22 Juni 2020 (SK BAN-SM Nomor 458/BAN-SM/SK/2020), MAN 1 Kota Bandung terus berbenah menjadi lembaga pendidikan Islam yang kompetitif dan berkualitas.</p>
      <p style="line-height:1.8;color:var(--mansaba-text);" class="mb-0">Hingga saat ini, MAN 1 Kota Bandung terus berkembang menjadi madrasah unggulan dengan lebih dari 1.200 siswa aktif dan 84 tenaga pendidik profesional. Madrasah ini tidak hanya fokus pada prestasi akademik, tetapi juga pengembangan karakter dan keterampilan siswa melalui berbagai kegiatan ekstrakurikuler dan pembiasaan positif. Dengan tiga jurusan unggulan — IPA, IPS, dan Agama — MAN 1 Kota Bandung siap melahirkan lulusan yang berdaya saing global.</p>
    </div>
  </div>

  {{-- Visi & Misi --}}
  <div class="row g-4 mb-4">
    <div class="col-md-6">
      <div class="mansaba-visi-card h-100">
        <div class="d-flex align-items-center gap-3 mb-3">
          <div style="width:42px;height:42px;background:linear-gradient(135deg,var(--mansaba-green),var(--mansaba-green-light));border-radius:5px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <i class="ti tabler-eye" style="color:#fff;font-size:1.2rem;"></i>
          </div>
          <h5 class="fw-bold mb-0" style="color:var(--mansaba-green);">Visi</h5>
        </div>
        <p class="fst-italic mb-0" style="line-height:1.8;color:var(--mansaba-text);">
          &ldquo;{{ $globalSettings['visi'] ?? 'Terwujudnya peserta didik yang beriman, bertakwa, berilmu, berkarakter, berbudaya, dan berdaya saing global.' }}&rdquo;
        </p>
      </div>
    </div>
    <div class="col-md-6">
      <div class="mansaba-misi-card h-100">
        <div class="d-flex align-items-center gap-3 mb-3">
          <div style="width:42px;height:42px;background:linear-gradient(135deg,var(--mansaba-gold),var(--mansaba-gold-light));border-radius:5px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <i class="ti tabler-target" style="color:#fff;font-size:1.2rem;"></i>
          </div>
          <h5 class="fw-bold mb-0" style="color:#8B6914;">Misi</h5>
        </div>
        <ol class="mb-0 ps-4" style="line-height:2;color:var(--mansaba-text);">
          @php
            $misiItems = !empty($globalSettings['misi']) ? explode('|', $globalSettings['misi']) : [
              'Menyelenggarakan pendidikan yang berbasis iman dan takwa.',
              'Mengembangkan potensi akademik dan non-akademik peserta didik secara optimal.',
              'Membentuk karakter peserta didik yang Islami, disiplin, dan bertanggung jawab.',
              'Menciptakan lingkungan madrasah yang bersih, nyaman, dan kondusif.',
              'Membangun kerjasama dalam rangka peningkatan mutu pendidikan.',
            ];
          @endphp
          @foreach ($misiItems as $item)
            <li>{{ trim($item) }}</li>
          @endforeach
        </ol>
      </div>
    </div>
  </div>

  {{-- Tujuan --}}
  <div class="mansaba-card mb-4">
    <div class="card-body p-4">
      <div class="d-flex align-items-center gap-3 mb-3">
        <div style="width:46px;height:46px;background:linear-gradient(135deg,var(--mansaba-maroon),#9B3D52);border-radius:5px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
          <i class="ti tabler-flag-2" style="color:#fff;font-size:1.3rem;"></i>
        </div>
        <h4 class="fw-bold mb-0" style="color:var(--mansaba-dark);">Tujuan</h4>
      </div>
      <div class="row g-3">
        @foreach ([
          'Menghasilkan lulusan yang beriman, bertakwa, dan berakhlak mulia.',
          'Meningkatkan prestasi akademik dan non-akademik peserta didik.',
          'Membudayakan literasi, numerasi, dan keterampilan abad 21.',
          'Mengembangkan jiwa kewirausahaan dan kemandirian peserta didik.',
          'Mewujudkan madrasah yang unggul dalam pelayanan dan pengelolaan.',
          'Membangun citra madrasah sebagai lembaga pendidikan terpercaya.',
        ] as $tujuan)
        <div class="col-md-6">
          <div class="d-flex align-items-start gap-2">
            <i class="ti tabler-circle-check-filled" style="color:var(--mansaba-green);font-size:1.1rem;flex-shrink:0;margin-top:2px;"></i>
            <span style="font-size:0.9rem;color:var(--mansaba-text);line-height:1.6;">{{ $tujuan }}</span>
          </div>
        </div>
        @endforeach
      </div>
    </div>
  </div>

  {{-- Data Siswa & Guru --}}
  <div class="row g-4 mb-4">
    <div class="col-md-6">
      <div class="mansaba-card h-100">
        <div class="card-body p-4">
          <div class="d-flex align-items-center gap-3 mb-3">
            <div style="width:46px;height:46px;background:linear-gradient(135deg,#1A73E8,#4A90D9);border-radius:5px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
              <i class="ti tabler-users" style="color:#fff;font-size:1.3rem;"></i>
            </div>
            <h4 class="fw-bold mb-0" style="color:var(--mansaba-dark);">Data Siswa</h4>
          </div>
          <div class="table-responsive">
            <table class="table mansaba-table-profile table-bordered mb-0">
              <tbody>
                <tr><th style="width:200px;">Total Siswa</th><td><strong>2.945</strong> (L: 1.215, P: 1.730)</td></tr>
                <tr><th>Rombongan Belajar</th><td>36 Rombel</td></tr>
                <tr><th>Jurusan</th><td>IPA, IPS, Bahasa, Keagamaan</td></tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="mansaba-card h-100">
        <div class="card-body p-4">
          <div class="d-flex align-items-center gap-3 mb-3">
            <div style="width:46px;height:46px;background:linear-gradient(135deg,#E67E22,#F39C12);border-radius:5px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
              <i class="ti tabler-school" style="color:#fff;font-size:1.3rem;"></i>
            </div>
            <h4 class="fw-bold mb-0" style="color:var(--mansaba-dark);">Data Guru & Tendik</h4>
          </div>
          <div class="table-responsive">
            <table class="table mansaba-table-profile table-bordered mb-0">
              <tbody>
                <tr><th style="width:200px;">Total Guru Aktif</th><td><strong>95</strong> (L: 47, P: 48)</td></tr>
                <tr><th>Tenaga Kependidikan</th><td>19 Orang</td></tr>
                <tr><th>Total Personil</th><td>114 Orang</td></tr>
                <tr><th>Guru PNS</th><td>65 Orang</td></tr>
                <tr><th>Guru Non-PNS</th><td>30 Orang</td></tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- Data Pokok --}}
  <div class="mansaba-card mb-5">
    <div class="card-body p-4">
      <div class="d-flex align-items-center gap-3 mb-4">
        <div style="width:46px;height:46px;background:linear-gradient(135deg,#1A1A2E,#2C3E50);border-radius:5px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
          <i class="ti tabler-database" style="color:#fff;font-size:1.3rem;"></i>
        </div>
        <h4 class="fw-bold mb-0" style="color:var(--mansaba-dark);">Data Pokok Sekolah</h4>
      </div>
      <div class="table-responsive">
        <table class="table mansaba-table-profile table-bordered mb-0">
          <tbody>
            @foreach ([
              ['Nama Madrasah',   'MAN 1 Kota Bandung'],
              ['NSM',             '131132730001'],
              ['NPSN',            '20277069'],
              ['Status',          '<span class="badge px-3 py-1" style="background:rgba(27,94,66,0.1);color:var(--mansaba-green);border-radius:5px;font-weight:700;">Negeri</span>'],
              ['Akreditasi',      '<span class="badge px-3 py-1" style="background:rgba(201,151,43,0.1);color:#8B6914;border-radius:5px;font-weight:700;">A (Unggul)</span>'],
              ['SK Akreditasi',   '458/BAN-SM/SK/2020 (22 Juni 2020)'],
              ['Alamat',          'JL. HAJI ALPI CIJERAH, Cibuntu, Kec. Bandung Kulon, Kota Bandung, Jawa Barat'],
              ['Luas Tanah',      '26.070 m²'],
              ['Kepala Madrasah', 'Yayan Ristaman Jaya, S.Pd., S.E., M.M.'],
              ['Tahun Berdiri',   '1992 (SK KMA No. 42 Tahun 1992)'],
              ['Jurusan',         'IPA, IPS, Agama'],
            ] as $row)
            <tr>
              <th style="width:200px;">{{ $row[0] }}</th>
              <td>{!! $row[1] !!}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>

  </div>
</section>

@endsection
