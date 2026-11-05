<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SponsorshipApplication extends Model
{
    public const STATUS_PENDING = 'pending';
    public const STATUS_ACCEPTED = 'diterima';
    public const STATUS_REJECTED = 'ditolak';

    protected $fillable = [
        'sponsorship_id',
        'panitia_id',
        'nama_event',
        'deskripsi_event',
        'tanggal_event',
        'lokasi_event',
        'proposal',
        'pesan',
        'status',
        'catatan_sponsor',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_event' => 'date',
        ];
    }

    /** Sponsorship yang diajukan. */
    public function sponsorship(): BelongsTo
    {
        return $this->belongsTo(Sponsorship::class);
    }

    /** Panitia yang mengajukan. */
    public function panitia(): BelongsTo
    {
        return $this->belongsTo(PanitiaProfile::class, 'panitia_id');
    }
}