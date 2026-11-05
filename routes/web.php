<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SponsorProfileController;
use App\Http\Controllers\SponsorshipController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::middleware('verified_account')->group(function () {
        Route::get('/dashboard', function () {
            // Sponsor langsung diarahkan ke daftar sponsorship miliknya.
            if (auth()->user()->role === 'sponsor') {
                return redirect()->route('sponsor.sponsorships.index');
            }

            return view('dashboard');
        })->name('dashboard');

        Route::middleware('role:sponsor')->group(function () {
            Route::get('/sponsor/profile', [SponsorProfileController::class, 'show'])->name('sponsor.profile');
            Route::put('/sponsor/profile', [SponsorProfileController::class, 'update'])->name('sponsor.profile.update');

            // CRUD Sponsorship milik sponsor (Achira)
            Route::resource('sponsor/sponsorships', SponsorshipController::class)
                ->names('sponsor.sponsorships');
            Route::patch('/sponsor/sponsorships/{sponsorship}/close', [SponsorshipController::class, 'close'])
                ->name('sponsor.sponsorships.close');
        });
    });
});