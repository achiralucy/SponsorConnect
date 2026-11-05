<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sponsorship extends Model
{
    public const STATUS_OPEN = 'open';
    public const STATUS_CLOSED = 'closed';

    protected $fillable = [
        'sponsor_id',
        'judul',
        'deskripsi',
        'benefit',
        'persyaratan',
        'batas_pengajuan',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'batas_pengajuan' => 'date',
        ];
    }

    /** Sponsor pemilik sponsorship ini. */
    public function sponsor(): BelongsTo
    {
        return $this->belongsTo(SponsorProfile::class, 'sponsor_id');
    }

    /** Pengajuan dari panitia untuk sponsorship ini (1:N). */
    public function applications(): HasMany
    {
        return $this->hasMany(SponsorshipApplication::class);
    }

    /**
     * Sponsorship yang benar-benar masih bisa diajukan:
     * status open DAN batas pengajuan belum lewat.
     * Dipakai juga oleh halaman daftar sponsorship milik panitia.
     */
    public function scopeOpen(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_OPEN)
            ->whereDate('batas_pengajuan', '>=', today());
    }

    /** True kalau sponsorship masih menerima pengajuan. */
    public function isOpen(): bool
    {
        return $this->status === self::STATUS_OPEN
            && $this->batas_pengajuan->gte(today());
    }

    /** Label status untuk ditampilkan di UI (membedakan "ditutup" dan "kedaluwarsa"). */
    public function getStatusLabelAttribute(): string
    {
        if ($this->status === self::STATUS_CLOSED) {
            return 'Ditutup';
        }

        return $this->batas_pengajuan->lt(today()) ? 'Kedaluwarsa' : 'Dibuka';
    }

    /**
     * Menutup otomatis semua sponsorship yang batas pengajuannya sudah lewat.
     * Dijalankan terjadwal (routes/console.php) dan tiap sponsor membuka daftarnya.
     *
     * @return int jumlah sponsorship yang ditutup
     */
    public static function closeExpired(): int
    {
        return static::where('status', self::STATUS_OPEN)
            ->whereDate('batas_pengajuan', '<', today())
            ->update(['status' => self::STATUS_CLOSED]);
    }
}