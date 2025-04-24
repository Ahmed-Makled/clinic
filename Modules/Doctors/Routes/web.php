<?php

use Illuminate\Support\Facades\Route;
use Modules\Doctors\Http\Controllers\DoctorsController;

// Public route for viewing doctors
Route::get('doctors', [DoctorsController::class, 'index'])->name('doctors.index');

// Admin routes for managing doctors
Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('doctors', DoctorsController::class)->except(['index'])->names('doctors');
});
