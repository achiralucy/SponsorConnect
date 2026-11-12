<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class SponsorProfile extends Model
{
    protected $fillable = [
        'user_id',
        'nama_perusahaan',
        'bidang_usaha',
        'deskripsi',
        'kontak',
        'alamat',
        'logo',
    ];

    /** Akun pemilik profil ini (1:1). */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** Semua sponsorship yang dibuka oleh sponsor ini (1:N). */
    public function sponsorships(): HasMany
    {
        return $this->hasMany(Sponsorship::class, 'sponsor_id');
    }

    public function getLogoUrlAttribute(): ?string
    {
        if (! $this->logo) {
            return null;
        }

        return '/storage/' . ltrim($this->logo, '/') . '?v=' . ($this->updated_at?->timestamp ?? 0);
    }
}