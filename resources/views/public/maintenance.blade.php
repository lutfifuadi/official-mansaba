@php
$settings = App\Models\Setting::all()->keyBy('key');
$siteName = $settings->get('site_name')?->value ?? 'MAN 1 Kota Bandung';
$logo = $settings->get('school_logo')?->value;
$logoSrc = $logo ? (str_starts_with($logo, 'http') ? $logo : \App\Helpers\StorageHelper::url($logo)) : null;
$facebook = $settings->get('facebook')?->value ?? '#';
$instagram = $settings->get('instagram')?->value ?? '#';
$youtube = $settings->get('youtube')?->value ?? '#';
$twitter = $settings->get('twitter')?->value ?? '#';
$tiktok = $settings->get('tiktok')?->value ?? '#';
$estTime = $settings->get('maintenance_est_time')?->value ?? 'Beberapa jam ke depan';
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <title>Maintenance — {{ $siteName }}</title>
  <style>
    @font-face { font-family:'Product Sans'; font-style:normal; font-weight:300; font-display:block; src:url('{{ asset('fonts/product-sans/ProductSans-Light.woff2') }}') format('woff2'); }
    @font-face { font-family:'Product Sans'; font-style:normal; font-weight:400; font-display:block; src:url('{{ asset('fonts/product-sans/ProductSans-Regular.woff2') }}') format('woff2'); }
    @font-face { font-family:'Product Sans'; font-style:normal; font-weight:500; font-display:block; src:url('{{ asset('fonts/product-sans/ProductSans-Medium.woff2') }}') format('woff2'); }
    @font-face { font-family:'Product Sans'; font-style:normal; font-weight:700; font-display:block; src:url('{{ asset('fonts/product-sans/ProductSans-Bold.woff2') }}') format('woff2'); }
    @font-face { font-family:'Trajan Pro'; font-style:normal; font-weight:400; font-display:block; src:url('{{ asset('fonts/trajan-pro/TrajanPro-Regular.woff2') }}') format('woff2'); }
    @font-face { font-family:'Trajan Pro'; font-style:normal; font-weight:700; font-display:block; src:url('{{ asset('fonts/trajan-pro/TrajanPro-Bold.woff2') }}') format('woff2'); }
    *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
    html, body {
      height: 100%; overflow: hidden;
      font-family: 'Product Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', system-ui, sans-serif;
      background: #0f1117;
      color: #fff;
      -webkit-font-smoothing: antialiased;
      -moz-osx-font-smoothing: grayscale;
    }
    body {
      display: flex; align-items: center; justify-content: center;
      min-height: 100dvh; position: relative;
    }

    .bg-layer {
      position: fixed; inset: 0; z-index: 0;
      background:
        radial-gradient(ellipse 70% 50% at 30% 20%, rgba(255, 152, 0, 0.06) 0%, transparent 50%),
        radial-gradient(ellipse 60% 50% at 70% 80%, rgba(255, 87, 34, 0.05) 0%, transparent 50%),
        linear-gradient(180deg, #0f1117 0%, #1a1d27 40%, #0f1117 100%);
    }

    .grid-overlay {
      position: fixed; inset: 0; z-index: 1;
      background-image:
        linear-gradient(rgba(255,255,255,0.015) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255,255,255,0.015) 1px, transparent 1px);
      background-size: 50px 50px;
      mask-image: radial-gradient(ellipse 60% 50% at 50% 50%, black 30%, transparent 70%);
      -webkit-mask-image: radial-gradient(ellipse 60% 50% at 50% 50%, black 30%, transparent 70%);
    }

    .gear-bg {
      position: fixed; z-index: 1; pointer-events: none;
      font-size: 40vw; font-weight: 900;
      color: rgba(255,255,255,0.02);
      bottom: -5vw; right: -5vw;
      line-height: 1;
      animation: spin 20s linear infinite;
    }
    @keyframes spin { to { transform: rotate(360deg); } }

    .gear-bg-2 {
      position: fixed; z-index: 1; pointer-events: none;
      font-size: 20vw; font-weight: 900;
      color: rgba(255,255,255,0.015);
      top: 2vw; left: 2vw;
      line-height: 1;
      animation: spin-reverse 25s linear infinite;
    }
    @keyframes spin-reverse { to { transform: rotate(-360deg); } }

    .container {
      position: relative; z-index: 10;
      width: 100%; max-width: 800px;
      padding: 24px;
      text-align: center;
      display: flex; flex-direction: column; align-items: center;
    }

    .icon-wrap {
      width: 96px; height: 96px;
      background: rgba(255, 152, 0, 0.08);
      border: 2px solid rgba(255, 152, 0, 0.12);
      border-radius: 50%;
      display: flex; align-items: center; justify-content: center;
      margin-bottom: 24px;
      position: relative;
    }
    .icon-wrap svg { width: 48px; height: 48px; color: #ffb74d; }
    .icon-ring {
      position: absolute; inset: -4px; border-radius: 50%;
      border: 2px solid rgba(255, 152, 0, 0.08);
      animation: ring-pulse 2s ease-in-out infinite;
    }
    @keyframes ring-pulse {
      0%, 100% { transform: scale(1); opacity: 1; }
      50% { transform: scale(1.15); opacity: 0; }
    }

    h1 {
      font-family: 'Trajan Pro', serif;
      font-size: clamp(1.4rem, 4.5vw, 2.2rem);
      font-weight: 700; line-height: 1.25;
      margin-bottom: 12px;
      letter-spacing: 0.02em;
    }

    p.desc {
      font-size: clamp(0.88rem, 2vw, 1rem);
      color: rgba(255,255,255,0.5);
      line-height: 1.7;
      margin-bottom: 24px;
    }

    .info-card {
      width: 100%; max-width: 480px;
      background: rgba(255,255,255,0.04);
      border: 1px solid rgba(255,255,255,0.06);
      border-radius: 5px;
      padding: 16px 20px;
      display: flex; align-items: center; gap: 14px;
      margin-bottom: 24px;
      backdrop-filter: blur(8px);
    }
    .info-card svg { width: 22px; height: 22px; color: #ffb74d; flex-shrink: 0; }
    .info-card .info-text { text-align: left; }
    .info-card .info-text .label { font-size: 0.7rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.08em; color: rgba(255,255,255,0.3); margin-bottom: 2px; }
    .info-card .info-text .value { font-size: 0.9rem; font-weight: 600; color: rgba(255,255,255,0.85); }

    .progress-track {
      width: 100%; max-width: 480px;
      height: 4px; border-radius: 4px;
      background: rgba(255,255,255,0.06);
      overflow: hidden;
      margin-bottom: 24px;
    }
    .progress-bar {
      height: 100%; width: 45%;
      border-radius: 4px;
      background: linear-gradient(90deg, #ff9800, #ffb74d);
      animation: progressMove 2.5s ease-in-out infinite;
    }
    @keyframes progressMove {
      0% { width: 20%; margin-left: 0; }
      50% { width: 60%; margin-left: 20%; }
      100% { width: 20%; margin-left: 80%; }
    }

    .socials {
      display: flex; gap: 12px; justify-content: center;
    }
    .socials a {
      width: 38px; height: 38px; border-radius: 50%;
      display: flex; align-items: center; justify-content: center;
      background: rgba(255,255,255,0.05);
      border: 1px solid rgba(255,255,255,0.06);
      color: rgba(255,255,255,0.4);
      text-decoration: none;
      transition: all 0.25s;
    }
    .socials a:hover {
      background: rgba(255, 152, 0, 0.12);
      border-color: rgba(255, 152, 0, 0.2);
      color: #ffb74d; transform: translateY(-2px);
    }
    .socials a svg { width: 18px; height: 18px; }

    .footer-text {
      font-size: 0.7rem; color: rgba(255,255,255,0.15);
      margin-top: 20px;
    }

    .logo-small {
      display: flex; align-items: center; gap: 8px;
      margin-bottom: 20px;
      text-decoration: none;
    }
    .logo-small .img-placeholder {
      width: 32px; height: 32px;
      background: rgba(255,255,255,0.06);
      border-radius: 5px;
      display: flex; align-items: center; justify-content: center;
      padding: 6px;
    }
    .logo-small .img-placeholder img { max-width: 100%; max-height: 100%; object-fit: contain; }
    .logo-small .site-name {
      font-family: 'Trajan Pro', serif;
      font-size: 0.9rem; font-weight: 700;
      color: rgba(255,255,255,0.65);
      letter-spacing: 0.03em;
    }

    @media (max-width: 480px) {
      .container { padding: 16px; }
      .icon-wrap { width: 72px; height: 72px; margin-bottom: 16px; }
      .icon-wrap svg { width: 36px; height: 36px; }
      .info-card { padding: 12px 16px; }
    }
    @media (max-height: 600px) {
      .icon-wrap { width: 56px; height: 56px; margin-bottom: 10px; }
      .icon-wrap svg { width: 28px; height: 28px; }
      h1 { font-size: 1.3rem; }
      .info-card { margin-bottom: 14px; }
      .progress-track { margin-bottom: 14px; }
    }
  </style>
</head>
<body>

  <div class="bg-layer"></div>
  <div class="grid-overlay"></div>
  <div class="gear-bg">&#9881;</div>
  <div class="gear-bg-2">&#9881;</div>

  <div class="container">
    <a href="/" class="logo-small">
      <div class="img-placeholder">
        @if($logoSrc)
          <img src="{{ $logoSrc }}" alt="{{ $siteName }}">
        @else
          <span style="font-family:'Trajan Pro',serif;font-size:0.9rem;font-weight:700;color:rgba(255,255,255,0.4);">{{ substr($siteName, 0, 1) }}</span>
        @endif
      </div>
      <span class="site-name">{{ $siteName }}</span>
    </a>

    <div class="icon-wrap">
      <div class="icon-ring"></div>
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
        <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>
      </svg>
    </div>

    <h1>Sedang Dalam Pemeliharaan</h1>

    <p class="desc">
      Kami sedang melakukan peningkatan performa dan perbaikan sistem agar website {{ $siteName }} semakin baik. Mohon maaf atas ketidaknyamanannya.
    </p>

    <div class="info-card">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
        <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
      </svg>
      <div class="info-text">
        <div class="label">Estimasi Selesai</div>
        <div class="value">{{ $estTime }}</div>
      </div>
    </div>

    <div class="progress-track">
      <div class="progress-bar"></div>
    </div>

    <div class="socials">
      <a href="{{ $facebook }}" aria-label="Facebook" target="_blank" rel="noopener">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
      </a>
      <a href="{{ $instagram }}" aria-label="Instagram" target="_blank" rel="noopener">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>
      </a>
      <a href="{{ $youtube }}" aria-label="YouTube" target="_blank" rel="noopener">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22.54 6.42a2.78 2.78 0 0 0-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.46a2.78 2.78 0 0 0-1.94 2A29 29 0 0 0 1 12a29 29 0 0 0 .46 5.58 2.78 2.78 0 0 0 1.94 2C5.12 20 12 20 12 20s6.88 0 8.6-.46a2.78 2.78 0 0 0 1.94-2A29 29 0 0 0 23 12a29 29 0 0 0-.46-5.58z"/><polygon points="9.75 15.02 15.5 12 9.75 8.98 9.75 15.02"/></svg>
      </a>
      <a href="{{ $twitter }}" aria-label="Twitter/X" target="_blank" rel="noopener">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4l11.733 16h4.267l-11.733 -16zM4 20l6.768 -6.768M19.5 4l-6.768 6.768"/></svg>
      </a>
      <a href="{{ $tiktok }}" aria-label="TikTok" target="_blank" rel="noopener">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 12a4 4 0 1 0 4 4V4a5 5 0 0 0 5 5"/></svg>
      </a>
    </div>

    <div class="footer-text">© {{ date('Y') }} {{ $siteName }}. All rights reserved.</div>
  </div>

</body>
</html>
