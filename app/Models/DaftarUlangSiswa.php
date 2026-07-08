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

    /**
     * Scope: Filter students based on their checklist completeness group name.
     * Allowed groups: 'lengkap', 'hampir_lengkap', 'setengah_lengkap', 'baru_memulai', 'belum_kumpul'
     *
     * Rules:
     * - 'lengkap': score is 4
     * - 'hampir_lengkap': score is 3
     * - 'setengah_lengkap': score is 2
     * - 'baru_memulai': score is 1
     * - 'belum_kumpul': score is 0
     */
    public function scopeWhereKelompokKelengkapan($query, $kelompok)
    {
        $targetScore = match (strtolower(str_replace('-', '_', $kelompok))) {
            'lengkap' => 4,
            'hampir_lengkap' => 3,
            'setengah_lengkap' => 2,
            'baru_memulai' => 1,
            'belum_kumpul', 'belum_mengumpulkan' => 0,
            default => null,
        };

        if ($targetScore === null) {
            return $query;
        }

        return $query->whereHas('checklist', function ($q) use ($targetScore) {
            $q->whereRaw('(raport + kartu_keluarga + akte_kelahiran + ijazah) = ?', [$targetScore]);
        });
    }

    /**
     * Scope: Filter students who are missing a specific document.
     * Allowed docs: 'raport', 'kartu_keluarga', 'akte_kelahiran', 'ijazah'
     */
    public function scopeWhereKurangBerkas($query, $berkas)
    {
        $allowedDocs = ['raport', 'kartu_keluarga', 'akte_kelahiran', 'ijazah'];
        $berkasNormalized = strtolower(str_replace(' ', '_', $berkas));

        if (!in_array($berkasNormalized, $allowedDocs)) {
            return $query;
        }

        return $query->whereHas('checklist', function ($q) use ($berkasNormalized) {
            $q->where($berkasNormalized, false);
        });
    }
}
