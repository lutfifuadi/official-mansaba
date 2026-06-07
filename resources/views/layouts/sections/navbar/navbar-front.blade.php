@php
use Illuminate\Support\Facades\Route;
$currentRoute = request()->path();
$navLinks = [
  ['label' => 'Beranda',         'url' => route('home'),                        'path' => ''],
  ['label' => 'Berita',          'url' => route('public.news'),                 'path' => 'berita'],
  ['label' => 'Galeri',          'url' => route('public.galleries'),            'path' => 'galeri'],
  ['label' => 'Prestasi',        'url' => route('public.achievements'),         'path' => 'prestasi'],
  ['label' => 'Ekstrakurikuler', 'url' => route('public.extracurriculars'),     'path' => 'ekstrakurikuler'],
  ['label' => 'Profil',          'url' => route('public.profile'),              'path' => 'profil'],
];
@endphp
<!-- Navbar Islami: Start -->
<nav class="mansaba-navbar layout-navbar shadow-none py-0" id="mansaba-nav">
  <div class="container">
    <div class="navbar navbar-expand-lg px-0 py-2">

      <!-- Brand -->
      <a class="navbar-brand d-flex align-items-center gap-2 me-4" href="{{ url('/') }}">
        <div style="width:38px;height:38px;display:flex;align-items:center;justify-content:center;">
          @if (!empty($globalSettings['school_logo']))
            @php $logoUrl = str_starts_with($globalSettings['school_logo'], 'http') ? $globalSettings['school_logo'] : \App\Helpers\StorageHelper::url($globalSettings['school_logo']); @endphp
            <img src="{{ $logoUrl }}" alt="Logo" loading="lazy" style="max-height: 38px; max-width: 38px; object-fit: contain;">
          @else
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none"><path d="M12 2L2 7v10l10 5 10-5V7L12 2z" fill="#C9972B" opacity=".3"/><path d="M12 2L2 7l10 5 10-5L12 2z" fill="#C9972B"/></svg>
          @endif
        </div>
        <span class="navbar-brand-text d-none d-sm-block" style="font-family:'Trajan Pro',serif;font-weight:700;font-size:1.1rem;color:#fff;line-height:1.1;letter-spacing:0.02em;">
          {{ $globalSettings['site_name'] ?? 'MAN 1 Kota Bandung' }}
        </span>
      </a>

      <!-- Hamburger -->
      <button class="navbar-toggler border-0 ms-auto me-2" type="button"
        id="mansabaNavToggle"
        aria-controls="mansabaNav" aria-expanded="false" aria-label="Toggle navigation"
        style="color:rgba(255,255,255,0.8);font-size:1.3rem;">
        <i class="ti tabler-menu-2"></i>
      </button>

      <!-- Overlay for mobile menu -->
      <div class="landing-menu-overlay" id="mansabaNavOverlay"></div>

      <!-- Mobile Offcanvas Menu (slide from left) -->
      <div class="landing-nav-menu navbar-collapse" id="mansabaNav">
        <div class="offcanvas-header d-flex align-items-center justify-content-between px-3 pt-3 pb-2">
          <a class="d-flex align-items-center gap-2 text-decoration-none" href="{{ url('/') }}">
            <div style="width:34px;height:34px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
              @if (!empty($globalSettings['school_logo']))
                @php $logoUrl = str_starts_with($globalSettings['school_logo'], 'http') ? $globalSettings['school_logo'] : \App\Helpers\StorageHelper::url($globalSettings['school_logo']); @endphp
                <img src="{{ $logoUrl }}" alt="Logo" loading="lazy" style="max-height: 34px; max-width: 34px; object-fit: contain;">
              @else
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M12 2L2 7v10l10 5 10-5V7L12 2z" fill="#C9972B" opacity=".3"/><path d="M12 2L2 7l10 5 10-5L12 2z" fill="#C9972B"/></svg>
              @endif
            </div>
            <span style="font-family:'Trajan Pro',serif;font-weight:700;font-size:1.05rem;color:#fff;line-height:1.1;letter-spacing:0.02em;">
              {{ $globalSettings['site_name'] ?? 'MAN 1 Kota Bandung' }}
            </span>
          </a>
          <button type="button" class="btn-close btn-close-white" id="mansabaNavClose" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body px-3 py-2">
          <ul class="navbar-nav gap-1">
            @foreach($navLinks as $link)
            <li class="nav-item">
              <a class="nav-link {{ str_starts_with($currentRoute, $link['path']) && $link['path'] !== '' ? 'active' : ($currentRoute === '' && $link['path'] === '' ? 'active' : '') }}"
                 href="{{ $link['url'] }}">
                {{ $link['label'] }}
              </a>
            </li>
            @endforeach
          </ul>

          <!-- Mobile Actions -->
          <div class="mt-3 pt-3 border-top d-lg-none" style="border-color: rgba(255,255,255,0.15) !important;">
            <div class="d-flex flex-column gap-2 mb-2">
              @if ($configData['hasCustomizer'] == true)
              <div class="dropdown">
                <button class="btn btn-outline-light btn-sm dropdown-toggle w-100 d-flex align-items-center justify-content-between px-3 py-2"
                        type="button" data-bs-toggle="dropdown" aria-expanded="false" style="color: rgba(255,255,255,0.9); border-color: rgba(255,255,255,0.25); border-radius: 5px;">
                  <span class="d-flex align-items-center gap-2">
                    <i class="ti tabler-sun" style="font-size:1rem;"></i>
                    <span>Pilih Tema</span>
                  </span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end w-100 shadow" style="border: 1px solid rgba(255,255,255,0.15);">
                  <li>
                    <button type="button" class="dropdown-item d-flex align-items-center gap-2" data-bs-theme-value="light">
                      <i class="ti tabler-sun"></i> Terang
                    </button>
                  </li>
                  <li>
                    <button type="button" class="dropdown-item d-flex align-items-center gap-2" data-bs-theme-value="dark">
                      <i class="ti tabler-moon-stars"></i> Gelap
                    </button>
                  </li>
                  <li>
                    <button type="button" class="dropdown-item d-flex align-items-center gap-2" data-bs-theme-value="system">
                      <i class="ti tabler-device-desktop-analytics"></i> Sistem
                    </button>
                  </li>
                </ul>
              </div>
              @endif
              <a href="{{ url('/login') }}" class="btn btn-login btn-sm d-flex align-items-center justify-content-center gap-2 py-2" style="border-radius: 5px;">
                <i class="ti tabler-login" style="font-size:0.95rem;"></i>
                Masuk
              </a>
            </div>
          </div>
        </div>
        <!-- Desktop CTA Button (Moved inside navbar-collapse) -->
        <div class="d-none d-lg-flex align-items-center gap-2 ms-lg-3">
          @if ($configData['hasCustomizer'] == true)
          <div class="dropdown me-1">
            <a class="nav-link dropdown-toggle hide-arrow px-2 py-1" href="javascript:void(0);"
               data-bs-toggle="dropdown" style="color:rgba(255,255,255,0.7);">
              <i class="ti tabler-sun icon-base" id="mansaba-theme-icon" style="font-size:1.1rem;"></i>
            </a>
            <ul class="dropdown-menu dropdown-menu-end" style="min-width:150px;">
              <li>
                <button type="button" class="dropdown-item d-flex align-items-center gap-2" data-bs-theme-value="light">
                  <i class="ti tabler-sun"></i> Terang
                </button>
              </li>
              <li>
                <button type="button" class="dropdown-item d-flex align-items-center gap-2" data-bs-theme-value="dark">
                  <i class="ti tabler-moon-stars"></i> Gelap
                </button>
              </li>
              <li>
                <button type="button" class="dropdown-item d-flex align-items-center gap-2" data-bs-theme-value="system">
                  <i class="ti tabler-device-desktop-analytics"></i> Sistem
                </button>
              </li>
            </ul>
          </div>
          @endif
          <a href="{{ url('/login') }}" class="btn btn-login btn-sm d-flex align-items-center gap-2">
            <i class="ti tabler-login" style="font-size:0.9rem;"></i>
            Masuk
          </a>
        </div>

      </div>

    </div>
  </div>
