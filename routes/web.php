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

// PB04 - Recipient Profile
use App\Http\Controllers\RecipientProfileController;

Route::middleware('auth')->group(function () {
    Route::get('/recipient/profile', [RecipientProfileController::class, 'edit'])->name('recipient.profile.edit');
    Route::put('/recipient/profile', [RecipientProfileController::class, 'update'])->name('recipient.profile.update');
});

use App\Http\Controllers\DonorSearchController;

Route::get('/donors/search', [DonorSearchController::class, 'search'])
    ->name('donors.search');

// F07 - Create Blood Request (recipients only)
use App\Http\Controllers\BloodRequestController;

Route::middleware('auth')->group(function () {
    Route::get('/blood-requests/create', [BloodRequestController::class, 'create'])->name('blood-requests.create');
    Route::post('/blood-requests', [BloodRequestController::class, 'store'])->name('blood-requests.store');
});

use App\Http\Controllers\DonorDatabaseController;

Route::middleware('auth')->group(function () {
    Route::get('/donors', [DonorDatabaseController::class, 'index'])->name('donors.index');
});

// F12 - Donor Request Notifications
use App\Http\Controllers\NotificationController;

Route::middleware('auth')->group(function () {
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.mark-as-read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
});

// F13 - Accept/Reject Donation Request
use App\Http\Controllers\DonationResponseController;

Route::middleware('auth')->group(function () {
    Route::get('/blood-requests/{id}', [DonationResponseController::class, 'show'])->name('blood-requests.show');
    Route::post('/blood-requests/{id}/accept', [DonationResponseController::class, 'accept'])->name('blood-requests.accept');
    Route::post('/blood-requests/{id}/reject', [DonationResponseController::class, 'reject'])->name('blood-requests.reject');
});

// F10 - Donation History
use App\Http\Controllers\DonationHistoryController;

Route::middleware('auth')->group(function () {
    Route::get('/donation-history', [DonationHistoryController::class, 'index'])->name('donation-history.index');
});

// ============================================================
// Sprint 4 - Hospital Routes (F16, F17, F18)
// Protected by 'role:hospital' middleware
// ============================================================
Route::middleware(['auth', 'role:hospital'])->prefix('hospital')->name('hospital.')->group(function () {
    // F16 - Hospital Request Management
    Route::get('/requests', [\App\Http\Controllers\Hospital\HospitalRequestController::class, 'index'])->name('requests.index');
    Route::get('/requests/{id}', [\App\Http\Controllers\Hospital\HospitalRequestController::class, 'show'])->name('requests.show');
    Route::post('/requests/{id}/assign', [\App\Http\Controllers\Hospital\HospitalRequestController::class, 'assign'])->name('requests.assign');
    Route::post('/requests/{id}/unassign', [\App\Http\Controllers\Hospital\HospitalRequestController::class, 'unassign'])->name('requests.unassign');
    Route::patch('/requests/{id}/status', [\App\Http\Controllers\Hospital\HospitalRequestController::class, 'updateStatus'])->name('requests.status');

    // F17 - Confirm Completed Donation
    // Route::post('/donations/{id}/confirm', [DonationConfirmationController::class, 'confirm'])->name('donations.confirm');

    // F18 - Blood Donation Campaign Management
    // Route::resource('campaigns', CampaignController::class);
});

// ============================================================
// Sprint 4 - Admin Routes (F19, F20)
// Protected by 'role:admin' middleware
// ============================================================
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\ReportController;

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // F19 - Admin Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::post('/hospitals/{id}/verify', [AdminDashboardController::class, 'verifyHospital'])->name('hospitals.verify');
    Route::post('/hospitals/{id}/reject', [AdminDashboardController::class, 'rejectHospital'])->name('hospitals.reject');

    // F20 - Reports & Blood-Group Statistics
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
});