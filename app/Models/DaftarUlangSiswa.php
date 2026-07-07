<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class DaftarUlangSiswa extends Model
{
    use HasFactory;

    protected $table = 'daftar_ulang_siswa';

    protected $fillable = [
        'periode_id',
        'nis',
        'nama_lengkap',
        'kelas_asal',
        'kelas_tujuan',
        'jurusan',
    ];

    /**
     * Relationship with Periode (DaftarUlangPeriode).
     */
    public function periode(): BelongsTo
    {
        return $this->belongsTo(DaftarUlangPeriode::class, 'periode_id');
    }

    /**
     * Relationship with Checklist (DaftarUlangChecklist).
     */
    public function checklist(): HasOne
    {
        return $this->hasOne(DaftarUlangChecklist::class, 'siswa_id');
    }
}
