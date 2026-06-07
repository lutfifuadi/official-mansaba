@php
$configData = Helper::appClasses();
use Illuminate\Support\Str;
@endphp

@extends('layouts/layoutFront')

@section('title', 'Beranda')

@section('content')

{{-- ════════════════════════════════════
    HERO SECTION
════════════════════════════════════ --}}
<div class="mansaba-hero text-center">
  <div class="hero-content">
    <span class="hero-badge">🕌 {{ $settings['site_name'] ?? 'Madrasah Aliyah Negeri 1 Kota Bandung' }}</span>
    <h1>{{ $settings['hero_title'] ?? 'Berprestasi, Berkarakter' }}<br><span style="color:var(--mansaba-gold-light);">{{ $settings['hero_highlight'] ?? 'Berakhlak Mulia' }}</span></h1>
    <p class="hero-subtitle">{{ $settings['hero_subtitle'] ?? 'Mewujudkan generasi Islami yang unggul dalam ilmu, iman, dan amal untuk kejayaan umat dan bangsa.' }}</p>
    <div class="d-flex justify-content-center gap-3 flex-wrap">
      <a href="#berita" class="btn btn-mansaba-primary">
        <i class="ti tabler-news me-2"></i>Berita Terbaru
      </a>
      <a href="{{ route('public.profile') }}" class="btn btn-mansaba-outline">
        <i class="ti tabler-school me-2"></i>Profil Sekolah
      </a>
    </div>
  </div>
</div>

{{-- ════════════════════════════════════
    BANNER PENGUMUMAN
════════════════════════════════════ --}}
@if($announcements->count() > 0)
  <section class="section-bg-cream py-5">
    <div class="container-xxl">
      <div class="mansaba-section-header">
        <span class="section-label">📢 Pengumuman Resmi</span>
        <h2>Informasi & Pengumuman</h2>
        <div class="mansaba-divider"></div>
      </div>

      <div class="swiper announcementSwiper">
        <div class="swiper-wrapper">
          @foreach($announcements as $a)
            <div class="swiper-slide">
              <div class="announcement-card">
                <div class="announcement-card-header d-flex justify-content-between align-items-center mb-3">
                  <span class="announcement-date">
                    <i class="ti tabler-calendar me-1"></i>{{ $a->created_at->format('d M Y') }}
                  </span>
                  <span class="announcement-badge-new">Terbaru</span>
                </div>
                <div class="d-flex align-items-start gap-3">
                  <div class="announcement-icon-wrap">
                    <i class="ti tabler-volume"></i>
                  </div>
                  <div class="announcement-content-wrap flex-grow-1">
                    <h5 class="announcement-title">{{ $a->title }}</h5>
                    <div class="announcement-body-text">{!! $a->content !!}</div>
                  </div>
                </div>
              </div>
            </div>
          @endforeach
        </div>
        <div class="swiper-pagination announcement-pagination mt-4"></div>
      </div>
    </div>
  </section>
@endif

{{-- ════════════════════════════════════
    STATS
════════════════════════════════════ --}}
<section class="section-bg-cream py-5">
  <div class="container-xxl">
    <div class="mansaba-stats-wrapper" style="{{ $announcements->count() > 0 ? 'margin-top: 0 !important;' : '' }}">
      <div class="mansaba-stats-strip">
        <div class="row align-items-center g-3">
          <div class="col-6 col-md stat-item">
            <div class="stat-number">{{ $stats['student_count'] }}+</div>
            <div class="stat-label">{{ $settings['stats_label_1'] ?? 'Siswa Aktif' }}</div>
          </div>
          <div class="col-auto d-none d-md-block"><div class="stat-divider"></div></div>
          <div class="col-6 col-md stat-item">
            <div class="stat-number">{{ $stats['teacher_count'] }}+</div>
            <div class="stat-label">{{ $settings['stats_label_2'] ?? 'Tenaga Pendidik' }}</div>
          </div>
          <div class="col-auto d-none d-md-block"><div class="stat-divider"></div></div>
          <div class="col-6 col-md stat-item">
            <div class="stat-number">{{ $stats['years_active'] }}+</div>
            <div class="stat-label">{{ $settings['stats_label_3'] ?? 'Tahun Berdiri' }}</div>
          </div>
          <div class="col-auto d-none d-md-block"><div class="stat-divider"></div></div>
          <div class="col-6 col-md stat-item">
            <div class="stat-number">{{ $stats['achievement_count'] }}+</div>
            <div class="stat-label">{{ $settings['stats_label_4'] ?? 'Prestasi Diraih' }}</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- ════════════════════════════════════
    SAMBUTAN KEPALA SEKOLAH
