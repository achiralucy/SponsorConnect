<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'status_verifikasi',
        'alasan_mendaftar',
        'catatan_verifikasi',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function sponsorProfile(): HasOne
    {
        return $this->hasOne(SponsorProfile::class);
    }

    public function panitiaProfile(): HasOne
    {
        return $this->hasOne(PanitiaProfile::class);
    }
}
