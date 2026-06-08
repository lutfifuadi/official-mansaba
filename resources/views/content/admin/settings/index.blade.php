@extends('layouts/contentNavbarLayout')

@section('title', 'Pengaturan')

@section('content')
  @if(session('success'))
    <div class="alert alert-success alert-dismissible" role="alert">
      {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  @if(session('error'))
    <div class="alert alert-danger alert-dismissible" role="alert">
      {{ session('error') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  @php
    $settingGroups = [
      'Identitas Sekolah' => [
        'app_name'           => 'Nama Aplikasi',
        'site_name'          => 'Nama Situs',
        'motto'              => 'Moto Sekolah (tagline pendek)',
        'visi'               => 'Visi',
        'misi'               => 'Misi (pisahkan tiap item dengan tanda |)',
        'site_description'   => 'Deskripsi Situs',
        'address'            => 'Alamat Sekolah',
        'phone'              => 'Nomor Telepon',
        'email'              => 'Email',
        'school_logo'        => 'Logo Sekolah',
        'favicon'            => 'Favicon',
        'operational_hours'  => 'Jam Operasional',
      ],
      'Statistik' => [
        'student_count'      => 'Jumlah Siswa',
        'teacher_count'      => 'Jumlah Tenaga Pendidik',
        'founded_year'       => 'Tahun Berdiri',
        'stats_label_1'      => 'Label Statistik 1',
        'stats_label_2'      => 'Label Statistik 2',
        'stats_label_3'      => 'Label Statistik 3',
        'stats_label_4'      => 'Label Statistik 4',
      ],
      'Hero Section' => [
        'hero_title'         => 'Judul Hero',
        'hero_highlight'     => 'Teks Sorotan Hero',
        'hero_subtitle'      => 'Subtitle Hero',
      ],
      'Sosial Media' => [
        'facebook'           => 'Facebook (URL)',
        'instagram'          => 'Instagram (URL)',
        'youtube'            => 'YouTube (URL)',
        'twitter'            => 'Twitter/X (URL)',
        'tiktok'             => 'TikTok (URL)',
      ],
      'Kepala Sekolah' => [
        'headmaster_name'    => 'Nama Kepala Sekolah',
        'headmaster_photo'   => 'Foto',
        'headmaster_message' => 'Sambutan',
        'headmaster_label'   => 'Label Section',
        'headmaster_title'   => 'Judul Section',
      ],
      'Section Berita' => [
        'section_news_label'   => 'Label Section',
        'section_news_title'   => 'Judul Section',
        'section_news_desc'    => 'Deskripsi Section',
      ],
      'Section Prestasi' => [
        'section_achievement_label' => 'Label Section',
        'section_achievement_title' => 'Judul Section',
        'section_achievement_desc'  => 'Deskripsi Section',
      ],
      'Section Galeri' => [
        'section_gallery_label'   => 'Label Section',
        'section_gallery_title'   => 'Judul Section',
        'section_gallery_desc'    => 'Deskripsi Section',
      ],
      'Section Ekstrakurikuler' => [
        'section_extracurricular_label' => 'Label Section',
        'section_extracurricular_title' => 'Judul Section',
        'section_extracurricular_desc'  => 'Deskripsi Section',
      ],
      'Pengumuman' => [
        'announcement'        => 'Teks Pengumuman',
        'announcement_active' => 'Aktifkan (1 = ya, 0 = tidak)',
      ],
      'Info Kontak' => [
        'contact_label_address' => 'Label Alamat',
        'contact_label_phone'   => 'Label Telepon',
        'contact_label_email'   => 'Label Email',
      ],
      'Layanan Online' => [
        'service_ptsp'         => 'PTSP (URL)',
        'service_esurat'       => 'ESurat (URL)',
        'service_presensi'     => 'Presensi Online (URL)',
        'service_ujian_online' => 'Ujian Online (URL)',
        'service_rdm'          => 'RDM (URL)',
        'service_emis'         => 'Lokal EMIS (URL)',
        'registration_enabled' => 'Registrasi Pengguna Baru',
      ],
      'Footer' => [
        'footer_text'       => 'Teks Footer',
        'footer_show_credit' => 'Tampilkan Kredit (1 = ya, 0 = tidak)',
      ],
      'SEO — Umum' => [
        'meta_title_home'     => 'Meta Title Homepage',
        'meta_title_separator' => 'Separator Title (default: —)',
        'meta_title_suffix'   => 'Suffix Title (kosongkan pakai Nama Situs)',
        'meta_description'    => 'Meta Description',
        'meta_keyword'        => 'Meta Keywords (dipisah koma)',
        'google_analytics'    => 'Google Analytics ID (G-XXXXXXXXXX)',
        'google_site_verification' => 'Google Site Verification Code',
        'facebook_pixel'      => 'Facebook Pixel ID',
        'meta_title_berita'    => 'SEO Title Halaman Berita',
        'meta_title_galeri'    => 'SEO Title Halaman Galeri',
        'meta_title_prestasi'  => 'SEO Title Halaman Prestasi',
        'meta_title_ekstrakurikuler' => 'SEO Title Halaman Ekstrakurikuler',
        'meta_title_profil'    => 'SEO Title Halaman Profil',
      ],
      'SEO — Open Graph' => [
        'og_title'            => 'OG Title (default: Nama Situs)',
        'og_description'      => 'OG Description (default: Meta Description)',
        'og_image'            => 'OG Image (URL)',
        'og_image_alt'        => 'OG Image Alt Text',
      ],
      'SEO — Twitter' => [
        'twitter_handle'      => 'Twitter Handle (@username)',
        'twitter_card_type'   => 'Twitter Card Type (summary / summary_large_image)',
      ],
      'Navigasi Menu' => [
        'nav_menu'            => 'Menu Navigasi (JSON)',
      ],
      'Mode Situs' => [
        'coming_soon_mode'    => 'Coming Soon Mode',
        'coming_soon_date'    => 'Target Tanggal Luncur',
        'maintenance_mode'    => 'Maintenance Mode',
        'maintenance_est_time' => 'Estimasi Waktu Selesai',
      ],
    ];

    $tabConfig = [
      'identitas' => [
        'label' => 'Identitas & Statistik',
        'icon'  => 'tabler-building',
        'color' => 'primary',
        'groups' => ['Identitas Sekolah', 'Statistik'],
      ],
      'tampilan' => [
        'label' => 'Tampilan Hero',
        'icon'  => 'tabler-palette',
        'color' => 'info',
        'groups' => ['Hero Section', 'Kepala Sekolah'],
      ],
      'konten' => [
        'label' => 'Section Konten',
        'icon'  => 'tabler-layout',
        'color' => 'warning',
        'groups' => ['Section Berita', 'Section Prestasi', 'Section Galeri', 'Section Ekstrakurikuler'],
      ],
      'sosial' => [
        'label' => 'Sosial & Kontak',
        'icon'  => 'tabler-brand-facebook',
        'color' => 'success',
        'groups' => ['Sosial Media', 'Info Kontak'],
      ],
      'layanan' => [
        'label' => 'Layanan & Pengumuman',
        'icon'  => 'tabler-world',
        'color' => 'danger',
        'groups' => ['Layanan Online', 'Pengumuman'],
      ],
      'seo' => [
        'label' => 'SEO',
        'icon'  => 'tabler-search-engine',
        'color' => 'dark',
        'groups' => ['SEO — Umum', 'SEO — Open Graph', 'SEO — Twitter'],
      ],
      'navigasi' => [
        'label' => 'Navigasi',
        'icon'  => 'tabler-menu-2',
        'color' => 'secondary',
        'groups' => ['Navigasi Menu'],
      ],
      'mode' => [
        'label' => 'Mode Situs',
        'icon'  => 'tabler-switch',
        'color' => 'success',
        'groups' => ['Mode Situs'],
      ],
    ];

    $groupIcons = [
      'Identitas Sekolah' => 'tabler-building',
      'Statistik' => 'tabler-chart-bar',
      'Hero Section' => 'tabler-photo',
      'Sosial Media' => 'tabler-brand-facebook',
      'Kepala Sekolah' => 'tabler-user-star',
      'Section Berita' => 'tabler-news',
      'Section Prestasi' => 'tabler-trophy',
      'Section Galeri' => 'tabler-photo',
      'Section Ekstrakurikuler' => 'tabler-users-group',
      'Pengumuman' => 'tabler-speakerphone',
      'Info Kontak' => 'tabler-address-book',
      'Layanan Online' => 'tabler-world',
      'SEO — Umum' => 'tabler-search-engine',
      'SEO — Open Graph' => 'tabler-share',
      'SEO — Twitter' => 'tabler-brand-twitter',
      'Navigasi Menu' => 'tabler-menu-2',
      'Mode Situs' => 'tabler-switch',
    ];
  @endphp

  <div class="card">
    <div class="card-header border-bottom d-flex justify-content-between align-items-center">
      <div class="d-flex align-items-center">
        <div class="avatar avatar-sm me-3">
          <span class="avatar-initial rounded bg-label-primary">
            <i class="icon-base ti tabler-settings"></i>
          </span>
        </div>
        <h5 class="card-title mb-0">Pengaturan Website</h5>
      </div>
    </div>
    <div class="card-body p-0">
      <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row g-0">
          {{-- Tab Navigation (Vertical Pills) --}}
          <div class="col-md-3 border-end">
            <div class="nav flex-column nav-pills p-4" role="tablist">
              @foreach ($tabConfig as $tabKey => $tab)
                <a class="nav-link d-flex align-items-center justify-content-start mb-1 {{ $loop->first ? 'active' : '' }}"
                   data-bs-toggle="pill"
                   href="#tab-{{ $tabKey }}"
                   role="tab">
                  <i class="icon-base ti {{ $tab['icon'] }} me-2"></i>
                  <span>{{ $tab['label'] }}</span>
                </a>
              @endforeach
            </div>
          </div>

          {{-- Tab Content --}}
          <div class="col-md-9">
            <div class="tab-content p-4">
              @foreach ($tabConfig as $tabKey => $tab)
                <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="tab-{{ $tabKey }}" role="tabpanel">

                  @foreach ($tab['groups'] as $groupName)
                    @if(isset($settingGroups[$groupName]))
                      <div class="border rounded mb-4">
                        <div class="p-3 bg-lighter d-flex align-items-center border-bottom">
                          <div class="avatar avatar-xs me-2">
                            <span class="avatar-initial rounded bg-label-{{ $tab['color'] }}">
                              <i class="icon-base ti {{ $groupIcons[$groupName] ?? 'tabler-settings' }}" style="font-size: 0.875rem;"></i>
                            </span>
                          </div>
                          <h6 class="mb-0">{{ $groupName }}</h6>
                        </div>
                        <div class="p-3">
                          <div class="row">
                      @foreach ($settingGroups[$groupName] as $key => $label)
                      @php $setting = $settings->firstWhere('key', $key); $val = $setting->value ?? ''; @endphp
                      <div class="col-md-6 mb-3 {{ $key === 'nav_menu' ? 'col-md-12' : '' }}">
                        <label for="setting_{{ $key }}" class="form-label">{{ $label }}</label>
                        @if (in_array($key, ['visi', 'misi']))
                          <textarea class="form-control @error($key) is-invalid @enderror"
                                    id="setting_{{ $key }}"
                                    name="{{ $key }}"
                                    rows="4">{{ old($key, $val) }}</textarea>
                        @elseif ($key === 'nav_menu')
                          <textarea class="form-control font-monospace @error($key) is-invalid @enderror"
                                    id="setting_{{ $key }}"
                                    name="{{ $key }}"
                                    rows="10"
                                    spellcheck="false">{{ old($key, $val) }}</textarea>
                          <small class="text-muted d-block mt-1">
                            Format JSON. Contoh:
                            <code class="user-select-all">[{"label":"Beranda","url":"\/","path":""},{"label":"Berita","url":"\/berita","path":"berita"}]</code>
                          </small>
                        @elseif (in_array($key, ['school_logo', 'favicon', 'headmaster_photo']))
                          <div class="mb-2">
                            <div class="d-flex align-items-center gap-3 mb-2">
                              @php $imgUrl = !empty($val) ? (str_starts_with($val, 'http') ? $val : \App\Helpers\StorageHelper::url($val)) : null; @endphp
                              @if ($imgUrl)
                                <div class="avatar avatar-md border" style="background:#f8f9fa;">
                                  <img src="{{ $imgUrl }}" alt="{{ $label }}" style="object-fit: contain; border-radius: 4px;">
                                </div>
                              @else
                                <div class="avatar avatar-md border d-flex align-items-center justify-content-center" style="background:#f8f9fa;">
                                  <i class="icon-base ti tabler-photo text-muted"></i>
                                </div>
                              @endif
                              <input type="file" class="form-control @error($key) is-invalid @enderror"
                                     id="setting_{{ $key }}"
                                     name="{{ $key }}"
                                     accept="image/*">
                            </div>
                            <input type="text" class="form-control form-control-sm mt-1 @error($key . '_url') is-invalid @enderror"
                                   id="setting_{{ $key }}_url"
                                   name="{{ $key }}_url"
                                   placeholder="atau masukkan URL eksternal..."
                                   value="{{ old($key . '_url', (str_starts_with($val, 'http') ? $val : '')) }}">
                          </div>
                        @elseif ($key === 'coming_soon_date')
                          <input type="datetime-local" class="form-control @error($key) is-invalid @enderror"
                                 id="setting_{{ $key }}"
                                 name="{{ $key }}"
                                 value="{{ old($key, $val ? \Carbon\Carbon::parse($val)->format('Y-m-d\TH:i') : '') }}">
                        @elseif (in_array($key, ['coming_soon_mode', 'maintenance_mode', 'registration_enabled']))
                          <div class="d-flex align-items-center gap-3 pt-1">
                            <div class="form-check form-switch mb-0">
                              <input type="hidden" name="{{ $key }}" value="0">
                              <input class="form-check-input" type="checkbox" id="setting_{{ $key }}" name="{{ $key }}" value="1" {{ $val === '1' ? 'checked' : '' }} style="width:3rem;height:1.5rem;cursor:pointer;">
                            </div>
                            <span class="small text-muted" id="label_{{ $key }}">
                              @if ($key === 'coming_soon_mode')
                                {{ $val === '1' ? 'Website akan menampilkan halaman Coming Soon untuk pengunjung' : 'Website berjalan normal' }}
                              @elseif ($key === 'maintenance_mode')
                                {{ $val === '1' ? 'Website akan menampilkan halaman Maintenance untuk pengunjung' : 'Website berjalan normal' }}
                              @elseif ($key === 'registration_enabled')
                                {{ $val === '1' ? 'Pengguna baru dapat mendaftar melalui halaman Register' : 'Halaman Register tidak dapat diakses' }}
                              @endif
                            </span>
                          </div>
                          @if ($key === 'coming_soon_mode' && $val === '1')
                            <div class="alert alert-warning d-flex align-items-center gap-2 py-2 px-3 mt-2 mb-0" style="font-size:0.82rem;">
                              <i class="icon-base ti tabler-alert-triangle"></i>
                              Coming Soon Mode aktif. Pengunjung akan melihat halaman Coming Soon.
                            </div>
                          @endif
                          @if ($key === 'maintenance_mode' && $val === '1')
                            <div class="alert alert-warning d-flex align-items-center gap-2 py-2 px-3 mt-2 mb-0" style="font-size:0.82rem;">
                              <i class="icon-base ti tabler-alert-triangle"></i>
                              Maintenance Mode aktif. Pengunjung akan melihat halaman Maintenance. Admin tetap bisa akses panel.
                            </div>
                          @endif
                        @else
                          <input type="text" class="form-control @error($key) is-invalid @enderror"
                                 id="setting_{{ $key }}"
                                 name="{{ $key }}"
                                 value="{{ old($key, $val) }}">
                        @endif
                        @error($key)
                          <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                      </div>
                    @endforeach
                          </div>
                        </div>
                      </div>
                    @endif
                  @endforeach

                </div>
              @endforeach
            </div>
          </div>
        </div>

        <div class="card-footer border-top d-flex justify-content-end py-3 px-4">
          <button type="submit" class="btn btn-primary">
            <i class="icon-base ti tabler-device-floppy me-1"></i> Simpan Pengaturan
          </button>
        </div>
      </form>
    </div>
  </div>
@endsection

@section('page-script')
<script>
  document.querySelectorAll('[id^="setting_coming_soon_mode"], [id^="setting_maintenance_mode"], [id^="setting_registration_enabled"]').forEach(function(el) {
    el.addEventListener('change', function() {
      var key = this.id.replace('setting_', '');
      var label = document.getElementById('label_' + key);
      if (label) {
        if (key === 'coming_soon_mode') {
          label.textContent = this.checked ? 'Website akan menampilkan halaman Coming Soon untuk pengunjung' : 'Website berjalan normal';
        } else if (key === 'maintenance_mode') {
          label.textContent = this.checked ? 'Website akan menampilkan halaman Maintenance untuk pengunjung' : 'Website berjalan normal';
        } else if (key === 'registration_enabled') {
          label.textContent = this.checked ? 'Pengguna baru dapat mendaftar melalui halaman Register' : 'Halaman Register tidak dapat diakses';
        }
      }
      var alert = this.closest('.mb-3').querySelector('.alert-warning');
      if (alert) {
        alert.style.display = this.checked ? 'flex' : 'none';
      }
    });
  });

  document.querySelectorAll('input[type="checkbox"][name="coming_soon_mode"], input[type="checkbox"][name="maintenance_mode"], input[type="checkbox"][name="registration_enabled"]').forEach(function(el) {
    if (el.checked) {
      var label = document.getElementById('label_' + el.id.replace('setting_', ''));
      if (label) {
        if (el.name === 'coming_soon_mode') {
          label.textContent = 'Website akan menampilkan halaman Coming Soon untuk pengunjung';
        } else if (el.name === 'maintenance_mode') {
          label.textContent = 'Website akan menampilkan halaman Maintenance untuk pengunjung';
        } else if (el.name === 'registration_enabled') {
          label.textContent = 'Pengguna baru dapat mendaftar melalui halaman Register';
        }
      }
    }
  });
</script>
@endsection