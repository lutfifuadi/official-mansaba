@extends('layouts/contentNavbarLayout')

@section('title', 'Pengaturan Tema')

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

  {{-- Header --}}
  <div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
      <div class="d-flex align-items-center">
        <div class="avatar avatar-sm me-3">
          <span class="avatar-initial rounded bg-label-primary">
            <i class="icon-base ti tabler-palette"></i>
          </span>
        </div>
        <div>
          <h5 class="card-title mb-0">Pengaturan Tema</h5>
          <small class="text-muted">Sesuaikan tampilan dan nuansa website MAN 1 Kebumen</small>
        </div>
      </div>
    </div>
  </div>

  {{-- Konten Utama dengan Alpine.js live preview --}}
  <div class="row" x-data="themeManager()" x-init="init()">
    {{-- Form Pengaturan --}}
    <div class="col-lg-5 col-xl-4 mb-4">
      <div class="card">
        <div class="card-header">
          <h6 class="mb-0">Opsi Tema</h6>
        </div>
        <div class="card-body">
          <form action="{{ route('admin.theme.update') }}" method="POST" id="themeForm">
            @csrf
            @method('PUT')

            {{-- Warna Primary --}}
            <div class="mb-4">
              <label class="form-label d-flex align-items-center gap-2">
                <i class="icon-base ti tabler-color-swatch"></i>
                Warna Utama (Primary)
              </label>
              <div class="d-flex align-items-center gap-3">
                {{-- Color picker visual (tanpa name, hanya untuk UI) --}}
                <input type="color"
                       class="form-control form-control-color p-1 border-0"
                       id="theme_primary_color"
                       value="{{ old('theme_primary_color', $theme['theme_primary_color']) }}"
                       style="width: 3.5rem; height: 3.5rem; cursor: pointer;"
                       @change="document.querySelector('[name=theme_primary_color]').value = $event.target.value; updatePrimaryColor($event.target.value)">
                {{-- Input teks hex (tanpa name, hanya untuk UI) --}}
                <input type="text"
                       class="form-control font-monospace"
                       value="{{ old('theme_primary_color', $theme['theme_primary_color']) }}"
                       style="max-width: 120px;"
                       @input="document.querySelector('[name=theme_primary_color]').value = $event.target.value; updatePrimaryColor($event.target.value)"
                       x-ref="hexInput">
              </div>
              {{-- Hidden input asli untuk submit --}}
              <input type="hidden" name="theme_primary_color"
                     value="{{ old('theme_primary_color', $theme['theme_primary_color']) }}">
              <div class="form-text">Klik kotak warna untuk memilih atau ketik kode hex (contoh: #1B5E42)</div>
              @error('theme_primary_color')
                <div class="invalid-feedback d-block">{{ $message }}</div>
              @enderror
            </div>

            <hr class="my-4">

            {{-- Mode Theme --}}
            <div class="mb-4">
              <label class="form-label d-flex align-items-center gap-2">
                <i class="icon-base ti tabler-sun-moon"></i>
                Mode Tampilan
              </label>
              <div class="d-flex flex-wrap gap-2">
                @php $mode = old('theme_mode', $theme['theme_mode']); @endphp
                @foreach([
                  ['value' => 'light', 'icon' => 'tabler-sun', 'label' => 'Terang'],
                  ['value' => 'dark', 'icon' => 'tabler-moon', 'label' => 'Gelap'],
                  ['value' => 'system', 'icon' => 'tabler-device-laptop', 'label' => 'Sistem'],
                ] as $opt)
                  <div class="form-check form-check-inline theme-radio-card">
                    <input class="form-check-input visually-hidden"
                           type="radio"
                           name="theme_mode"
                           id="theme_mode_{{ $opt['value'] }}"
                           value="{{ $opt['value'] }}"
                           {{ $mode === $opt['value'] ? 'checked' : '' }}
                           @change="updateThemeMode('{{ $opt['value'] }}')">
                    <label class="form-check-label d-flex flex-column align-items-center gap-1 p-3 border rounded cursor-pointer"
                           for="theme_mode_{{ $opt['value'] }}"
                           style="min-width: 90px; transition: all 0.2s;">
                      <i class="icon-base ti {{ $opt['icon'] }}" style="font-size: 1.5rem;"></i>
                      <span class="small">{{ $opt['label'] }}</span>
                    </label>
                  </div>
                @endforeach
              </div>
              @error('theme_mode')
                <div class="invalid-feedback d-block">{{ $message }}</div>
              @enderror
            </div>

            <hr class="my-4">

            {{-- Skin --}}
            <div class="mb-4">
              <label class="form-label d-flex align-items-center gap-2">
                <i class="icon-base ti tabler-layout-grid"></i>
                Gaya Tampilan (Skin)
              </label>
              @php $skin = old('theme_skin', $theme['theme_skin']); @endphp
              @foreach([
                ['value' => 'default', 'label' => 'Default', 'desc' => 'Tampilan standar dengan bayangan dan border penuh'],
                ['value' => 'bordered', 'label' => 'Bordered', 'desc' => 'Gaya dengan border di seluruh komponen, tanpa bayangan'],
                ['value' => 'mansaba', 'label' => 'Mansaba', 'desc' => 'Khas MAN 1 Kebumen — hijau, emas, krem'],
              ] as $opt)
                <div class="form-check skin-radio-card mb-2">
                  <input class="form-check-input visually-hidden"
                         type="radio"
                         name="theme_skin"
                         id="theme_skin_{{ $opt['value'] }}"
                         value="{{ $opt['value'] }}"
                         {{ $skin === $opt['value'] ? 'checked' : '' }}
                         @change="updateSkin('{{ $opt['value'] }}')">
                  <label class="form-check-label d-flex align-items-center gap-3 p-3 border rounded cursor-pointer w-100"
                         for="theme_skin_{{ $opt['value'] }}"
                         style="transition: all 0.2s;">
                    <div class="avatar avatar-sm">
                      <span class="avatar-initial rounded"
                            :class="{
                              'bg-label-secondary': '{{ $opt['value'] }}' === 'default',
                              'bg-label-info': '{{ $opt['value'] }}' === 'bordered',
                              'bg-label-success': '{{ $opt['value'] }}' === 'mansaba'
                            }">
                        <i class="icon-base ti"
                           :class="{
                             'tabler-border-all': '{{ $opt['value'] }}' === 'bordered',
                             'tabler-leaf': '{{ $opt['value'] }}' === 'mansaba',
                             'tabler-layout': '{{ $opt['value'] }}' === 'default'
                           }"></i>
                      </span>
                    </div>
                    <div>
                      <div class="fw-medium">{{ $opt['label'] }}</div>
                      <small class="text-muted">{{ $opt['desc'] }}</small>
                    </div>
                  </label>
                </div>
              @endforeach
              @error('theme_skin')
                <div class="invalid-feedback d-block">{{ $message }}</div>
              @enderror
            </div>

            <hr class="my-4">

            {{-- Semi Dark --}}
            <div class="mb-4">
              <div class="d-flex align-items-center justify-content-between">
                <label class="form-label mb-0 d-flex align-items-center gap-2" for="theme_semi_dark">
                  <i class="icon-base ti tabler-circle-half-2"></i>
                  Semi Dark Sidebar
                </label>
                <div class="form-check form-switch mb-0">
                  <input type="hidden" name="theme_semi_dark" value="0">
                  <input class="form-check-input" type="checkbox"
                         id="theme_semi_dark"
                         name="theme_semi_dark"
                         value="1"
                         {{ old('theme_semi_dark', $theme['theme_semi_dark']) === '1' ? 'checked' : '' }}
                         style="width: 3rem; height: 1.5rem; cursor: pointer;"
                         @change="toggleSemiDark">
                </div>
              </div>
              <div class="form-text">Aktifkan untuk membuat sidebar menjadi gelap saat mode terang</div>
            </div>

            {{-- Tombol Simpan --}}
            <div class="d-flex justify-content-end pt-3 border-top mt-4">
              <button type="submit" class="btn btn-primary">
                <i class="icon-base ti tabler-device-floppy me-1"></i> Simpan Pengaturan
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    {{-- Preview Card --}}
    <div class="col-lg-7 col-xl-8 mb-4">
      <div class="card">
        <div class="card-header d-flex align-items-center gap-2">
          <i class="icon-base ti tabler-eye"></i>
          <h6 class="mb-0">Pratinjau</h6>
        </div>
        <div class="card-body">
          <div class="alert alert-info d-flex align-items-center gap-2 py-2 px-3 mb-3" style="font-size:0.85rem;">
            <i class="icon-base ti tabler-info-circle"></i>
            Perubahan di form akan tercermin di pratinjau ini secara <strong>real-time</strong>. Simpan untuk menerapkan ke seluruh website.
          </div>

          {{-- Preview Card --}}
          <div class="border rounded overflow-hidden" id="theme-preview"
               :style="previewStyle">
            <div class="d-flex" style="min-height: 320px;">
              {{-- Sidebar Preview --}}
              <div class="p-3 d-none d-md-block"
                   style="width: 200px;"
                   :style="sidebarStyle">
                <div class="d-flex align-items-center gap-2 mb-4">
                  <div class="bg-white bg-opacity-25 rounded" style="width: 32px; height: 32px;"></div>
                  <div>
                    <div class="small fw-bold" :style="{ color: isSemiDark ? '#fff' : '#000' }">MAN 1</div>
                    <div class="small" :style="{ color: isSemiDark ? 'rgba(255,255,255,0.6)' : 'rgba(0,0,0,0.5)' }">Kebumen</div>
                  </div>
                </div>
                <nav class="d-flex flex-column gap-2">
                  <template x-for="item in ['Dashboard', 'Berita', 'Galeri', 'Settings']" :key="item">
                    <div class="d-flex align-items-center gap-2 px-2 py-1 rounded"
                         :style="{ backgroundColor: item === 'Dashboard' ? primaryRgba(0.15) : 'transparent', color: item === 'Dashboard' ? primaryColor : (isSemiDark ? 'rgba(255,255,255,0.7)' : 'rgba(0,0,0,0.6)') }">
                      <div :style="{ width: '6px', height: '6px', borderRadius: '50%', backgroundColor: primaryColor }"></div>
                      <span class="small" x-text="item"></span>
                    </div>
                  </template>
                </nav>
              </div>

              {{-- Content Preview --}}
              <div class="flex-grow-1 p-3" style="background-color: #f5f5f5;">
                {{-- Navbar Mini --}}
                <div class="d-flex justify-content-between align-items-center mb-3 p-2 rounded shadow-sm"
                     style="background-color: #fff;">
                  <div class="d-flex align-items-center gap-2">
                    <div class="rounded" :style="{ width: '10px', height: '10px', backgroundColor: primaryColor }"></div>
                    <span class="small fw-medium" x-text="'Selamat Datang, Admin'"></span>
                  </div>
                  <div class="d-flex align-items-center gap-2">
                    <span class="badge rounded-pill" :style="{ backgroundColor: primaryColor }">3</span>
                    <div class="rounded-circle" style="width: 28px; height: 28px; background-color: #ddd;"></div>
                  </div>
                </div>

                {{-- Stats Mini --}}
                <div class="row g-2 mb-3">
                  <template x-for="(stat, idx) in stats" :key="idx">
                    <div class="col-4">
                      <div class="p-2 rounded shadow-sm text-white text-center"
                           :style="{ backgroundColor: idx === 0 ? primaryColor : (idx === 1 ? primaryRgba(0.8) : primaryRgba(0.6)) }">
                        <div class="fw-bold" x-text="stat.value"></div>
                        <div class="small" x-text="stat.label"></div>
                      </div>
                    </div>
                  </template>
                </div>

                {{-- Card Mini --}}
                <div class="p-3 rounded shadow-sm" style="background-color: #fff;">
                  <div class="d-flex align-items-center gap-3 mb-2">
                    <div class="rounded d-flex align-items-center justify-content-center"
                         :style="{ width: '40px', height: '40px', backgroundColor: primaryRgba(0.15), color: primaryColor }">
                      <i class="icon-base ti tabler-news"></i>
                    </div>
                    <div>
                      <div class="small fw-bold">Berita Terbaru</div>
                      <div class="small text-muted">12 menit yang lalu</div>
                    </div>
                    <span class="badge ms-auto" :style="{ backgroundColor: primaryColor }">Baru</span>
                  </div>
                  <p class="small text-muted mb-0">
                    MAN 1 Kebumen meraih prestasi membanggakan dalam kompetisi sains tingkat provinsi...
                  </p>
                  <div class="mt-2">
                    <span class="small fw-medium" :style="{ color: primaryColor, cursor: 'pointer' }">Baca selengkapnya →</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('page-script')