════════════════════════════════════ --}}
@if (!empty($settings['headmaster_message']))
  <section class="section-bg-cream py-5">
    <div class="container-xxl">
      <div class="mansaba-section-header">
        <span class="section-label">👤 {{ $settings['headmaster_label'] ?? 'Pimpinan' }}</span>
        <h2>{{ $settings['headmaster_title'] ?? 'Sambutan Kepala Sekolah' }}</h2>
        <div class="mansaba-divider"></div>
      </div>
      <div class="row justify-content-center">
        <div class="col-lg-10">
          <div class="mansaba-headmaster-card">
            <div class="row align-items-center g-4">
              <div class="col-md-4 text-center">
                <div class="headmaster-photo-wrapper">
                  <img src="{{ !empty($settings['headmaster_photo']) ? $settings['headmaster_photo'] : 'https://ui-avatars.com/api/?name=' . urlencode($settings['headmaster_name'] ?? 'Kepala Sekolah') . '&background=1B5E42&color=fff&size=200' }}"
                       alt="{{ $settings['headmaster_name'] ?? 'Kepala Sekolah' }}"
                       loading="lazy"
                       class="headmaster-photo">
                </div>
                <h5 class="mt-3 mb-1 fw-bold" style="color:var(--mansaba-dark);">{{ $settings['headmaster_name'] ?? 'Kepala Sekolah' }}</h5>
                <small class="text-muted">{{ $settings['headmaster_name'] ? 'Kepala ' . $settings['site_name'] : 'Kepala Sekolah' }}</small>
              </div>
              <div class="col-md-8">
                <div class="headmaster-message">
                  <i class="ti tabler-quote" style="font-size:1.8rem;color:var(--mansaba-gold);opacity:0.4;"></i>
                  <p class="mb-0" style="font-size:0.95rem;line-height:1.8;color:var(--mansaba-text);font-style:italic;">
                    {{ $settings['headmaster_message'] }}
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endif

{{-- ════════════════════════════════════
    BERITA TERBARU
════════════════════════════════════ --}}
<section id="berita" class="section-bg-cream py-5">
  <div class="container-xxl">
    <div class="mansaba-section-header">
      <span class="section-label">📰 {{ $settings['section_news_label'] ?? 'Informasi & Kegiatan' }}</span>
      <h2>{{ $settings['section_news_title'] ?? 'Berita Terbaru' }}</h2>
      <div class="mansaba-divider"></div>
      <p>{{ $settings['section_news_desc'] ?? 'Ikuti perkembangan terkini dari lingkungan MAN 1 Kota Bandung' }}</p>
    </div>

    <div class="row g-4">
      @forelse ($news ?? [] as $item)
        <div class="col-md-4 mansaba-fade-up">
          <div class="mansaba-card mansaba-card-news h-100">
            <div class="news-card-img" style="height:200px;overflow:hidden;position:relative;">
              <img src="{{ $item->image ? \App\Helpers\StorageHelper::url($item->image) : asset('storage/default-news.jpg') }}"
                   alt="{{ $item->title ?? 'Berita' }}"
                   loading="lazy"
                   style="width:100%;height:100%;object-fit:cover;transition:transform 0.4s ease;"
                   onmouseover="this.style.transform='scale(1.05)'"
                   onmouseout="this.style.transform='scale(1)'">
              <div style="position:absolute;top:12px;left:12px;">
                <span class="news-category-badge">{{ $item->category ?? 'Berita' }}</span>
              </div>
            </div>
            <div class="card-body d-flex flex-column">
              <small class="text-muted d-flex align-items-center gap-1 mb-2">
                <i class="ti tabler-calendar" style="font-size:0.8rem;"></i>
                {{ $item->created_at ? $item->created_at->format('d M Y') : '-' }}
              </small>
              <h5 class="card-title">
                <a href="{{ route('public.news-detail', $item->slug ?? $item->id) }}" style="text-decoration:none;color:inherit;">
                  {{ $item->title ?? 'Judul Berita' }}
                </a>
              </h5>
              <p class="card-text text-muted flex-grow-1 mb-3" style="font-size:0.88rem;line-height:1.6;">
                {{ Str::limit(strip_tags($item->excerpt ?? $item->content ?? ''), 110) }}
              </p>
              <a href="{{ route('public.news-detail', $item->slug ?? $item->id) }}" class="btn-read-more">
                Baca Selengkapnya <i class="ti tabler-arrow-right"></i>
              </a>
            </div>
          </div>
        </div>
      @empty
        <div class="col-12">
          <div class="mansaba-card text-center py-5">
            <i class="ti tabler-news" style="font-size:3rem;color:#ccc;"></i>
            <p class="text-muted mt-3 mb-0">Belum ada berita tersedia.</p>
          </div>
        </div>
      @endforelse
    </div>

    <div class="text-center mt-4">
      <a href="{{ route('public.news') }}" class="btn btn-outline-success px-4" style="border-color:var(--mansaba-green);color:var(--mansaba-green);border-radius:5px;font-weight:600;">
        <i class="ti tabler-eye me-2"></i>Lihat Semua Berita
      </a>
    </div>
  </div>
