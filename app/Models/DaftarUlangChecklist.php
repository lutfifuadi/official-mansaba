<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DaftarUlangChecklist extends Model
{
    use HasFactory;

    protected $table = 'daftar_ulang_checklist';

    protected $fillable = [
        'siswa_id',
        'raport',
        'kartu_keluarga',
        'akte_kelahiran',
        'ijazah',
        'status',
        'verified_by',
        'verified_at',
    ];

    protected $appends = [
        'skor_kelengkapan',
        'nama_kelompok',
        'kurang_item',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'raport' => 'boolean',
            'kartu_keluarga' => 'boolean',
            'akte_kelahiran' => 'boolean',
            'ijazah' => 'boolean',
            'verified_at' => 'datetime',
        ];
    }

    /**
     * Relationship with Siswa (DaftarUlangSiswa).
     */
    public function siswa(): BelongsTo
    {
        return $this->belongsTo(DaftarUlangSiswa::class, 'siswa_id');
    }

    /**
     * Get the checklist completion score (0-4).
     */
    public function getSkorKelengkapanAttribute(): int
    {
        $skor = 0;
        if ($this->raport) $skor++;
        if ($this->kartu_keluarga) $skor++;
        if ($this->akte_kelahiran) $skor++;
        if ($this->ijazah) $skor++;
        return $skor;
    }

    /**
     * Get the group name based on completeness.
     */
    public function getNamaKelompokAttribute(): string
    {
        $skor = $this->skor_kelengkapan;
        switch ($skor) {
            case 4:
                return 'Lengkap';
            case 3:
                return 'Hampir Lengkap';
            case 2:
                return 'Setengah Lengkap';
            case 1:
                return 'Baru Memulai';
            default:
                return 'Belum Kumpul';
        }
    }

    /**
     * Get missing items.
     */
    public function getKurangItemAttribute(): array
    {
        $kurang = [];
        if (!$this->raport) $kurang[] = 'Raport';
        if (!$this->kartu_keluarga) $kurang[] = 'Kartu Keluarga';
        if (!$this->akte_kelahiran) $kurang[] = 'Akte Kelahiran';
        if (!$this->ijazah) $kurang[] = 'Ijazah';
        return $kurang;
    }

    /**
     * Relationship with Verifier (User).
     */
    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    /**
     * Model boot function to handle events.
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            $isComplete = $model->raport 
                && $model->kartu_keluarga 
                && $model->akte_kelahiran 
                && $model->ijazah;

            $model->status = $isComplete ? 'lengkap' : 'belum_lengkap';
        });
    }
}