</nav>
<!-- Navbar Islami: End -->

<style>
/* Navbar scroll effect */
#mansaba-nav {
  position: sticky;
  top: 0;
  z-index: 1030;
  transition: padding 0.3s ease, box-shadow 0.3s ease;
}
#mansaba-nav.scrolled {
  box-shadow: 0 4px 30px rgba(27,94,66,0.4) !important;
}

/* Hide offcanvas header on desktop (brand already visible in navbar) */
@media (min-width: 992px) {
  #mansabaNav.landing-nav-menu .offcanvas-header {
    display: none !important;
  }
  #mansabaNav.landing-nav-menu .offcanvas-body .navbar-nav {
    flex-direction: row;
    gap: 0.25rem;
  }
}

/* Mobile offcanvas menu (slide from left) */
@media (max-width: 991.98px) {
  #mansaba-nav .container,
  #mansaba-nav .navbar {
    transform: none !important;
    -webkit-transform: none !important;
    filter: none !important;
    backdrop-filter: none !important;
    -webkit-backdrop-filter: none !important;
    perspective: none !important;
    will-change: auto !important;
    contain: none !important;
  }

  #mansabaNav.landing-nav-menu {
    position: fixed !important;
    z-index: 9999 !important;
    display: block !important;
    padding: 1rem;
    margin: 0 !important;
    transform: none !important;
    box-sizing: border-box !important;
    height: 100vh !important;
    width: 80vw !important;
    max-width: 300px;
    top: 0 !important;
    left: -100% !important;
    overflow-y: auto;
    transition: left .3s ease-in-out !important;
    background: linear-gradient(135deg, #0a4a2e 0%, #0d5e3a 50%, #0a4a2e 100%) !important;
    border-right: 1px solid rgba(255,255,255,0.08);
  }
  #mansabaNav.landing-nav-menu.show {
    left: 0 !important;
  }

  #mansabaNavOverlay {
    display: none;
    position: fixed;
    z-index: 9998;
    background-color: rgba(0,0,0,0.65);
    block-size: 100%;
    inline-size: 100%;
    inset-block-start: 0;
    inset-inline-start: 0;
    transition: opacity .25s ease;
  }
  #mansabaNavOverlay.show {
    display: block;
  }

  #mansabaNav.landing-nav-menu .nav-link {
    color: rgba(255,255,255,0.85) !important;
    padding: 0.6rem 0.75rem !important;
    border-radius: 6px;
    transition: background 0.2s ease;
  }
  #mansabaNav.landing-nav-menu .nav-link:hover,
  #mansabaNav.landing-nav-menu .nav-link.active {
    background: rgba(255,255,255,0.08);
    color: #fff !important;
  }
  #mansabaNav.landing-nav-menu .offcanvas-header {
    border-bottom: 1px solid rgba(255,255,255,0.08);
  }
  #mansabaNav.landing-nav-menu .btn-close {
    filter: brightness(0) invert(1);
    opacity: 0.7;
  }
  #mansabaNav.landing-nav-menu .btn-close:hover {
    opacity: 1;
  }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
  var nav = document.getElementById('mansaba-nav');
  window.addEventListener('scroll', function() {
    if (window.scrollY > 20) {
      nav.classList.add('scrolled');
    } else {
      nav.classList.remove('scrolled');
    }
  });

  // Mobile offcanvas menu toggle
  var toggleBtn = document.getElementById('mansabaNavToggle');
  var closeBtn = document.getElementById('mansabaNavClose');
  var menu = document.getElementById('mansabaNav');
  var overlay = document.getElementById('mansabaNavOverlay');

  function openMenu() {
    menu.classList.add('show');
    overlay.classList.add('show');
    document.body.style.overflow = 'hidden';
  }

  function closeMenu() {
    menu.classList.remove('show');
    overlay.classList.remove('show');
    document.body.style.overflow = '';
  }

  if (toggleBtn) {
    toggleBtn.addEventListener('click', function() {
      if (menu.classList.contains('show')) {
        closeMenu();
      } else {
        openMenu();
      }
    });
  }

  if (closeBtn) {
    closeBtn.addEventListener('click', closeMenu);
  }

  if (overlay) {
    overlay.addEventListener('click', closeMenu);
  }

  // Close menu when nav link is clicked (except dropdown toggles)
  document.querySelectorAll('#mansabaNav .nav-link').forEach(function(link) {
    link.addEventListener('click', function() {
      if (!link.classList.contains('dropdown-toggle')) {
        closeMenu();
      }
    });
  });
});
</script>