</section>

{{-- ════════════════════════════════════
    PRESTASI
════════════════════════════════════ --}}
<section class="section-bg-soft py-5">
  <div class="container-xxl">
    <div class="mansaba-section-header">
      <span class="section-label">🏆 {{ $settings['section_achievement_label'] ?? 'Keunggulan & Pencapaian' }}</span>
      <h2>{{ $settings['section_achievement_title'] ?? 'Prestasi Membanggakan' }}</h2>
      <div class="mansaba-divider"></div>
      <p>{{ $settings['section_achievement_desc'] ?? 'Prestasi yang telah diraih siswa-siswi MAN 1 Kota Bandung' }}</p>
    </div>
    <div class="row g-4">
      @forelse ($achievements ?? [] as $item)
        <div class="col-md-4 mansaba-fade-up">
          <div class="mansaba-card mansaba-card-achievement h-100">
            <div class="card-body">
              <div class="d-flex align-items-start gap-3">
                <div class="achievement-icon">🏅</div>
                <div class="flex-grow-1">
                  <span class="badge-mansaba-gold badge px-2 py-1 mb-2">{{ $item->category ?? 'Prestasi' }}</span>
                  <h6 class="fw-bold mb-1" style="color:var(--mansaba-dark);font-size:0.92rem;">{{ $item->title ?? 'Judul Prestasi' }}</h6>
                  <p class="text-muted mb-2" style="font-size:0.83rem;line-height:1.5;">{{ Str::limit(strip_tags($item->description ?? ''), 90) }}</p>
                  <small class="text-muted d-flex align-items-center gap-1">
                    <i class="ti tabler-calendar" style="font-size:0.75rem;"></i>
                    {{ $item->date ?? $item->year ?? '-' }}
                  </small>
                </div>
              </div>
            </div>
          </div>
        </div>
      @empty
        <div class="col-12 text-center py-4">
          <i class="ti tabler-trophy" style="font-size:3rem;color:#ccc;"></i>
          <p class="text-muted mt-3 mb-0">Belum ada data prestasi.</p>
        </div>
      @endforelse
    </div>
    <div class="text-center mt-4">
      <a href="{{ route('public.achievements') }}" class="btn btn-outline-warning px-4" style="border-color:var(--mansaba-gold);color:var(--mansaba-gold);border-radius:5px;font-weight:600;">
        <i class="ti tabler-trophy me-2"></i>Lihat Semua Prestasi
      </a>
    </div>
  </div>
</section>

{{-- ════════════════════════════════════
    GALERI FOTO — SWIPER SLIDER
════════════════════════════════════ --}}
@section('vendor-style')
@parent
@vite(['resources/assets/vendor/libs/swiper/swiper.scss'])
@endsection

