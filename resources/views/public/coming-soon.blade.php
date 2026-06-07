@php
$settings = App\Models\Setting::all()->keyBy('key');
$siteName = $settings->get('site_name')?->value ?? 'MAN 1 Kota Bandung';
$logo = $settings->get('school_logo')?->value;
$logoSrc = $logo ? (str_starts_with($logo, 'http') ? $logo : \Illuminate\Support\Facades\Storage::url($logo)) : null;
$facebook = $settings->get('facebook')?->value ?? '#';
$instagram = $settings->get('instagram')?->value ?? '#';
$youtube = $settings->get('youtube')?->value ?? '#';
$twitter = $settings->get('twitter')?->value ?? '#';
$tiktok = $settings->get('tiktok')?->value ?? '#';
$targetDate = $settings->get('coming_soon_date')?->value ?? '2026-08-17 08:00:00';
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <title>Coming Soon — {{ $siteName }}</title>
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
      background: #0a1f0e;
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
        radial-gradient(ellipse 80% 60% at 50% -10%, rgba(46, 125, 50, 0.25) 0%, transparent 60%),
        radial-gradient(ellipse 60% 50% at 80% 90%, rgba(27, 94, 66, 0.2) 0%, transparent 50%),
        radial-gradient(ellipse 50% 40% at 20% 80%, rgba(201, 151, 43, 0.08) 0%, transparent 50%),
        linear-gradient(180deg, #0a1f0e 0%, #0d2e15 40%, #0a1f0e 100%);
    }

    .grid-overlay {
      position: fixed; inset: 0; z-index: 1;
      background-image:
        linear-gradient(rgba(255,255,255,0.02) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255,255,255,0.02) 1px, transparent 1px);
      background-size: 60px 60px;
      mask-image: radial-gradient(ellipse 70% 60% at 50% 50%, black 20%, transparent 70%);
      -webkit-mask-image: radial-gradient(ellipse 70% 60% at 50% 50%, black 20%, transparent 70%);
    }

    .orb {
      position: fixed; border-radius: 50%; filter: blur(80px);
      z-index: 1; pointer-events: none;
    }
    .orb-1 {
      width: 500px; height: 500px;
      background: radial-gradient(circle, rgba(46, 125, 50, 0.15), transparent);
      top: -150px; right: -100px;
      animation: orbFloat 12s ease-in-out infinite;
    }
    .orb-2 {
      width: 400px; height: 400px;
      background: radial-gradient(circle, rgba(201, 151, 43, 0.1), transparent);
      bottom: -100px; left: -80px;
      animation: orbFloat 15s ease-in-out infinite reverse;
    }
    @keyframes orbFloat {
      0%, 100% { transform: translate(0, 0) scale(1); }
      50% { transform: translate(30px, -30px) scale(1.1); }
    }

    .container {
      position: relative; z-index: 10;
      width: 100%; max-width: 640px;
      padding: 24px 24px 16px;
      text-align: center;
      display: flex; flex-direction: column; align-items: center;
      gap: 0;
    }

    .logo-wrap {
      width: 72px; height: 72px;
      background: rgba(255,255,255,0.06);
      border: 2px solid rgba(255,255,255,0.08);
      border-radius: 5px;
      display: flex; align-items: center; justify-content: center;
      padding: 12px;
      margin-bottom: 20px;
      backdrop-filter: blur(12px);
    }
    .logo-wrap img { max-width: 100%; max-height: 100%; object-fit: contain; }

    .badge-soon {
      display: inline-flex; align-items: center; gap: 6px;
      padding: 6px 16px;
      border-radius: 100px;
      background: rgba(46, 125, 50, 0.15);
      border: 1px solid rgba(46, 125, 50, 0.3);
      font-size: 0.75rem; font-weight: 600; letter-spacing: 0.3em;
      text-transform: uppercase; color: #81c784;
      margin-bottom: 16px;
    }
    .badge-soon .dot {
      width: 6px; height: 6px; border-radius: 50%;
      background: #81c784; animation: pulse-dot 1.5s ease-in-out infinite;
    }
    @keyframes pulse-dot {
      0%, 100% { opacity: 1; transform: scale(1); }
      50% { opacity: 0.4; transform: scale(0.8); }
    }

    h1 {
      font-family: 'Trajan Pro', serif;
      font-size: clamp(1.8rem, 5.5vw, 3rem);
      font-weight: 700; line-height: 1.15;
      background: linear-gradient(135deg, #fff 40%, #a5d6a7);
      -webkit-background-clip: text; -webkit-text-fill-color: transparent;
      background-clip: text;
      margin-bottom: 12px;
      letter-spacing: 0.02em;
    }

    p.desc {
      font-size: clamp(0.88rem, 2vw, 1rem);
      color: rgba(255,255,255,0.55);
      line-height: 1.6;
      max-width: 460px;
      margin-bottom: 28px;
    }

    .countdown {
      display: flex; gap: 12px; justify-content: center;
      margin-bottom: 28px;
      width: 100%;
    }
    .cd-item {
      flex: 1; max-width: 90px;
      background: rgba(255,255,255,0.05);
      border: 1px solid rgba(255,255,255,0.08);
      border-radius: 5px;
      padding: 12px 6px;
      backdrop-filter: blur(8px);
    }
    .cd-item .num {
      font-family: 'Product Sans', sans-serif;
      font-size: clamp(1.6rem, 5vw, 2.4rem);
      font-weight: 700; line-height: 1;
      background: linear-gradient(135deg, #fff, #a5d6a7);
      -webkit-background-clip: text; -webkit-text-fill-color: transparent;
      background-clip: text;
      letter-spacing: 0.02em;
    }
    .cd-item .label {
      font-size: 0.65rem; font-weight: 500;
      text-transform: uppercase; letter-spacing: 0.1em;
      color: rgba(255,255,255,0.4);
      margin-top: 4px;
    }

    .subscribe {
      display: flex; width: 100%; max-width: 400px; gap: 8px;
      margin-bottom: 24px;
    }
    .subscribe input {
      flex: 1; padding: 12px 16px;
      border-radius: 5px; border: 1.5px solid rgba(255,255,255,0.1);
      background: rgba(255,255,255,0.06);
      color: #fff; font-size: 0.88rem;
      outline: none; transition: border 0.25s;
    }
    .subscribe input::placeholder { color: rgba(255,255,255,0.3); }
    .subscribe input:focus { border-color: rgba(46, 125, 50, 0.5); }
    .subscribe button {
      padding: 12px 20px; border: none; border-radius: 5px;
      background: linear-gradient(135deg, #2e7d32, #1b5e20);
      color: #fff; font-weight: 600; font-size: 0.88rem;
      cursor: pointer; transition: transform 0.2s, box-shadow 0.2s; white-space: nowrap;
    }
    .subscribe button:hover { transform: translateY(-1px); box-shadow: 0 4px 20px rgba(46,125,50,0.35); }
    .subscribe button:active { transform: scale(0.97); }

    .socials {
      display: flex; gap: 12px; justify-content: center;
    }
    .socials a {
      width: 38px; height: 38px; border-radius: 50%;
      display: flex; align-items: center; justify-content: center;
      background: rgba(255,255,255,0.06);
      border: 1px solid rgba(255,255,255,0.08);
      color: rgba(255,255,255,0.5);
      text-decoration: none; font-size: 1.1rem;
      transition: all 0.25s;
    }
    .socials a:hover {
      background: rgba(46, 125, 50, 0.2);
      border-color: rgba(46, 125, 50, 0.3);
      color: #81c784; transform: translateY(-2px);
    }

    .footer-text {
      font-size: 0.7rem; color: rgba(255,255,255,0.2);
      margin-top: 16px;
    }

    @media (max-width: 480px) {
      .container { padding: 16px 20px 12px; }
      .logo-wrap { width: 56px; height: 56px; padding: 10px; margin-bottom: 14px; }
      .countdown { gap: 8px; }
      .cd-item { padding: 8px 4px; max-width: 72px; }
      .subscribe { flex-direction: column; }
      .subscribe button { width: 100%; }
    }
    @media (max-height: 600px) {
      .logo-wrap { width: 48px; height: 48px; padding: 8px; margin-bottom: 10px; }
      .badge-soon { margin-bottom: 8px; padding: 4px 12px; }
      h1 { font-size: 1.4rem; }
      .countdown { margin-bottom: 14px; }
      .subscribe { margin-bottom: 14px; }
    }
  </style>
</head>
<body>

  <div class="bg-layer"></div>
  <div class="grid-overlay"></div>
  <div class="orb orb-1"></div>
  <div class="orb orb-2"></div>

  <div class="container">
    <div class="logo-wrap">
      @if($logoSrc)
        <img src="{{ $logoSrc }}" alt="{{ $siteName }}">
      @else
          <span style="font-family:'Trajan Pro',serif;font-size:1.5rem;font-weight:700;color:#81c784;">{{ substr($siteName, 0, 1) }}</span>
      @endif
    </div>

    <div class="badge-soon">
      <span class="dot"></span>
      Segera Hadir
    </div>

    <h1>Coming Soon</h1>

    <p class="desc">
      Kami sedang menyiapkan sesuatu yang istimewa untuk Anda. Nantikan pengalaman baru dari {{ $siteName }}!
    </p>

    <div class="countdown" id="countdown">
      <div class="cd-item"><div class="num" id="cd-days">00</div><div class="label">Hari</div></div>
      <div class="cd-item"><div class="num" id="cd-hours">00</div><div class="label">Jam</div></div>
      <div class="cd-item"><div class="num" id="cd-minutes">00</div><div class="label">Menit</div></div>
      <div class="cd-item"><div class="num" id="cd-seconds">00</div><div class="label">Detik</div></div>
    </div>

    <form class="subscribe" onsubmit="event.preventDefault(); this.querySelector('input').value=''; this.querySelector('button').textContent='Terkirim!'; setTimeout(()=>this.querySelector('button').textContent='Beritahu Saya', 2000);">
      <input type="email" placeholder="Masukkan email Anda..." required>
      <button type="submit">Beritahu Saya</button>
    </form>

    <div class="socials">
      <a href="{{ $facebook }}" aria-label="Facebook" target="_blank" rel="noopener">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
      </a>
      <a href="{{ $instagram }}" aria-label="Instagram" target="_blank" rel="noopener">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>
      </a>
      <a href="{{ $youtube }}" aria-label="YouTube" target="_blank" rel="noopener">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22.54 6.42a2.78 2.78 0 0 0-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.46a2.78 2.78 0 0 0-1.94 2A29 29 0 0 0 1 12a29 29 0 0 0 .46 5.58 2.78 2.78 0 0 0 1.94 2C5.12 20 12 20 12 20s6.88 0 8.6-.46a2.78 2.78 0 0 0 1.94-2A29 29 0 0 0 23 12a29 29 0 0 0-.46-5.58z"/><polygon points="9.75 15.02 15.5 12 9.75 8.98 9.75 15.02"/></svg>
      </a>
      <a href="{{ $twitter }}" aria-label="Twitter/X" target="_blank" rel="noopener">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4l11.733 16h4.267l-11.733 -16zM4 20l6.768 -6.768M19.5 4l-6.768 6.768"/></svg>
      </a>
      <a href="{{ $tiktok }}" aria-label="TikTok" target="_blank" rel="noopener">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 12a4 4 0 1 0 4 4V4a5 5 0 0 0 5 5"/></svg>
      </a>
    </div>

    <div class="footer-text">© {{ date('Y') }} {{ $siteName }}. All rights reserved.</div>
  </div>

  <script>
    (function() {
      var target = new Date('{{ $targetDate }}').getTime();
      function pad(n) { return n < 10 ? '0' + n : n; }
      function tick() {
        var now = new Date().getTime();
        var diff = Math.max(0, target - now);
        var d = Math.floor(diff / 86400000);
        var h = Math.floor((diff % 86400000) / 3600000);
        var m = Math.floor((diff % 3600000) / 60000);
        var s = Math.floor((diff % 60000) / 1000);
        document.getElementById('cd-days').textContent = pad(d);
        document.getElementById('cd-hours').textContent = pad(h);
        document.getElementById('cd-minutes').textContent = pad(m);
        document.getElementById('cd-seconds').textContent = pad(s);
      }
      tick();
      setInterval(tick, 1000);
    })();
  </script>
</body>
</html>
