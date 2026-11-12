<?php

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AdminVerificationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SponsorProfileController;
use App\Http\Controllers\SponsorApplicationController;
use App\Http\Controllers\SponsorshipController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingController;

// Landing page publik
Route::get('/', [LandingController::class, 'index'])->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/status-verifikasi', [AuthController::class, 'verificationStatus'])
        ->name('verification.status');

    Route::middleware('verified_account')->group(function () {
        Route::get('/dashboard', function () {
            if (auth()->user()->role === 'sponsor') {
                return redirect()->route('sponsor.sponsorships.index');
            }

            if (auth()->user()->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }

            return view('dashboard');
        })->name('dashboard');

        Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
            Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
            Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
            Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');

            Route::get('/verifications', [AdminVerificationController::class, 'index'])
                ->name('verifications.index');

            Route::post('/verifications/{user}/approve', [AdminVerificationController::class, 'approve'])
                ->name('verifications.approve');

            Route::post('/verifications/{user}/reject', [AdminVerificationController::class, 'reject'])
                ->name('verifications.reject');
        });

        Route::middleware('role:sponsor')->group(function () {
            Route::get('/sponsor/pengajuan', [SponsorApplicationController::class, 'index'])
                ->name('sponsor.applications.index');

            Route::get('/sponsor/pengajuan/{id}', [SponsorApplicationController::class, 'show'])
                ->name('sponsor.applications.show');

            Route::patch('/sponsor/pengajuan/{id}/review', [SponsorApplicationController::class, 'review'])
                ->name('sponsor.applications.review');

            Route::get('/sponsor/profile', [SponsorProfileController::class, 'show'])
                ->name('sponsor.profile');

            Route::put('/sponsor/profile', [SponsorProfileController::class, 'update'])
                ->name('sponsor.profile.update');

            Route::resource('sponsor/sponsorships', SponsorshipController::class)
                ->names('sponsor.sponsorships');

            Route::patch('/sponsor/sponsorships/{sponsorship}/close', [SponsorshipController::class, 'close'])
                ->name('sponsor.sponsorships.close');
        });
    });
});

Route::middleware(['auth', 'verified_account', 'role:panitia'])
    ->prefix('panitia')
    ->name('panitia.')
    ->group(base_path('routes/panitia.php'));