<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $bloodRequests = \App\Models\BloodRequest::with('user')->latest()->get();
    return view('dashboard', compact('bloodRequests'));
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

use App\Http\Controllers\DonorSearchController;

Route::get('/donors/search', [DonorSearchController::class, 'search'])
    ->name('donors.search');

// F07 — Create Blood Request (recipients only)
use App\Http\Controllers\BloodRequestController;

Route::middleware('auth')->group(function () {
    Route::get('/blood-requests/create', [BloodRequestController::class, 'create'])->name('blood-requests.create');
    Route::post('/blood-requests', [BloodRequestController::class, 'store'])->name('blood-requests.store');
});

use App\Http\Controllers\DonorDatabaseController;

Route::middleware('auth')->group(function () {
    Route::get('/donors', [DonorDatabaseController::class, 'index'])->name('donors.index');
});

// F12 — Donor Request Notifications
use App\Http\Controllers\NotificationController;

Route::middleware('auth')->group(function () {
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.mark-as-read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
});

// F13 — Accept/Reject Donation Request
use App\Http\Controllers\DonationResponseController;

Route::middleware('auth')->group(function () {
    Route::get('/blood-requests/{id}', [DonationResponseController::class, 'show'])->name('blood-requests.show');
    Route::post('/blood-requests/{id}/accept', [DonationResponseController::class, 'accept'])->name('blood-requests.accept');
    Route::post('/blood-requests/{id}/reject', [DonationResponseController::class, 'reject'])->name('blood-requests.reject');
});



// F10 � Donation History
use App\Http\Controllers\DonationHistoryController;

Route::middleware('auth')->group(function () {
    Route::get('/donation-history', [DonationHistoryController::class, 'index'])->name('donation-history.index');
});

