<?php

use Illuminate\Support\Facades\Route;
use Modules\Appointments\Http\Controllers\AppointmentsController;

// Public routes that require authentication
Route::middleware(['web', 'auth:web'])->group(function () {
    Route::get('/appointments/book/{doctor}', [AppointmentsController::class, 'book'])->name('appointments.book');
    Route::post('/appointments', [AppointmentsController::class, 'store'])->name('appointments.store');
});

// Admin routes
Route::middleware(['web', 'auth:web', 'role:Administrator'])->group(function () {
    Route::resource('appointments', AppointmentsController::class)->except(['store']);
});
