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
use App\Http\Controllers\DonorDatabaseController;

Route::middleware('auth')->group(function () {
    Route::get('/donor/profile', [DonorProfileController::class, 'edit'])->name('donor.profile.edit');
    Route::put('/donor/profile', [DonorProfileController::class, 'update'])->name('donor.profile.update');
    Route::get('/donors', [DonorDatabaseController::class, 'index'])->name('donors.index');
});
