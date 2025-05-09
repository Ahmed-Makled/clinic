<?php

use Illuminate\Support\Facades\Route;
use Modules\Appointments\Http\Controllers\AppointmentsController;

// Public routes that require authentication
Route::middleware(['web', 'auth:web'])->group(function () {
    Route::get('/appointments/book/{doctor}', [AppointmentsController::class, 'book'])->name('appointments.book');
    Route::post('/appointments', [AppointmentsController::class, 'store'])->name('appointments.store');
    Route::get('/appointments/{appointment}', [AppointmentsController::class, 'show'])->name('appointments.show');
    Route::put('/appointments/{appointment}/cancel', [AppointmentsController::class, 'cancel'])->name('appointments.cancel');
    Route::post('/appointments/{appointment}/confirm-cash', [AppointmentsController::class, 'confirmCashPayment'])->name('appointments.confirm-cash');
});

// Admin routes
Route::middleware(['web', 'auth:web', 'role:Admin'])->group(function () {
    Route::resource('appointments', AppointmentsController::class)->except(['store', 'show']);
    Route::put('/appointments/{appointment}/complete', [AppointmentsController::class, 'complete'])->name('appointments.complete');
    Route::put('/appointments/{appointment}/mark-as-paid', [AppointmentsController::class, 'markAsPaid'])->name('appointments.mark-as-paid');
    Route::put('/appointments/{appointment}/mark-as-unpaid', [AppointmentsController::class, 'markAsUnpaid'])->name('appointments.mark-as-unpaid');
});
