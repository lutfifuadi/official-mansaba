@php use Illuminate\Support\Str; @endphp
@extends('layouts/layoutFront')
@section('title', 'Layanan Online')
@php $breadcrumbs = [['name' => 'Beranda', 'url' => url('/')], ['name' => 'Layanan']]; @endphp

@section('content')

{{-- Page Header --}}
<div class="mansaba-page-header">
  <div class="container text-center position-relative" style="z-index:1;">
    <nav aria-label="breadcrumb" class="breadcrumb-islami mb-2">
      <ol class="breadcrumb justify-content-center">
        <li class="breadcrumb-item"><a href="{{ route('home') }}" style="color:rgba(255,255,255,0.6);text-decoration:none;">Beranda</a></li>
        <li class="breadcrumb-item active">Layanan</li>
      </ol>
    </nav>
    <h1 class="mb-2">Pusat Layanan Digital</h1>
    <p class="header-subtitle">Akses berbagai layanan online MAN 1 Kota Bandung dengan mudah dan cepat.</p>
  </div>
</div>

<section class="section-bg-cream py-6">
  <div class="container-xxl">

    {{-- Search & Filter --}}
    <div x-data="serviceCatalog()" x-init="init()" class="mb-5">
      <div class="mansaba-card p-3 mb-3">
        <div class="row align-items-center g-3">
          <div class="col">
            <div class="position-relative">
              <i class="ti tabler-search" style="position:absolute;left:16px;top:50%;transform:translateY(-50%);color:#aaa;font-size:1.1rem;"></i>
              <input type="text" x-model="search" class="form-control ps-5 py-2" placeholder="Cari layanan..." style="border-radius:5px;border:1.5px solid #e0e0e0;padding-left:2.7rem !important;">
            </div>
          </div>
          <div class="col-auto">
            <span class="badge-mansaba-green badge px-3 py-2" style="font-size:0.8rem;">
              <span x-text="filteredCount"></span> Layanan
            </span>
          </div>
        </div>
      </div>

      {{-- Category Filter Chips --}}
      <div class="d-flex flex-wrap gap-2 mb-4">
        <button @click="category = 'all'" :class="category === 'all' ? 'service-chip-active' : 'service-chip'" class="btn btn-sm px-3 py-1 rounded-pill fw-semibold">
          Semua
        </button>
        <template x-for="cat in categories" :key="cat">
          <button @click="category = cat" :class="category === cat ? 'service-chip-active' : 'service-chip'" class="btn btn-sm px-3 py-1 rounded-pill fw-semibold">
            <span x-text="cat"></span>
          </button>
        </template>
      </div>

      {{-- Service Grid --}}
      <div class="row g-4" id="services-grid">
        <template x-for="(svc, index) in filteredServices" :key="svc.id">
          <div class="col-6 col-md-4 col-lg-3 service-fade-up" x-show="true" x-transition.duration.300ms>
            <div class="mansaba-card service-grid-card h-100 d-flex flex-column" :style="'border-top:3px solid ' + (svc.icon_color || '#1B5E42')">
              <div class="card-body d-flex flex-column align-items-center text-center">
                <div class="service-grid-icon mb-3" :style="'background:' + (svc.icon_color || '#1B5E42') + '22;color:' + (svc.icon_color || '#1B5E42')">
                  <i :class="'ti tabler-' + svc.icon" style="font-size:1.5rem;"></i>
                </div>
                <h6 class="fw-bold mb-1" style="color:var(--mansaba-dark);" x-text="svc.name"></h6>
                <span class="badge-mansaba-green badge px-2 py-1 mb-2" style="font-size:0.7rem;background:rgba(27,94,66,0.08);color:#1B5E42;" x-text="svc.category"></span>
                <p class="text-muted mb-3 flex-grow-1 service-grid-desc" x-text="svc.description ? svc.description.substring(0, 100) + (svc.description.length > 100 ? '...' : '') : 'Tidak ada deskripsi'"></p>
                <div class="d-flex gap-2 w-100">
                  <a :href="'{{ url('/layanan') }}/' + svc.slug" class="btn btn-outline-success btn-sm flex-grow-1" style="border-color:var(--mansaba-green);color:var(--mansaba-green);border-radius:5px;font-weight:600;">
                    <i class="ti tabler-info-circle me-1" style="font-size:0.8rem;"></i>Detail
                  </a>
                  <template x-if="svc.url && svc.url !== '#'">
                    <a :href="svc.url" target="_blank" rel="noopener" class="btn btn-success btn-sm flex-grow-1" style="background:var(--mansaba-green);border-color:var(--mansaba-green);border-radius:5px;font-weight:600;">
                      <i class="ti tabler-external-link me-1" style="font-size:0.8rem;"></i>Akses
                    </a>
                  </template>
                  <template x-if="!svc.url || svc.url === '#'">
                    <button class="btn btn-sm flex-grow-1" disabled style="border-radius:5px;font-weight:600;background:#e0e0e0;border-color:#e0e0e0;color:#999;">
                      <i class="ti tabler-clock me-1" style="font-size:0.8rem;"></i>Segera Hadir
                    </button>
                  </template>
                </div>
              </div>
            </div>
          </div>
        </template>

        {{-- Empty State (fallback for no JS) --}}
        <div class="col-12" x-show="filteredServices.length === 0" x-cloak>
          <div class="mansaba-card text-center py-5">
            <i class="ti tabler-world-off" style="font-size:3.5rem;color:#ccc;"></i>
            <h5 class="mt-3 fw-bold" style="color:var(--mansaba-dark);">Layanan Tidak Ditemukan</h5>
            <p class="text-muted">Tidak ada layanan yang sesuai dengan kriteria pencarian Anda.</p>
            <button @click="search = ''; category = 'all'" class="btn btn-sm px-4" style="background:var(--mansaba-green);color:#fff;border-radius:5px;font-weight:600;">
              <i class="ti tabler-refresh me-1"></i>Reset Filter
            </button>
          </div>
        </div>
      </div>
    </div>

    <div class="mb-5"></div>

  </div>
