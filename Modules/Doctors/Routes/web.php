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

        // مسارات إضافة بيانات طبيب من مستخدم موجود
        Route::get('/create-from-user', [DoctorsController::class, 'createFromUser'])->name('doctors.createFromUser');
        Route::post('/store-from-user', [DoctorsController::class, 'storeFromUser'])->name('doctors.storeFromUser');
    });
});
