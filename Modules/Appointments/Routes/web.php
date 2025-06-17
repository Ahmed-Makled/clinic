<?php

use Illuminate\Support\Facades\Route;
use Modules\Appointments\Http\Controllers\AppointmentsController;

Route::prefix('appointments')->name('appointments.')->middleware(['web'])->group(function () {
    // Public routes accessible to any authenticated user
    Route::middleware(['auth:web'])->group(function () {
        // Booking routes
        Route::get('/book/{doctor}', [AppointmentsController::class, 'book'])->name('book');
        Route::post('/', [AppointmentsController::class, 'store'])->name('store');
        Route::put('/{appointment}/cancel', [AppointmentsController::class, 'cancel'])->name('cancel');

        Route::get('/{appointment}', [AppointmentsController::class, 'show'])->name('show')->where('appointment', '[0-9]+');
    });

    // Admin only routes
    Route::middleware(['auth:web', 'role:Admin'])->group(function () {
        // List all appointments (index)
        Route::get('/', [AppointmentsController::class, 'index'])->name('index');

        // Admin specific actions - These specific routes must come before parameter routes
        Route::get('/create', [AppointmentsController::class, 'create'])->name('create');
        Route::get('/available-slots', [AppointmentsController::class, 'getAvailableSlots'])->name('available-slots');
        Route::get('/doctor-available-days', [AppointmentsController::class, 'getDoctorAvailableDays'])->name('doctor-available-days');

        // Parameter-based routes
        Route::get('/{appointment}/edit', [AppointmentsController::class, 'edit'])->name('edit');
        Route::put('/{appointment}', [AppointmentsController::class, 'update'])->name('update');
        Route::delete('/{appointment}', [AppointmentsController::class, 'destroy'])->name('destroy');
        Route::put('/{appointment}/complete', [AppointmentsController::class, 'complete'])->name('complete');
    });
});
