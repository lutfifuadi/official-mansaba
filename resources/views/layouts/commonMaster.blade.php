<!DOCTYPE html>
@php
  use Illuminate\Support\Str;
  use App\Helpers\Helpers;

  $menuFixed =
      $configData['layout'] === 'vertical'
          ? $menuFixed ?? ''
          : ($configData['layout'] === 'front'
              ? ''
              : $configData['headerType']);
  $navbarType =
      $configData['layout'] === 'vertical'
          ? $configData['navbarType']
          : ($configData['layout'] === 'front'
              ? 'layout-navbar-fixed'
              : '');
  $isFront = ($isFront ?? '') == true ? 'Front' : '';
  $contentLayout = isset($container) ? ($container === 'container-xxl' ? 'layout-compact' : 'layout-wide') : '';

  // Get skin name from configData - only applies to admin layouts
  $isAdminLayout = !Str::contains($configData['layout'] ?? '', 'front');
  $skinName = $isAdminLayout ? $configData['skinName'] ?? 'default' : 'default';

  // Get semiDark value from configData - only applies to admin layouts
  $semiDarkEnabled = $isAdminLayout && filter_var($configData['semiDark'] ?? false, FILTER_VALIDATE_BOOLEAN);

  // ─────────────────────────────────────────────────────────────
  // OVERRIDE THEME SETTINGS FROM DATABASE (prioritas tertinggi)
  // Database settings (diset via halaman /admin/theme) akan
  // meng-override cookie dan config default.
  // ─────────────────────────────────────────────────────────────
  if ($isAdminLayout) {
      // Primary Color
      if (!empty($globalSettings['theme_primary_color'] ?? '')) {
          $configData['color'] = $globalSettings['theme_primary_color'];
      }

      // Theme Mode (light/dark/system)
      if (!empty($globalSettings['theme_mode'] ?? '')) {
          $dbTheme = $globalSettings['theme_mode'];
          $configData['themeOpt'] = $dbTheme;
          // Untuk data-bs-theme, 'system' perlu di-resolve ke light/dark
          $configData['theme'] = $dbTheme === 'system'
              ? (isset($_COOKIE['admin-colorPref']) && $_COOKIE['admin-colorPref'] === 'dark' ? 'dark' : 'light')
              : $dbTheme;
      }

      // Skin
      if (!empty($globalSettings['theme_skin'] ?? '')) {
          $configData['skinName'] = $globalSettings['theme_skin'];
      }

      // Semi Dark
      if (isset($globalSettings['theme_semi_dark'])) {
          $semiDarkDb = filter_var($globalSettings['theme_semi_dark'], FILTER_VALIDATE_BOOLEAN);
          $configData['semiDark'] = $semiDarkDb;
          $semiDarkEnabled = $isAdminLayout && $semiDarkDb;
          $configData['menuAttributes'] = Helpers::getMenuAttributes($semiDarkEnabled);
      }
  }

  // ─────────────────────────────────────────────────────────────
  // Generate primary color CSS
  // ─────────────────────────────────────────────────────────────
  $primaryColorCSS = '';
  if (isset($configData['color']) && $configData['color']) {
      $primaryColorCSS = Helpers::generatePrimaryColorCSS($configData['color']);
  }

  // SEO variables
  $siteName = $globalSettings['site_name'] ?? 'MAN 1 Kota Bandung';
  $siteDesc = $globalSettings['site_description'] ?? '';
  $pc = $pageConfigs ?? [];
  $metaDesc = $pc['meta_description'] ?? $globalSettings['meta_description'] ?? $siteDesc;
  $metaKeywords = $globalSettings['meta_keyword'] ?? '';
  $ogTitle = $globalSettings['og_title'] ?? $siteName;
  $ogDesc = $globalSettings['og_description'] ?? $metaDesc;
  $ogImage = $pc['meta_image'] ?? $globalSettings['og_image'] ?? '';
  $canonical = $pc['canonical'] ?? url()->current();
  $robots = $isAdminLayout ? 'noindex, nofollow' : 'index, follow';
  $sep = $globalSettings['meta_title_separator'] ?? '—';
  $suffix = $globalSettings['meta_title_suffix'] ?? $siteName;
  $metaTitleHome = $globalSettings['meta_title_home'] ?? '';

@endphp

<html lang="{{ session()->get('locale') ?? app()->getLocale() }}"
  class="{{ $navbarType ?? '' }} {{ $contentLayout ?? '' }} {{ $menuFixed ?? '' }} {{ $menuCollapsed ?? '' }} {{ $footerFixed ?? '' }} {{ $customizerHidden ?? '' }}"
  dir="{{ $configData['textDirection'] }}" data-skin="{{ $skinName }}" data-assets-path="{{ asset('/assets') . '/' }}"
  data-base-url="{{ url('/') }}" data-framework="laravel" data-template="{{ $configData['layout'] }}-menu-template"
  data-bs-theme="{{ $configData['theme'] }}" @if ($isAdminLayout && $semiDarkEnabled) data-semidark-menu="true" @endif>

