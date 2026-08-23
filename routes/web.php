<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

use App\Http\Controllers\DonorProfileController;

Route::middleware('auth')->group(function () {
    Route::get('/donor/profile', [DonorProfileController::class, 'edit'])->name('donor.profile.edit');
    Route::put('/donor/profile', [DonorProfileController::class, 'update'])->name('donor.profile.update');
});

// PB04 — Recipient Profile
use App\Http\Controllers\RecipientProfileController;

Route::middleware('auth')->group(function () {
    Route::get('/recipient/profile', [RecipientProfileController::class, 'edit'])->name('recipient.profile.edit');
    Route::put('/recipient/profile', [RecipientProfileController::class, 'update'])->name('recipient.profile.update');
});