<section class="section-bg-white py-5">
  <div class="container-xxl">
    <div class="mansaba-section-header">
      <span class="section-label">📸 {{ $settings['section_gallery_label'] ?? 'Dokumentasi' }}</span>
      <h2>{{ $settings['section_gallery_title'] ?? 'Galeri Kegiatan' }}</h2>
      <div class="mansaba-divider"></div>
      <p>{{ $settings['section_gallery_desc'] ?? 'Momen kegiatan dan keseharian di MAN 1 Kota Bandung' }}</p>
    </div>

    <div class="swiper gallerySwiper">
      <div class="swiper-wrapper">
        @forelse ($galleries ?? [] as $item)
          @php $firstImg = $item->images->first(); @endphp
          <div class="swiper-slide">
            <div class="gallery-slide-inner">
              <img src="{{ $firstImg ? \App\Helpers\StorageHelper::url($firstImg->image) : asset('assets/img/placeholder.jpg') }}"
                   alt="{{ $item->title ?? 'Foto Kegiatan' }}"
                   loading="lazy" />
              @if ($item->title ?? false)
                <div class="gallery-slide-caption">
                  <span>{{ $item->title }}</span>
                </div>
              @endif
            </div>
          </div>
        @empty
          @for ($i = 1; $i <= 6; $i++)
            <div class="swiper-slide">
              <div class="gallery-slide-inner">
                <img src="https://picsum.photos/seed/gallery{{ $i }}/600/400"
                     alt="Galeri {{ $i }}"
                     loading="lazy" />
                <div class="gallery-slide-caption">
                  <span>Galeri {{ $i }}</span>
                </div>
              </div>
            </div>
          @endfor
        @endforelse
      </div>
      <div class="swiper-pagination"></div>
      <div class="swiper-button-next"></div>
      <div class="swiper-button-prev"></div>
    </div>

    <div class="text-center mt-4">
      <a href="{{ route('public.galleries') }}" class="btn btn-outline-success px-4" style="border-color:var(--mansaba-green);color:var(--mansaba-green);border-radius:5px;font-weight:600;">
        <i class="ti tabler-photo me-2"></i>Lihat Semua Galeri
      </a>
    </div>
  </div>
</section>

@section('page-script')
@vite(['resources/assets/vendor/libs/swiper/swiper.js'])
<script>
document.addEventListener('DOMContentLoaded', function() {
  new Swiper('.gallerySwiper', {
    slidesPerView: 2,
    spaceBetween: 16,
    loop: true,
    autoplay: {
      delay: 3000,
      disableOnInteraction: false,
    },
    pagination: {
      el: '.swiper-pagination',
      clickable: true,
    },
    navigation: {
      nextEl: '.swiper-button-next',
      prevEl: '.swiper-button-prev',
    },
    breakpoints: {
      576: { slidesPerView: 3, spaceBetween: 18 },
      992: { slidesPerView: 4, spaceBetween: 20 },
      1200: { slidesPerView: 5, spaceBetween: 24 },
    },
  });

  new Swiper('.announcementSwiper', {
    slidesPerView: 1,
    spaceBetween: 24,
    loop: {{ $announcements->count() > 1 ? 'true' : 'false' }},
    autoplay: {
      delay: 5000,
      disableOnInteraction: true,
    },
    pagination: {
      el: '.announcement-pagination',
      clickable: true,
    },
    breakpoints: {
      992: { slidesPerView: 2, spaceBetween: 24 },
    },
  });
});
</script>
@endsection

