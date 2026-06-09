@extends('layouts/contentNavbarLayout')

@section('title', 'Menu Navigasi Publik')

@section('vendor-script')
  @vite(['resources/assets/vendor/libs/sortablejs/sortable.js'])
@endsection

@section('content')
  @if(session('success'))
    <div class="alert alert-success alert-dismissible" role="alert" aria-live="polite">
      {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  @if(session('error'))
    <div class="alert alert-danger alert-dismissible" role="alert" aria-live="polite">
      {{ session('error') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  <div class="toast-container position-fixed top-0 end-0 p-3" id="toast-container" style="z-index: 9999;"></div>

  <div class="card">
    <div class="card-header border-bottom d-flex justify-content-between align-items-center">
      <div class="d-flex align-items-center">
        <div class="avatar avatar-sm me-3">
          <span class="avatar-initial rounded bg-label-secondary">
            <i class="icon-base ti tabler-menu-2"></i>
          </span>
        </div>
        <h5 class="card-title mb-0">Menu Navigasi Publik</h5>
      </div>
      <div>
        <a href="{{ url('/') }}" target="_blank" class="btn btn-outline-secondary me-2">
          <i class="icon-base ti tabler-eye me-1"></i> Lihat Hasil
        </a>
        <button type="button" class="btn btn-outline-danger me-2" onclick="window.location.reload()">
          <i class="icon-base ti tabler-refresh me-1"></i> Reset
        </button>
        <button type="button" class="btn btn-primary" id="btn-save-menu">
          <i class="icon-base ti tabler-device-floppy me-1"></i> Simpan Semua
        </button>
      </div>
    </div>
    <div class="card-body p-4">
      <p class="text-muted mb-4">
        <i class="icon-base ti tabler-info-circle me-1"></i>
        Seret (<i class="icon-base ti tabler-grip-vertical"></i>) item untuk mengurutkan. Klik tombol "Simpan Semua" untuk menyimpan perubahan.
      </p>

      <div id="menu-items-container" class="mb-4">
        <div id="menu-sortable-list" class="list-group">
          @forelse($items as $index => $item)
            <div class="list-group-item menu-item d-flex align-items-center gap-3 py-3 px-3" data-index="{{ $index }}">
              <button type="button"
                      class="btn btn-sm btn-icon-picker p-1 border rounded"
                      data-icon="{{ $item['icon'] ?? '' }}"
                      title="Pilih ikon"
                      style="width:36px;height:36px;flex-shrink:0;">
                <i class="icon-base ti {{ !empty($item['icon']) ? $item['icon'] : 'tabler-icons-off' }}" style="font-size:1.2rem;"></i>
              </button>
              <div class="drag-handle text-muted cursor-grab" aria-label="Seret untuk mengurutkan" style="touch-action: none;">
                <i class="icon-base ti tabler-grip-vertical" aria-hidden="true"></i>
                <span class="visually-hidden">Seret</span>
              </div>
              <div class="flex-grow-1 row gx-3 align-items-center">
                <div class="col-md-5 mb-2 mb-md-0">
                  <input type="text" class="form-control form-control-sm menu-label" value="{{ $item['label'] }}" placeholder="Label (mis: Berita)" style="min-height: 44px;">
                </div>
                <div class="col-md-5 mb-2 mb-md-0">
                  <input type="text" class="form-control form-control-sm menu-url" value="{{ $item['url'] }}" placeholder="URL (mis: /berita)" style="min-height: 44px;">
                </div>
                <div class="col-md-2">
                  <input type="hidden" class="menu-path" value="{{ $item['path'] }}">
                </div>
              </div>
              <button type="button" class="btn btn-sm btn-text-danger rounded-pill btn-remove-item" title="Hapus item">
                <i class="icon-base ti tabler-trash" aria-hidden="true"></i>
              </button>
            </div>
          @empty
            <div class="text-center py-5 empty-state" id="empty-state">
              <div class="avatar avatar-lg mb-3">
                <span class="avatar-initial rounded bg-label-secondary">
                  <i class="icon-base ti tabler-menu-2-off icon-lg"></i>
                </span>
              </div>
              <p class="text-muted mb-0">Belum ada item menu.</p>
              <button type="button" class="btn btn-primary mt-3" id="btn-add-item-empty">
                <i class="icon-base ti tabler-plus me-1"></i> Tambah Item
              </button>
            </div>
          @endforelse
        </div>
      </div>

      <div id="alert-container" class="mb-3"></div>

      <div class="d-flex gap-2">
        <button type="button" class="btn btn-outline-primary" id="btn-add-item">
          <i class="icon-base ti tabler-plus me-1"></i> Tambah Item
        </button>
        <button type="button" class="btn btn-primary" id="btn-save-menu-bottom">
          <i class="icon-base ti tabler-device-floppy me-1"></i> Simpan Semua
        </button>
      </div>
    </div>
  </div>

  <input type="hidden" id="menu-store-url" value="{{ route('admin.menus.store') }}">
  <input type="hidden" id="menu-csrf-token" value="{{ csrf_token() }}">

<!-- Icon Picker Modal -->
<div class="modal fade" id="iconPickerModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Pilih Ikon</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="text" class="form-control mb-3" id="icon-search" placeholder="Cari ikon..." autofocus>
        <div id="icon-picker-grid" class="row g-2" style="min-height:200px;">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-danger" id="btn-clear-icon">
          <i class="icon-base ti tabler-trash me-1"></i> Hapus Ikon
        </button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>
@endsection

@section('page-script')
<style>
.cursor-grab { cursor: grab; }
.cursor-grab:active { cursor: grabbing; }
.sortable-ghost {
  opacity: 0.5;
  background: var(--bs-secondary-bg) !important;
  border: 2px dashed var(--bs-secondary-color) !important;
  border-radius: var(--bs-border-radius);
}
.sortable-drag {
  opacity: 0.8 !important;
  transform: scale(1.02);
  box-shadow: 0 8px 24px rgba(0,0,0,0.15);
}
.sortable-chosen {
  background: var(--bs-primary-bg-subtle) !important;
  border-color: var(--bs-primary) !important;
}
</style>
<script>
  const AVAILABLE_ICONS = [
    {class: 'tabler-home', label: 'Beranda'},
    {class: 'tabler-news', label: 'Berita'},
    {class: 'tabler-file-text', label: 'Artikel'},
    {class: 'tabler-photo', label: 'Galeri'},
    {class: 'tabler-trophy', label: 'Prestasi'},
    {class: 'tabler-award', label: 'Penghargaan'},
    {class: 'tabler-users', label: 'Siswa'},
    {class: 'tabler-star', label: 'Unggulan'},
    {class: 'tabler-building', label: 'Profil'},
    {class: 'tabler-school', label: 'Madrasah'},
    {class: 'tabler-info-circle', label: 'Info'},
    {class: 'tabler-phone', label: 'Kontak'},
    {class: 'tabler-mail', label: 'Email'},
    {class: 'tabler-map-pin', label: 'Lokasi'},
    {class: 'tabler-clock', label: 'Jam'},
    {class: 'tabler-calendar', label: 'Kalender'},
    {class: 'tabler-book', label: 'Perpustakaan'},
    {class: 'tabler-video', label: 'Video'},
    {class: 'tabler-music', label: 'Musik'},
    {class: 'tabler-heart', label: 'Favorit'},
    {class: 'tabler-settings', label: 'Pengaturan'},
    {class: 'tabler-user', label: 'Pengguna'},
    {class: 'tabler-bell', label: 'Pengumuman'},
    {class: 'tabler-message', label: 'Pesan'},
    {class: 'tabler-share', label: 'Berbagi'},
    {class: 'tabler-download', label: 'Unduhan'},
    {class: 'tabler-search', label: 'Pencarian'},
    {class: 'tabler-link', label: 'Tautan'},
    {class: 'tabler-external-link', label: 'Eksternal'},
    {class: 'tabler-world', label: 'Website'},
  ];

  document.addEventListener('DOMContentLoaded', function() {
    const sortableList = document.getElementById('menu-sortable-list');
    const container = document.getElementById('menu-items-container');
    let iconPickerTarget = null;

    function renderIconGrid(filter) {
      const grid = document.getElementById('icon-picker-grid');
      const search = (filter || '').toLowerCase();
      let html = '';
      AVAILABLE_ICONS.forEach(function(icon) {
        if (search && !icon.label.toLowerCase().includes(search) && !icon.class.toLowerCase().includes(search)) {
          return;
        }
        html += '<div class="col-3 col-md-2">' +
          '<button type="button" class="btn btn-outline-secondary w-100 d-flex flex-column align-items-center py-2 px-1 icon-option" data-icon-class="' + icon.class + '" style="border-radius:8px;min-height:70px;">' +
            '<i class="icon-base ti ' + icon.class + '" style="font-size:1.5rem;"></i>' +
            '<small class="mt-1 text-truncate w-100" style="font-size:0.65rem;">' + icon.label + '</small>' +
          '</button>' +
        '</div>';
      });
      grid.innerHTML = html || '<div class="col-12 text-center text-muted py-4">Ikon tidak ditemukan</div>';
    }

    document.addEventListener('click', function(e) {
      var btn = e.target.closest('.btn-icon-picker');
      if (!btn) return;
      iconPickerTarget = btn;
      var currentIcon = btn.getAttribute('data-icon') || '';
      renderIconGrid('');
      document.getElementById('icon-search').value = '';
      document.querySelectorAll('.icon-option').forEach(function(opt) {
        var cls = opt.getAttribute('data-icon-class');
        if (cls === currentIcon) {
          opt.classList.add('btn-primary');
          opt.classList.remove('btn-outline-secondary');
        } else {
          opt.classList.remove('btn-primary');
          opt.classList.add('btn-outline-secondary');
        }
      });
      var modal = new bootstrap.Modal(document.getElementById('iconPickerModal'));
      modal.show();
      setTimeout(function() {
        document.getElementById('icon-search').focus();
      }, 300);
    });

    document.getElementById('icon-picker-grid').addEventListener('click', function(e) {
      var opt = e.target.closest('.icon-option');
      if (!opt) return;
      var iconClass = opt.getAttribute('data-icon-class');
      document.querySelectorAll('.icon-option').forEach(function(o) {
        o.classList.remove('btn-primary');
        o.classList.add('btn-outline-secondary');
      });
      opt.classList.remove('btn-outline-secondary');
      opt.classList.add('btn-primary');
      if (iconPickerTarget) {
        iconPickerTarget.setAttribute('data-icon', iconClass);
        var iconEl = iconPickerTarget.querySelector('i');
        if (iconEl) {
          iconEl.className = 'icon-base ti ' + iconClass;
        }
      }
    });

    document.getElementById('btn-clear-icon').addEventListener('click', function() {
      if (iconPickerTarget) {
        iconPickerTarget.setAttribute('data-icon', '');
        var iconEl = iconPickerTarget.querySelector('i');
        if (iconEl) {
          iconEl.className = 'icon-base ti tabler-icons-off';
        }
      }
      document.querySelectorAll('.icon-option').forEach(function(o) {
        o.classList.remove('btn-primary');
        o.classList.add('btn-outline-secondary');
      });
      var modal = bootstrap.Modal.getInstance(document.getElementById('iconPickerModal'));
      if (modal) modal.hide();
    });

    document.getElementById('icon-search').addEventListener('input', function() {
      renderIconGrid(this.value);
    });

    document.addEventListener('shown.bs.modal', function(e) {
      if (e.target.id === 'iconPickerModal') {
        document.getElementById('icon-search').focus();
      }
    });

    let sortable = Sortable.create(sortableList, {
      handle: '.drag-handle',
      animation: 150,
      dragClass: 'sortable-drag',
      chosenClass: 'sortable-chosen',
      delay: 150,
      delayOnTouchOnly: true,
      onEnd: function() {
        reindexItems();
      }
    });

    function reindexItems() {
      const items = sortableList.querySelectorAll('.menu-item');
      items.forEach(function(item, idx) {
        item.dataset.index = idx;
      });
    }

    function slugify(text) {
      return text
        .toLowerCase()
        .replace(/&/g, 'dan')
        .replace(/@/g, 'at')
        .replace(/[^a-z0-9\s-]/g, '')
        .replace(/[\s-]+/g, '-')
        .replace(/^-+|-+$/g, '');
    }

    function autoFillUrl(input) {
      var label = input.value.trim();
      if (!label) return;
      var parent = input.closest('.menu-item');
      if (!parent) return;
      var urlInput = parent.querySelector('.menu-url');
      var pathInput = parent.querySelector('.menu-path');

      if (urlInput.dataset.manual === 'true') {
        pathInput.value = slugify(label);
        return;
      }

      var slug = slugify(label);
      if (slug === 'beranda' || slug === 'home' || slug === '') {
        urlInput.value = '/';
        pathInput.value = '';
      } else {
        urlInput.value = '/' + slug;
        pathInput.value = slug;
      }
    }

    sortableList.addEventListener('input', function(e) {
      if (e.target.classList.contains('menu-label')) {
        autoFillUrl(e.target);
      }
      if (e.target.classList.contains('menu-url')) {
        e.target.dataset.manual = 'true';
      }
    });

    sortableList.addEventListener('focusout', function(e) {
      if (e.target.classList.contains('menu-url')) {
        var parent = e.target.closest('.menu-item');
        var pathInput = parent.querySelector('.menu-path');
        var url = e.target.value.trim();
        if (url) {
          pathInput.value = url.replace(/^\//, '').replace(/\/+$/, '');
        }
      }
    });

    function createMenuItem(index) {
      var div = document.createElement('div');
      div.className = 'list-group-item menu-item d-flex align-items-center gap-3 py-3 px-3';
      div.dataset.index = index;
      div.innerHTML =
        '<button type="button" class="btn btn-sm btn-icon-picker p-1 border rounded" data-icon="" title="Pilih ikon" style="width:36px;height:36px;flex-shrink:0;">' +
          '<i class="icon-base ti tabler-icons-off" style="font-size:1.2rem;"></i>' +
        '</button>' +
        '<div class="drag-handle text-muted cursor-grab" aria-label="Seret untuk mengurutkan" style="touch-action: none;">' +
          '<i class="icon-base ti tabler-grip-vertical" aria-hidden="true"></i>' +
          '<span class="visually-hidden">Seret</span>' +
        '</div>' +
        '<div class="flex-grow-1 row gx-3 align-items-center">' +
          '<div class="col-md-5 mb-2 mb-md-0">' +
            '<input type="text" class="form-control form-control-sm menu-label" value="" placeholder="Label (mis: Berita)" style="min-height: 44px;">' +
          '</div>' +
          '<div class="col-md-5 mb-2 mb-md-0">' +
            '<input type="text" class="form-control form-control-sm menu-url" value="" placeholder="URL (mis: /berita)" style="min-height: 44px;">' +
          '</div>' +
          '<div class="col-md-2">' +
            '<input type="hidden" class="menu-path" value="">' +
          '</div>' +
        '</div>' +
        '<button type="button" class="btn btn-sm btn-text-danger rounded-pill btn-remove-item" title="Hapus item">' +
          '<i class="icon-base ti tabler-trash" aria-hidden="true"></i>' +
        '</button>';
      return div;
    }

    function removeEmptyState() {
      var emptyState = document.querySelector('.empty-state');
      if (emptyState) {
        emptyState.remove();
      }
    }

    document.getElementById('btn-add-item').addEventListener('click', function() {
      removeEmptyState();
      var items = sortableList.querySelectorAll('.menu-item');
      var newIndex = items.length;
      var newItem = createMenuItem(newIndex);
      sortableList.appendChild(newItem);
    });

    var btnAddEmpty = document.getElementById('btn-add-item-empty');
    if (btnAddEmpty) {
      btnAddEmpty.addEventListener('click', function() {
        removeEmptyState();
        var items = sortableList.querySelectorAll('.menu-item');
        var newIndex = items.length;
        var newItem = createMenuItem(newIndex);
        sortableList.appendChild(newItem);
      });
    }

    function showToast(message, type) {
      var container = document.getElementById('toast-container');
      var toastId = 'toast-' + Date.now();
      var iconClass = type === 'success' ? 'tabler-check-circle text-success' : 'tabler-alert-circle text-danger';
      var title = type === 'success' ? 'Berhasil' : 'Gagal';

      var toastHtml =
        '<div id="' + toastId + '" class="toast" role="alert" aria-live="polite" aria-atomic="true" data-bs-autohide="true" data-bs-delay="5000">' +
          '<div class="toast-header">' +
            '<i class="icon-base ti ' + iconClass + ' me-2 fs-5"></i>' +
            '<strong class="me-auto">' + title + '</strong>' +
            '<button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Tutup"></button>' +
          '</div>' +
          '<div class="toast-body">' +
            message +
          '</div>' +
        '</div>';

      container.insertAdjacentHTML('beforeend', toastHtml);

      var toastEl = document.getElementById(toastId);
      var toast = new bootstrap.Toast(toastEl);
      toast.show();

      toastEl.addEventListener('hidden.bs.toast', function() {
        toastEl.remove();
      });
    }

    function showValidationError(message) {
      var alertContainer = document.getElementById('alert-container');
      alertContainer.innerHTML =
        '<div class="alert alert-warning alert-dismissible d-flex align-items-center gap-2" role="alert">' +
          '<i class="icon-base ti tabler-alert-triangle"></i>' +
          '<span>' + message + '</span>' +
          '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>' +
        '</div>';
    }

    sortableList.addEventListener('click', function(e) {
      var btn = e.target.closest('.btn-remove-item');
      if (!btn) return;
      var items = sortableList.querySelectorAll('.menu-item');
      if (items.length <= 1) {
        showValidationError('Minimal harus ada satu item menu.');
        return;
      }
      if (!confirm('Yakin ingin menghapus item menu ini?')) return;
      var item = btn.closest('.menu-item');
      sortableList.removeChild(item);
      reindexItems();
    });

    function collectMenuData() {
      var items = [];
      var itemEls = sortableList.querySelectorAll('.menu-item');
      itemEls.forEach(function(item) {
        var label = item.querySelector('.menu-label').value.trim();
        var url = item.querySelector('.menu-url').value.trim();
        var path = item.querySelector('.menu-path').value.trim();
        var iconBtn = item.querySelector('.btn-icon-picker');
        var icon = iconBtn ? iconBtn.getAttribute('data-icon') || '' : '';
        if (!label) return;
        items.push({
          label: label,
          url: url || '/' + path,
          path: path || label.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-+|-+$/g, ''),
          icon: icon
        });
      });
      return items;
    }

    function saveMenu() {
      var alertContainer = document.getElementById('alert-container');
      alertContainer.innerHTML = '';

      var items = collectMenuData();
      if (items.length === 0) {
        showValidationError('Minimal harus ada satu item menu dengan label.');
        return;
      }

      var saveBtns = document.querySelectorAll('#btn-save-menu, #btn-save-menu-bottom');
      saveBtns.forEach(function(btn) {
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Menyimpan...';
      });

      var url = document.getElementById('menu-store-url').value;
      var token = document.getElementById('menu-csrf-token').value;

      fetch(url, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': token,
          'Accept': 'application/json'
        },
        body: JSON.stringify({ items: items })
      })
      .then(function(res) {
        if (!res.ok) {
          return res.json().then(function(err) { throw err; });
        }
        return res.json();
      })
      .then(function(data) {
        showToast(data.message, 'success');
      })
      .catch(function(err) {
        var msg = err && err.message ? err.message : 'Gagal menyimpan menu.';
        showToast(msg, 'error');
      })
      .finally(function() {
        saveBtns.forEach(function(btn) {
          btn.disabled = false;
          btn.innerHTML = '<i class="icon-base ti tabler-device-floppy me-1"></i> Simpan Semua';
        });
      });
    }

    document.getElementById('btn-save-menu').addEventListener('click', saveMenu);
    document.getElementById('btn-save-menu-bottom').addEventListener('click', saveMenu);
  });
</script>
@endsection
