<?php

use Illuminate\Support\Facades\Route;
use Modules\Doctors\Http\Controllers\DoctorsController;

Route::prefix('doctors')->group(function () {

    // Admin routes
    Route::middleware(['auth:web', 'role:Admin'])->group(function () {
        Route::get('/', [DoctorsController::class, 'index'])->name('doctors.index');
        Route::get('/create', [DoctorsController::class, 'create'])->name('doctors.create');
        Route::post('/', [DoctorsController::class, 'store'])->name('doctors.store');
        Route::get('/{doctor}/details', [DoctorsController::class, 'details'])->name('doctors.details');
        Route::get('/{doctor}/edit', [DoctorsController::class, 'edit'])->name('doctors.edit');
        Route::put('/{doctor}', [DoctorsController::class, 'update'])->name('doctors.update');
        Route::delete('/{doctor}', [DoctorsController::class, 'destroy'])->name('doctors.destroy');

        // مسارات الأطباء الذين تحتاج بياناتهم للاستكمال
        Route::get('/incomplete-profiles', [DoctorsController::class, 'incompleteProfiles'])->name('doctors.incomplete_profiles');
        Route::get('/update-completion-status', [DoctorsController::class, 'updateProfilesCompletionStatus'])->name('doctors.update_completion_status');
    });

    // Doctor profile routes
    Route::middleware(['auth:web', 'role:Doctor'])->group(function () {
        Route::get('/profile', [DoctorsController::class, 'profile'])->name('doctors.profile');
        Route::put('/profile/update', [DoctorsController::class, 'updateProfile'])->name('doctors.profile.update');
        Route::put('/profile/password', [DoctorsController::class, 'updatePassword'])->name('doctors.password.update');
        Route::get('/profile/appointments', [DoctorsController::class, 'appointments'])->name('doctors.appointments');

        Route::put('/profile/appointments/{appointment}/complete', [DoctorsController::class, 'completeAppointment'])->name('doctors.appointments.complete');
        Route::put('/profile/appointments/{appointment}/cancel', [DoctorsController::class, 'cancelAppointment'])->name('doctors.appointments.cancel');
    });
});

Route::middleware(['web', 'auth:web'])->group(function () {
    Route::get('/search', [DoctorsController::class, 'search'])->name('search');
    Route::post('/doctors/{doctor}/rate', [DoctorsController::class, 'rate'])->name('doctors.rate');
});