</section>

@endsection

@section('page-style')
<style>
.service-grid-card {
  transition: transform 0.25s ease, box-shadow 0.25s ease;
  border-radius: 10px;
  overflow: hidden;
}
.service-grid-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 8px 25px rgba(27,94,66,0.12);
}
.service-grid-icon {
  width: 52px;
  height: 52px;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 50%;
  flex-shrink: 0;
}
.service-grid-desc {
  font-size: 0.82rem;
  line-height: 1.5;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
.service-chip {
  background: #fff;
  border: 1.5px solid #e0e0e0;
  color: #666;
  transition: all 0.2s ease;
}
.service-chip:hover {
  border-color: var(--mansaba-green);
  color: var(--mansaba-green);
  background: rgba(27,94,66,0.04);
}
.service-chip-active {
  background: var(--mansaba-green) !important;
  border-color: var(--mansaba-green) !important;
  color: #fff !important;
}
.service-fade-up {
  animation: fadeInUp 0.4s ease forwards;
  opacity: 0;
}
@keyframes fadeInUp {
  from { opacity: 0; transform: translateY(15px); }
  to { opacity: 1; transform: translateY(0); }
}
[x-cloak] { display: none !important; }
</style>
@endsection

@section('page-script')
<script>
function serviceCatalog() {
  return {
    search: '',
    category: 'all',
    services: @json($servicesJson),
    categories: @json($categories),
    get filteredServices() {
      return this.services.filter(s => {
        const matchSearch = !this.search || s.name.toLowerCase().includes(this.search.toLowerCase()) || (s.description && s.description.toLowerCase().includes(this.search.toLowerCase()));
        const matchCategory = this.category === 'all' || s.category === this.category;
        return matchSearch && matchCategory;
      });
    },
    get filteredCount() {
      return this.filteredServices.length;
    },
    init() {}
  };
}
</script>
@endsection
