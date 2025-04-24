<?php

use Illuminate\Support\Facades\Route;
use Modules\Doctors\Http\Controllers\DoctorController;

// Public route for viewing doctors
Route::get('doctors', [DoctorController::class, 'index'])->name('doctors.index');

// Admin routes for managing doctors
Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('doctors', DoctorController::class)->except(['index'])->names('doctors');
});
