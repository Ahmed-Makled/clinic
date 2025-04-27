<?php

use Illuminate\Support\Facades\Route;
use Modules\Doctors\Http\Controllers\DoctorsController;

Route::prefix('doctors')->group(function () {
    // Public routes
    Route::get('profiles', [DoctorsController::class, 'profiles'])->name('doctors.profiles');

    // Admin routes
    Route::middleware(['auth:web', 'role:Admin'])->group(function () {
        Route::get('/', [DoctorsController::class, 'index'])->name('doctors.index');
        Route::get('/create', [DoctorsController::class, 'create'])->name('doctors.create');
        Route::post('/', [DoctorsController::class, 'store'])->name('doctors.store');
        Route::get('/{doctor}/details', [DoctorsController::class, 'details'])->name('doctors.details');
        Route::get('/{doctor}/edit', [DoctorsController::class, 'edit'])->name('doctors.edit');
        Route::put('/{doctor}', [DoctorsController::class, 'update'])->name('doctors.update');
        Route::delete('/{doctor}', [DoctorsController::class, 'destroy'])->name('doctors.destroy');
    });

    // This should be last to avoid conflicting with other routes
    Route::get('/{doctor}', [DoctorsController::class, 'show'])->name('doctors.show');
});