<head>
  <meta charset="utf-8" />
  <meta name="viewport"
    content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

  <title>@php
    $yieldedTitle = trim($__env->yieldContent('title'));
    $fullTitle = $yieldedTitle;
    if (!$isAdminLayout) {
      $pageMetaMap = [
        'Beranda'          => $globalSettings['meta_title_home'] ?? '',
        'Berita'           => $globalSettings['meta_title_berita'] ?? '',
        'Galeri'           => $globalSettings['meta_title_galeri'] ?? '',
        'Prestasi'         => $globalSettings['meta_title_prestasi'] ?? '',
        'Ekstrakurikuler'  => $globalSettings['meta_title_ekstrakurikuler'] ?? '',
        'Profil Sekolah'   => $globalSettings['meta_title_profil'] ?? '',
        'Profil'           => $globalSettings['meta_title_profil'] ?? '',
      ];
      if (!empty($pageMetaMap[$yieldedTitle])) {
        $fullTitle = $pageMetaMap[$yieldedTitle];
      }
      $fullTitle .= ' ' . $sep . ' ' . $suffix;
    }
    echo $fullTitle;
  @endphp</title>
  <meta name="description" content="{{ $metaDesc }}" />
  <meta name="keywords" content="{{ $metaKeywords }}" />
  <meta name="robots" content="{{ $robots }}" />
  <link rel="canonical" href="{{ $canonical }}" />

  <meta property="og:title" content="{{ $ogTitle }}" />
  <meta property="og:type" content="{{ $isAdminLayout ? 'admin' : 'website' }}" />
  <meta property="og:url" content="{{ $canonical }}" />
  <meta property="og:image" content="{{ $ogImage }}" />
  <meta property="og:description" content="{{ $ogDesc }}" />
  <meta property="og:site_name" content="{{ $siteName }}" />
  <meta property="og:locale" content="{{ app()->getLocale() }}" />

  <meta name="twitter:card" content="{{ $globalSettings['twitter_card_type'] ?? 'summary_large_image' }}" />
  <meta name="twitter:site" content="{{ $globalSettings['twitter_handle'] ?? '' }}" />
  <meta name="twitter:title" content="{{ $ogTitle }}" />
  <meta name="twitter:description" content="{{ $ogDesc }}" />
  <meta name="twitter:image" content="{{ $ogImage }}" />
  <meta name="twitter:image:alt" content="{{ $globalSettings['og_image_alt'] ?? '' }}" />

  @if($globalSettings['google_site_verification'] ?? false)
    <meta name="google-site-verification" content="{{ $globalSettings['google_site_verification'] }}" />
  @endif

  @if(!$isAdminLayout && ($globalSettings['google_analytics'] ?? false))
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ $globalSettings['google_analytics'] }}"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', '{{ $globalSettings['google_analytics'] }}');
    </script>
  @endif

  @if(!$isAdminLayout && ($globalSettings['facebook_pixel'] ?? false))
    <script>
      !function(f,b,e,v,n,t,s)
      {if(f.fbq)return;n=f.fbq=function(){n.callMethod?n.callMethod.apply(n,arguments):n.queue.push(arguments)};
      if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
      n.queue=[];t=b.createElement(e);t.async=!0;
      t.src=v;s=b.getElementsByTagName(e)[0];
      s.parentNode.insertBefore(t,s)}(window,document,'script','https://connect.facebook.net/en_US/fbevents.js');
      fbq('init', '{{ $globalSettings['facebook_pixel'] }}');
      fbq('track', 'PageView');
    </script>
  @endif

  <!-- laravel CRUD token -->
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <!-- Favicon -->
  <link rel="icon" type="image/x-icon" href="{{ $globalSettings['favicon'] ?? asset('assets/img/favicon/favicon.ico') }}" />

  <!-- Include Styles -->
  <!-- $isFront is used to append the front layout styles only on the front layout otherwise the variable will be blank -->
  @include('layouts/sections/styles' . $isFront)

  @if (
      $primaryColorCSS &&
          (config('custom.custom.primaryColor') ||
              isset($_COOKIE['admin-primaryColor']) ||
              isset($_COOKIE['front-primaryColor']) ||
              !empty($globalSettings['theme_primary_color'] ?? '')))
    <!-- Primary Color Style -->
    <style id="primary-color-style">
      {!! $primaryColorCSS !!}
    </style>
  @endif

  @if (!$isAdminLayout)
    <script type="application/ld+json">{!! json_encode(\App\Helpers\SeoHelper::organization(), JSON_UNESCAPED_SLASHES) !!}</script>
    <script type="application/ld+json">{!! json_encode(\App\Helpers\SeoHelper::website(), JSON_UNESCAPED_SLASHES) !!}</script>
    @if (isset($breadcrumbs))
      <script type="application/ld+json">{!! json_encode(\App\Helpers\SeoHelper::breadcrumbList($breadcrumbs), JSON_UNESCAPED_SLASHES) !!}</script>
    @endif
    @if (isset($ldNewsArticle))
      <script type="application/ld+json">{!! json_encode(\App\Helpers\SeoHelper::newsArticle($ldNewsArticle), JSON_UNESCAPED_SLASHES) !!}</script>
    @endif
    @foreach (['en', 'ar', 'de', 'fr'] as $locale)
      @if (session()->get('locale') !== $locale)
        <link rel="alternate" hreflang="{{ $locale }}" href="{{ url('/lang/' . $locale) }}" />
      @endif
    @endforeach
    <link rel="alternate" hreflang="{{ app()->getLocale() }}" href="{{ url()->current() }}" />
    <link rel="alternate" hreflang="x-default" href="{{ url('/') }}" />
  @endif

  <!-- Include Scripts for customizer, helper, analytics, config -->
  <!-- $isFront is used to append the front layout scriptsIncludes only on the front layout otherwise the variable will be blank -->
  @include('layouts/sections/scriptsIncludes' . $isFront)

  <!-- Alpine.js for interactive components -->
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body>
  <!-- Layout Content -->
  @yield('layoutContent')
  <!--/ Layout Content -->

  {{-- remove while creating package --}}
  {{-- remove while creating package end --}}

  <!-- Include Scripts -->
  <!-- $isFront is used to append the front layout scripts only on the front layout otherwise the variable will be blank -->
  @include('layouts/sections/scripts' . $isFront)
</body>

</html>