{{-- ════════════════════════════════════
    EKSTRAKURIKULER
════════════════════════════════════ --}}
<section class="section-bg-white py-5">
  <div class="container-xxl">
    <div class="mansaba-section-header">
      <span class="section-label">⚽ {{ $settings['section_extracurricular_label'] ?? 'Pengembangan Diri' }}</span>
      <h2>{{ $settings['section_extracurricular_title'] ?? 'Kegiatan Ekstrakurikuler' }}</h2>
      <div class="mansaba-divider"></div>
      <p>{{ $settings['section_extracurricular_desc'] ?? 'Sarana pengembangan bakat dan minat siswa di luar akademik' }}</p>
    </div>

    @php
      $ekskulColors = [
        ['bg' => 'linear-gradient(135deg,#1B5E42,#2D8A5E)', 'icon' => 'users'],
        ['bg' => 'linear-gradient(135deg,#C9972B,#E8B84B)', 'icon' => 'music'],
        ['bg' => 'linear-gradient(135deg,#7B2D3E,#A84B5E)', 'icon' => 'ball-football'],
        ['bg' => 'linear-gradient(135deg,#1A66B3,#3A8AD9)', 'icon' => 'book'],
        ['bg' => 'linear-gradient(135deg,#7B3FA0,#A86BC9)', 'icon' => 'camera'],
        ['bg' => 'linear-gradient(135deg,#1A8C68,#3AB88E)', 'icon' => 'run'],
      ];
    @endphp

    <div class="row g-4">
      @forelse ($extracurriculars ?? [] as $item)
        @php
          $c = $ekskulColors[$loop->index % count($ekskulColors)];
          $slug = $item->slug ?? $item->id;
        @endphp
        <div class="col-6 col-md-4 col-lg-3 mansaba-fade-up">
          <a href="{{ route('public.extracurricular-detail', $slug) }}" class="text-decoration-none">
            <div class="ekskul-card h-100">
              <div class="ekskul-card-top" style="background:{{ $c['bg'] }};">
                <div class="ekskul-icon-circle">
                  <i class="ti tabler-{{ $item->icon ?? $c['icon'] }}"></i>
                </div>
              </div>
              <div class="ekskul-card-body">
                @if ($item->category ?? false)
                  <span class="ekskul-badge">{{ $item->category }}</span>
                @endif
                <h6 class="ekskul-name">{{ $item->name ?? 'Ekskul' }}</h6>
                @if ($item->coach ?? false)
                  <p class="ekskul-coach">
                    <i class="ti tabler-chalkboard"></i> {{ $item->coach }}
                  </p>
                @endif
                @if ($item->schedule ?? false)
                  <p class="ekskul-schedule">
                    <i class="ti tabler-clock"></i> {{ $item->schedule }}
                  </p>
                @endif
                <span class="ekskul-link">
                  Detail <i class="ti tabler-arrow-right"></i>
                </span>
              </div>
            </div>
          </a>
        </div>
      @empty
        <div class="col-12 text-center py-4">
          <i class="ti tabler-ball-football" style="font-size:3rem;color:#ccc;"></i>
          <p class="text-muted mt-3 mb-0">Belum ada data ekstrakurikuler.</p>
        </div>
      @endforelse
    </div>
    <div class="text-center mt-5">
      <a href="{{ route('public.extracurriculars') }}" class="btn btn-mansaba-primary px-5">
        <i class="ti tabler-list me-2"></i>Lihat Semua Ekstrakurikuler
      </a>
    </div>
  </div>
</section>

{{-- ════════════════════════════════════
    LAYANAN ONLINE
════════════════════════════════════ --}}
@php
  $services = [
    'service_ptsp'         => ['icon' => 'building-arch',      'label' => 'PTSP'],
    'service_esurat'       => ['icon' => 'file-text',          'label' => 'ESurat'],
    'service_presensi'     => ['icon' => 'user-check',         'label' => 'Presensi Online'],
    'service_ujian_online' => ['icon' => 'edit',               'label' => 'Ujian Online'],
    'service_rdm'          => ['icon' => 'book',               'label' => 'RDM'],
    'service_emis'         => ['icon' => 'database',           'label' => 'Lokal EMIS'],
  ];
  $activeServices = array_filter($services, fn($key) => !empty($settings[$key]) && $settings[$key] !== '#', ARRAY_FILTER_USE_KEY);
@endphp

@if (count($activeServices) > 0)
  <section class="section-bg-cream py-5">
    <div class="container-xxl">
      <div class="mansaba-section-header">
        <span class="section-label">🖥️ Layanan Online</span>
        <h2>Layanan Digital Madrasah</h2>
        <div class="mansaba-divider"></div>
        <p>Akses berbagai layanan online MAN 1 Kota Bandung dengan mudah dan cepat</p>
      </div>
      <div class="row g-3 justify-content-center">
        @foreach ($activeServices as $key => $svc)
          <div class="col-6 col-md-4 col-lg-2">
            <a href="{{ $settings[$key] }}" target="_blank" rel="noopener" class="mansaba-service-card">
              <div class="service-icon">
                <i class="ti tabler-{{ $svc['icon'] }}"></i>
              </div>
              <span class="service-label">{{ $svc['label'] }}</span>
            </a>
          </div>
        @endforeach
      </div>
    </div>
  </section>
