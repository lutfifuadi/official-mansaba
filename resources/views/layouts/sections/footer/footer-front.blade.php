<!-- Footer Islami Mansaba: Start -->
<footer class="mansaba-footer">
  <div class="container position-relative" style="z-index:1;">

    <!-- Top Row -->
    <div class="row g-5 mb-4">

      <!-- Brand + Deskripsi -->
      <div class="col-lg-4">
        <div class="d-flex align-items-center gap-3 mb-3">
          <div style="width:44px;height:44px;background:rgba(201,151,43,0.15);border:2px solid rgba(201,151,43,0.35);border-radius:5px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            @if (!empty($globalSettings['school_logo']))
              @php $logoUrl = str_starts_with($globalSettings['school_logo'], 'http') ? $globalSettings['school_logo'] : \App\Helpers\StorageHelper::url($globalSettings['school_logo']); @endphp
              <img src="{{ $logoUrl }}" alt="Logo" loading="lazy" style="max-height: 40px; max-width: 40px; object-fit: contain;">
            @else
              <svg width="26" height="26" viewBox="0 0 24 24" fill="none"><path d="M12 2L2 7v10l10 5 10-5V7L12 2z" fill="#C9972B" opacity=".3"/><path d="M12 2L2 7l10 5 10-5L12 2z" fill="#C9972B"/></svg>
            @endif
          </div>
          <div>
            <span class="footer-brand-text d-block">{{ $globalSettings['site_name'] ?? 'MAN 1 Kota Bandung' }}</span>
            <small style="color:rgba(255,255,255,0.45);font-size:0.75rem;">Madrasah Aliyah Negeri</small>
          </div>
        </div>
        <p class="footer-desc mb-4">
          {{ $globalSettings['site_description'] ?? 'Madrasah Aliyah Negeri 1 Kota Bandung — mewujudkan generasi yang beriman, berilmu, berkarakter, dan berdaya saing global.' }}
        </p>
        <div class="d-flex gap-2">
          @php
            $socialLinks = [
              'facebook'  => ['icon' => 'brand-facebook',  'label' => 'Facebook'],
              'instagram' => ['icon' => 'brand-instagram', 'label' => 'Instagram'],
              'youtube'   => ['icon' => 'brand-youtube',   'label' => 'YouTube'],
              'twitter'   => ['icon' => 'brand-x',         'label' => 'Twitter/X'],
              'tiktok'    => ['icon' => 'brand-tiktok',    'label' => 'TikTok'],
            ];
          @endphp
          @foreach ($socialLinks as $key => $social)
            @if (!empty($globalSettings[$key]))
              <a href="{{ $globalSettings[$key] }}" class="footer-social-btn" title="{{ $social['label'] }}" target="_blank" rel="noopener">
                <i class="ti tabler-{{ $social['icon'] }}"></i>
              </a>
            @endif
          @endforeach
        </div>
      </div>

      <!-- Link Navigasi -->
      <div class="col-6 col-lg-2">
        <h6 class="footer-heading">Navigasi</h6>
        <a href="{{ route('home') }}"                        class="footer-link">Beranda</a>
        <a href="{{ route('public.news') }}"              class="footer-link">Berita</a>
        <a href="{{ route('public.galleries') }}"         class="footer-link">Galeri</a>
        <a href="{{ route('public.achievements') }}"      class="footer-link">Prestasi</a>
        <a href="{{ route('public.extracurriculars') }}"  class="footer-link">Ekstrakurikuler</a>
        <a href="{{ route('public.profile') }}"           class="footer-link">Profil Sekolah</a>
      </div>

      <!-- Layanan -->
      <div class="col-6 col-lg-2">
        <h6 class="footer-heading">Layanan</h6>
        @foreach ($footerServices as $svc)
          @if (!empty($svc->url) && $svc->url !== '#')
            <a href="{{ $svc->url }}" class="footer-link" target="_blank" rel="noopener">{{ $svc->name }}</a>
          @endif
        @endforeach
        <a href="{{ route('login') }}" class="footer-link">Login</a>
      </div>

      <!-- Kontak -->
      <div class="col-lg-4">
        <h6 class="footer-heading">Kontak & Lokasi</h6>
        <div class="d-flex align-items-start gap-3 mb-3">
          <i class="ti tabler-map-pin mt-1" style="color:var(--mansaba-gold-light);font-size:1.1rem;flex-shrink:0;"></i>
          <span class="footer-desc">{{ $globalSettings['address'] ?? 'JL. HAJI ALPI CIJERAH, Kelurahan Cibuntu, Kec. Bandung Kulon, Kota Bandung, Jawa Barat' }}</span>
        </div>
        <div class="d-flex align-items-center gap-3 mb-3">
          <i class="ti tabler-phone" style="color:var(--mansaba-gold-light);font-size:1.1rem;flex-shrink:0;"></i>
          <span class="footer-desc">{{ $globalSettings['phone'] ?? '(022) 12345678' }}</span>
        </div>
        <div class="d-flex align-items-center gap-3 mb-3">
          <i class="ti tabler-mail" style="color:var(--mansaba-gold-light);font-size:1.1rem;flex-shrink:0;"></i>
          <span class="footer-desc">{{ $globalSettings['email'] ?? 'info@man1bandung.sch.id' }}</span>
        </div>
        <div class="d-flex align-items-center gap-3">
          <i class="ti tabler-clock" style="color:var(--mansaba-gold-light);font-size:1.1rem;flex-shrink:0;"></i>
          <span class="footer-desc">{{ $globalSettings['operational_hours'] ?? 'Senin – Jumat: 07.00 – 15.30 WIB' }}</span>
        </div>
      </div>

    </div>

    <!-- Footer Bottom -->
    <div class="footer-bottom d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
      <span>
        © <script>document.write(new Date().getFullYear())</script>
        <a href="#">{{ $globalSettings['site_name'] ?? 'MAN 1 Kota Bandung' }}</a>. Hak Cipta Dilindungi.
      </span>
      <span>
        Dibuat dengan <span style="color:#e74c3c;">❤️</span> untuk pendidikan Islam yang berkualitas
      </span>
    </div>

  </div>
</footer>
<!-- Footer Islami Mansaba: End -->
