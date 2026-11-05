<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PanitiaProfile extends Model
{
    protected $fillable = [
        'user_id',
        'nama_organisasi',
        'nama_penanggung_jawab',
        'kontak',
        'alamat',
        'deskripsi',
    ];

    /** Akun pemilik profil ini (1:1). */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** Semua pengajuan sponsorship yang dikirim panitia ini (1:N). */
    public function applications(): HasMany
    {
        return $this->hasMany(SponsorshipApplication::class, 'panitia_id');
    }
}