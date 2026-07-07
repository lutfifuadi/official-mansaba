<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class DaftarUlangPeriode extends Model
{
    use HasFactory;

    protected $table = 'daftar_ulang_periode';

    protected $fillable = [
        'tahun_ajaran',
        'kelas_target',
        'tanggal_buka',
        'tanggal_tutup',
        'is_active',
        'created_by',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tanggal_buka' => 'date',
            'tanggal_tutup' => 'date',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Relationship with Creator (User).
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Relationship with Siswa (DaftarUlangSiswa).
     */
    public function siswa(): HasMany
    {
        return $this->hasMany(DaftarUlangSiswa::class, 'periode_id');
    }

    /**
     * Scope a query to only include active periods.
     */
    public function scopeActive(Builder $query): Builder
    {
        $today = Carbon::today()->toDateString();
        return $query->where('is_active', true)
            ->where('tanggal_buka', '<=', $today)
            ->where('tanggal_tutup', '>=', $today);
    }
}
