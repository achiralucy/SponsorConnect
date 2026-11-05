<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');
// Tutup otomatis sponsorship yang batas pengajuannya sudah lewat (Achira).
// Dijalankan tiap hari lewat `php artisan schedule:run`; selain itu daftar
// sponsorship milik sponsor juga memanggil Sponsorship::closeExpired() saat dibuka.
Schedule::call(fn () => \App\Models\Sponsorship::closeExpired())
    ->daily()
    ->name('tutup-sponsorship-kedaluwarsa');