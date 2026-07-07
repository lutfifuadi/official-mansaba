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
