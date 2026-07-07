@extends('layouts/contentNavbarLayout')

@section('title', 'Tambah Siswa Baru')

@section('content')
<div class="row justify-content-center">
  <div class="col-md-8 col-12">
    <div class="card">
      <div class="card-header border-bottom d-flex align-items-center">
        <a href="{{ route('admin.daftar-ulang-siswa.index') }}" class="btn btn-sm btn-icon btn-text-secondary rounded-pill me-2">
          <i class="icon-base ti tabler-arrow-left"></i>
        </a>
        <h5 class="card-title mb-0">Tambah Siswa Baru</h5>
      </div>
      <div class="card-body pt-6">
        <form action="{{ route('admin.daftar-ulang-siswa.store') }}" method="POST">
          @csrf

          <!-- NIS -->
          <div class="mb-4">
            <label for="nis" class="form-label">NIS (Nomor Induk Siswa)</label>
            <input type="text" class="form-control @error('nis') is-invalid @enderror" id="nis" name="nis" placeholder="Masukkan 8-10 digit NIS" value="{{ old('nis') }}" required>
            @error('nis')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <!-- Nama Lengkap -->
          <div class="mb-4">
            <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
            <input type="text" class="form-control @error('nama_lengkap') is-invalid @enderror" id="nama_lengkap" name="nama_lengkap" placeholder="Masukkan nama lengkap siswa sesuai akte" value="{{ old('nama_lengkap') }}" required>
            @error('nama_lengkap')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="row">
            <!-- Kelas Asal -->
            <div class="col-md-6 mb-4">
              <label for="kelas_asal" class="form-label">Kelas Asal</label>
              <select class="form-select @error('kelas_asal') is-invalid @enderror" id="kelas_asal" name="kelas_asal" required>
                <option value="">Pilih Kelas Asal</option>
                <option value="X" {{ old('kelas_asal') == 'X' ? 'selected' : '' }}>Kelas X</option>
                <option value="XI" {{ old('kelas_asal') == 'XI' ? 'selected' : '' }}>Kelas XI</option>
              </select>
              @error('kelas_asal')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <!-- Kelas Tujuan -->
            <div class="col-md-6 mb-4">
              <label for="kelas_tujuan" class="form-label">Kelas Tujuan</label>
              <select class="form-select @error('kelas_tujuan') is-invalid @enderror" id="kelas_tujuan" name="kelas_tujuan" required>
                <option value="">Pilih Kelas Tujuan</option>
                <option value="XI" {{ old('kelas_tujuan') == 'XI' ? 'selected' : '' }}>Kelas XI (Target)</option>
                <option value="XII" {{ old('kelas_tujuan') == 'XII' ? 'selected' : '' }}>Kelas XII (Target)</option>
              </select>
              @error('kelas_tujuan')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
          </div>

          <!-- Jurusan -->
          <div class="mb-4">
            <label for="jurusan" class="form-label">Jurusan</label>
            <input type="text" class="form-control @error('jurusan') is-invalid @enderror" id="jurusan" name="jurusan" placeholder="Contoh: IPA, IPS, Keagamaan" value="{{ old('jurusan') }}" required>
            @error('jurusan')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <!-- Pilih Periode Aktif -->
          <div class="mb-6">
            <label for="periode_id" class="form-label">Pilih Periode Aktif</label>
            <select class="form-select @error('periode_id') is-invalid @enderror" id="periode_id" name="periode_id" required>
              <option value="">Pilih Periode Aktif</option>
              @foreach($periodes as $periode)
                <option value="{{ $periode->id }}" {{ old('periode_id') == $periode->id ? 'selected' : '' }}>
                  {{ $periode->tahun_ajaran }} ({{ $periode->kelas_target }}) - Aktif
                </option>
              @endforeach
            </select>
            @error('periode_id')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <!-- Action Buttons -->
          <div class="d-flex justify-content-end gap-3 border-top pt-4">
            <a href="{{ route('admin.daftar-ulang-siswa.index') }}" class="btn btn-label-secondary">Batal</a>
            <button type="submit" class="btn btn-primary">Simpan Data Siswa</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
