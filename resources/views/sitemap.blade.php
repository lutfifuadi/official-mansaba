<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
  <url>
    <loc>{{ url('/') }}</loc>
    <priority>1.0</priority>
    <changefreq>daily</changefreq>
  </url>
  <url>
    <loc>{{ url('/berita') }}</loc>
    <priority>0.9</priority>
    <changefreq>daily</changefreq>
  </url>
  <url>
    <loc>{{ url('/galeri') }}</loc>
    <priority>0.7</priority>
    <changefreq>weekly</changefreq>
  </url>
  <url>
    <loc>{{ url('/prestasi') }}</loc>
    <priority>0.8</priority>
    <changefreq>weekly</changefreq>
  </url>
  <url>
    <loc>{{ url('/ekstrakurikuler') }}</loc>
    <priority>0.7</priority>
    <changefreq>weekly</changefreq>
  </url>
  <url>
    <loc>{{ url('/profil') }}</loc>
    <priority>0.8</priority>
    <changefreq>monthly</changefreq>
  </url>
  @foreach($news as $item)
  <url>
    <loc>{{ route('public.news-detail', $item->slug) }}</loc>
    <lastmod>{{ $item->updated_at->tz('UTC')->toW3cString() }}</lastmod>
    <priority>0.6</priority>
    <changefreq>monthly</changefreq>
  </url>
  @endforeach
</urlset>
