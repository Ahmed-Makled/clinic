<?php

use Illuminate\Support\Facades\Route;
use Modules\Doctors\Http\Controllers\DoctorsController;

// Public routes
Route::get('/doctors/profiles', [DoctorsController::class, 'profiles'])->name('doctors.profiles');
Route::get('/doctors/{doctor}', [DoctorsController::class, 'show'])->name('doctors.show');

// Admin routes
Route::middleware(['web', 'auth:web', 'role:Admin'])->group(function () {
    Route::resource('doctors', DoctorsController::class)->except(['show']);
});
