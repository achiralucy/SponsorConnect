<?php

use App\Http\Controllers\Panitia\ApplicationController;
use App\Http\Controllers\Panitia\ProfileController;
use App\Http\Controllers\Panitia\SponsorshipBrowseController;
use Illuminate\Support\Facades\Route;

Route::get('/sponsorships', [SponsorshipBrowseController::class, 'index'])->name('sponsorships.index');
Route::get('/sponsorships/{id}', [SponsorshipBrowseController::class, 'show'])->name('sponsorships.show');

Route::get('/sponsorships/{id}/ajukan', [ApplicationController::class, 'create'])->name('sponsorships.apply');
Route::post('/sponsorships/{id}/ajukan', [ApplicationController::class, 'store'])->name('sponsorships.apply.store');

Route::get('/pengajuan', [ApplicationController::class, 'index'])->name('applications.index');
Route::get('/pengajuan/{id}', [ApplicationController::class, 'show'])->name('applications.show');
Route::get('/pengajuan/{id}/edit', [ApplicationController::class, 'edit'])->name('applications.edit');
Route::put('/pengajuan/{id}', [ApplicationController::class, 'update'])->name('applications.update');
Route::delete('/pengajuan/{id}', [ApplicationController::class, 'destroy'])->name('applications.destroy');

Route::get('/profil', [ProfileController::class, 'show'])->name('profile');
Route::put('/profil', [ProfileController::class, 'update'])->name('profile.update');