@endif

{{-- ════════════════════════════════════
    INFO KONTAK
════════════════════════════════════ --}}
<section class="section-kontak position-relative overflow-hidden">
  <div class="kontak-bg-ornament"></div>
  <div class="container-xxl position-relative" style="z-index:1;">
    <div class="row justify-content-center text-center mb-5">
      <div class="col-lg-6">
        <span class="kontak-label">📍 {{ $settings['contact_label_title'] ?? 'Hubungi Kami' }}</span>
        <h2 class="kontak-title">{{ $settings['contact_title'] ?? 'Terhubung Dengan Kami' }}</h2>
        <div class="kontak-divider"></div>
        <p class="kontak-desc">{{ $settings['contact_desc'] ?? 'Kami siap melayani pertanyaan dan informasi lebih lanjut' }}</p>
      </div>
    </div>

    <div class="row g-4 justify-content-center">
      <div class="col-lg-4 col-md-6">
        <div class="kontak-card">
          <div class="kontak-card-inner">
            <div class="kontak-icon-wrap" style="background:linear-gradient(135deg,#1B5E42,#2D8A5E);">
              <i class="ti tabler-map-pin"></i>
            </div>
            <div class="kontak-card-body">
              <span class="kontak-badge" style="color:#1B5E42;background:rgba(27,94,66,0.08);">{{ $settings['contact_label_address'] ?? 'Alamat' }}</span>
              <p class="kontak-text">{{ $settings['address'] ?? 'JL. HAJI ALPI CIJERAH, Cibuntu, Kec. Bandung Kulon, Kota Bandung, Jawa Barat' }}</p>
            </div>
            <div class="kontak-card-footer">
              <a href="https://maps.google.com/?q={{ urlencode($settings['address'] ?? 'MAN 1 Kota Bandung') }}" target="_blank" rel="noopener" class="kontak-cta">
                <i class="ti tabler-external-link"></i> Buka di Maps
              </a>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-4 col-md-6">
        <div class="kontak-card">
          <div class="kontak-card-inner">
            <div class="kontak-icon-wrap" style="background:linear-gradient(135deg,#C9972B,#E8B84B);">
              <i class="ti tabler-phone"></i>
            </div>
            <div class="kontak-card-body">
              <span class="kontak-badge" style="color:#8B6914;background:rgba(201,151,43,0.1);">{{ $settings['contact_label_phone'] ?? 'Telepon' }}</span>
              <p class="kontak-text">
                <a href="tel:{{ $settings['phone'] ?? '(022) 12345678' }}" class="kontak-link">{{ $settings['phone'] ?? '(022) 12345678' }}</a>
              </p>
            </div>
            <div class="kontak-card-footer">
              <span class="kontak-footer-info">
                <i class="ti tabler-clock"></i> {{ $settings['operational_hours'] ?? 'Senin–Jumat, 07.00–15.30' }}
              </span>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-4 col-md-6">
        <div class="kontak-card">
          <div class="kontak-card-inner">
            <div class="kontak-icon-wrap" style="background:linear-gradient(135deg,#7B2D3E,#A84B5E);">
              <i class="ti tabler-mail"></i>
            </div>
            <div class="kontak-card-body">
              <span class="kontak-badge" style="color:#7B2D3E;background:rgba(123,45,62,0.08);">{{ $settings['contact_label_email'] ?? 'Email' }}</span>
              <p class="kontak-text">
                <a href="mailto:{{ $settings['email'] ?? 'info@man1bandung.sch.id' }}" class="kontak-link">{{ $settings['email'] ?? 'info@man1bandung.sch.id' }}</a>
              </p>
            </div>
            <div class="kontak-card-footer">
              <span class="kontak-footer-info">
                <i class="ti tabler-send"></i> {{ $settings['site_name'] ?? 'MAN 1 Kota Bandung' }}
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="text-center mt-5">
      <p class="kontak-closing">
        <i class="ti tabler-heart-filled" style="color:var(--mansaba-maroon);font-size:0.9rem;"></i>
        {{ $settings['contact_closing'] ?? 'Terima kasih atas kepercayaan Anda kepada kami' }}
      </p>
    </div>
  </div>
</section>

@endsection