<style>
  /* Theme Radio Card Styles */
  .theme-radio-card input[type="radio"]:checked + label {
    border-color: var(--bs-primary, #1B5E42) !important;
    background-color: rgba(var(--bs-primary-rgb, 27, 94, 66), 0.08) !important;
    box-shadow: 0 0 0 2px var(--bs-primary, #1B5E42);
  }
  .theme-radio-card label:hover {
    border-color: var(--bs-primary, #1B5E42) !important;
    background-color: rgba(var(--bs-primary-rgb, 27, 94, 66), 0.04) !important;
  }

  /* Skin Radio Card Styles */
  .skin-radio-card input[type="radio"]:checked + label {
    border-color: var(--bs-primary, #1B5E42) !important;
    background-color: rgba(var(--bs-primary-rgb, 27, 94, 66), 0.06) !important;
  }
  .skin-radio-card label:hover {
    border-color: var(--bs-primary, #1B5E42) !important;
  }

  /* Color input styling */
  input[type="color"]::-webkit-color-swatch-wrapper {
    padding: 0;
  }
  input[type="color"]::-webkit-color-swatch {
    border: 2px solid #e0e0e0;
    border-radius: 6px;
  }
</style>

<script>
  function themeManager() {
    return {
      // State
      primaryColor: '{{ $theme['theme_primary_color'] }}',
      themeMode: '{{ $theme['theme_mode'] }}',
      skin: '{{ $theme['theme_skin'] }}',
      semiDark: {{ $theme['theme_semi_dark'] === '1' ? 'true' : 'false' }},
      stats: [
        { label: 'Siswa', value: '1.248' },
        { label: 'Guru', value: '86' },
        { label: 'Prestasi', value: '47' },
      ],

      // Computed
      get isSemiDark() {
        return this.semiDark;
      },
      get isDarkMode() {
        return this.themeMode === 'dark';
      },
      get previewStyle() {
        const isDark = this.themeMode === 'dark' || (this.themeMode === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches);
        return {
          backgroundColor: isDark ? '#1a1a2e' : '#ffffff',
          color: isDark ? '#e0e0e0' : '#333333',
          borderColor: isDark ? '#333' : '#ddd',
        };
      },
      get sidebarStyle() {
        if (this.isSemiDark && !this.isDarkMode) {
          return {
            backgroundColor: '#1a1a2e',
            color: '#fff',
          };
        }
        const isDark = this.isDarkMode;
        return {
          backgroundColor: isDark ? '#16213e' : '#f8f9fa',
          color: isDark ? '#e0e0e0' : '#333',
        };
      },

      // Methods
      init() {
        // Sync hidden input and hex input on page load
        const hiddenInput = document.querySelector('[name=theme_primary_color]');
        if (hiddenInput) hiddenInput.value = this.primaryColor;
        if (this.$refs.hexInput) this.$refs.hexInput.value = this.primaryColor;
      },
      updatePrimaryColor(color) {
        this.primaryColor = color;
        // Sync hidden input (untuk submit)
        const hiddenInput = document.querySelector('[name=theme_primary_color]');
        if (hiddenInput) hiddenInput.value = color;
        // Sync hex text display
        if (this.$refs.hexInput) this.$refs.hexInput.value = color;
        // Visual feedback on the color picker
        const colorInput = document.querySelector('#theme_primary_color');
        if (colorInput) colorInput.value = color;
      },
      updateThemeMode(mode) {
        this.themeMode = mode;
      },
      updateSkin(newSkin) {
        this.skin = newSkin;
      },
      toggleSemiDark() {
        this.semiDark = !this.semiDark;
      },
      primaryRgba(opacity) {
        // Convert hex to rgba
        const hex = this.primaryColor.replace('#', '');
        const r = parseInt(hex.substring(0, 2), 16);
        const g = parseInt(hex.substring(2, 4), 16);
        const b = parseInt(hex.substring(4, 6), 16);
        return `rgba(${r}, ${g}, ${b}, ${opacity})`;
      },
    };
  }
</script>
@endsection
