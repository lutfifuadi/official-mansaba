@extends('layouts/contentNavbarLayout')

@section('title', 'Pengaturan Periode Daftar Ulang')

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

<div class="row g-6">
  <!-- Form Pengaturan Periode -->
  <div class="col-md-5">
    <div class="card">
      <div class="card-header border-bottom">
        <h5 class="card-title mb-0">Tambah / Edit Periode</h5>
      </div>
      <div class="card-body pt-6">
        <form action="{{ route('admin.daftar-ulang-periode.store') }}" method="POST">
          @csrf
          <!-- Input ID hidden untuk edit mode -->
          <input type="hidden" id="periode_id" name="id" value="{{ old('id') }}">

          <!-- Input Tahun Ajaran -->
          <div class="mb-4">
            <label for="tahun_ajaran" class="form-label">Tahun Ajaran</label>
            <input type="text" class="form-control @error('tahun_ajaran') is-invalid @enderror" id="tahun_ajaran" name="tahun_ajaran" placeholder="Contoh: 2026/2027" value="{{ old('tahun_ajaran') }}" required>
            @error('tahun_ajaran')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <!-- Pilihan Kelas Target -->
          <div class="mb-4">
            <label for="kelas_target" class="form-label">Kelas Target</label>
            <select class="form-select @error('kelas_target') is-invalid @enderror" id="kelas_target" name="kelas_target" required>
              <option value="">Pilih Kelas Target</option>
              <option value="XI" {{ old('kelas_target') == 'XI' ? 'selected' : '' }}>Kelas XI</option>
              <option value="XII" {{ old('kelas_target') == 'XII' ? 'selected' : '' }}>Kelas XII</option>
            </select>
            @error('kelas_target')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <!-- Date Picker: Tanggal Buka -->
          <div class="mb-4">
            <label for="tanggal_buka" class="form-label">Tanggal Buka</label>
            <input type="date" class="form-control @error('tanggal_buka') is-invalid @enderror" id="tanggal_buka" name="tanggal_buka" value="{{ old('tanggal_buka') }}" required>
            @error('tanggal_buka')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <!-- Date Picker: Tanggal Tutup -->
          <div class="mb-4">
            <label for="tanggal_tutup" class="form-label">Tanggal Tutup</label>
            <input type="date" class="form-control @error('tanggal_tutup') is-invalid @enderror" id="tanggal_tutup" name="tanggal_tutup" value="{{ old('tanggal_tutup') }}" required>
            @error('tanggal_tutup')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <!-- Toggle: Aktifkan Periode -->
          <div class="mb-6">
            <div class="form-check form-switch">
              <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active') ? 'checked' : '' }}>
              <label class="form-check-label" for="is_active">Aktifkan Periode Ini</label>
            </div>
            <small class="text-muted d-block mt-1">Mengaktifkan periode ini akan otomatis menonaktifkan periode lain untuk kelas target yang sama.</small>
          </div>

          <div class="d-flex justify-content-end gap-2">
            <button type="reset" class="btn btn-label-secondary">Reset</button>
            <button type="submit" class="btn btn-primary">Simpan Periode</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Tabel Daftar Periode -->
  <div class="col-md-7">
    <div class="card h-100">
      <div class="card-header border-bottom">
        <h5 class="card-title mb-0">Daftar Periode Pernah Dibuat</h5>
      </div>
      <div class="table-responsive">
        <table class="table table-hover mb-0">
          <thead>
            <tr>
              <th>Tahun Ajaran</th>
              <th>Kelas Target</th>
              <th>Tgl Buka</th>
              <th>Tgl Tutup</th>
              <th>Status</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse($periodes as $periode)
              <tr>
                <td><span class="fw-semibold">{{ $periode->tahun_ajaran }}</span></td>
                <td><span class="badge bg-label-primary">Kelas {{ $periode->kelas_target }}</span></td>
                <td>{{ $periode->tanggal_buka->format('d M Y') }}</td>
                <td>{{ $periode->tanggal_tutup->format('d M Y') }}</td>
                <td>
                  @if($periode->is_active && today()->between($periode->tanggal_buka, $periode->tanggal_tutup))
                    <span class="badge bg-success">Aktif</span>
                  @else
                    <span class="badge bg-secondary">Tidak Aktif</span>
                  @endif
                </td>
                <td>
                  <div class="d-flex gap-1">
                    <button type="button" class="btn btn-sm btn-icon btn-text-secondary rounded-pill waves-effect btn-edit-periode" 
                      data-id="{{ $periode->id }}" 
                      data-tahun="{{ $periode->tahun_ajaran }}" 
                      data-kelas="{{ $periode->kelas_target }}" 
                      data-buka="{{ $periode->tanggal_buka->format('Y-m-d') }}" 
                      data-tutup="{{ $periode->tanggal_tutup->format('Y-m-d') }}" 
                      data-active="{{ $periode->is_active ? '1' : '0' }}" 
                      title="Edit">
                      <i class="icon-base ti tabler-pencil"></i>
                    </button>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="text-center py-4 text-muted">
                  Belum ada periode yang dibuat.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@section('page-script')
<script>
document.addEventListener('DOMContentLoaded', function() {
  const editButtons = document.querySelectorAll('.btn-edit-periode');
  const idInput = document.getElementById('periode_id');
  const tahunInput = document.getElementById('tahun_ajaran');
  const kelasInput = document.getElementById('kelas_target');
  const bukaInput = document.getElementById('tanggal_buka');
  const tutupInput = document.getElementById('tanggal_tutup');
  const activeInput = document.getElementById('is_active');
  const formTitle = document.querySelector('.card-title');

  editButtons.forEach(btn => {
    btn.addEventListener('click', function() {
      const id = this.getAttribute('data-id');
      const tahun = this.getAttribute('data-tahun');
      const kelas = this.getAttribute('data-kelas');
      const buka = this.getAttribute('data-buka');
      const tutup = this.getAttribute('data-tutup');
      const active = this.getAttribute('data-active') === '1';

      idInput.value = id;
      tahunInput.value = tahun;
      kelasInput.value = kelas;
      bukaInput.value = buka;
      tutupInput.value = tutup;
      activeInput.checked = active;

      if (formTitle) {
        formTitle.textContent = 'Edit Periode';
      }
    });
  });
});
</script>
@endsection
