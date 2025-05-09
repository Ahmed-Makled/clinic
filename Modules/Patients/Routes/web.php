<?php

use Illuminate\Support\Facades\Route;
use Modules\Patients\Http\Controllers\PatientController;
use Modules\Patients\Http\Controllers\PatientProfileController;






Route::prefix('patients')->group(function () {
   // مسارات تتطلب تسجيل الدخول فقط (للمرضى)
Route::middleware(['web', 'auth:web'])->group(function () {
    Route::get('/profile', [PatientProfileController::class, 'profile'])->name('profile');
    Route::post('/profile/store', [PatientProfileController::class, 'storeProfile'])->name('profile.store');
    Route::put('/profile/update', [PatientProfileController::class, 'updateProfile'])->name('profile.update');
    Route::post('/profile/password/update', [PatientProfileController::class, 'updatePassword'])->name('profile.password.update');
});

// مسارات للإدمن فقط
Route::middleware(['web', 'auth:web', 'role:Admin'])->group(function () {
    Route::get('', [PatientController::class, 'index'])->name('patients.index');
    Route::get('/create', [PatientController::class, 'create'])->name('patients.create');
    Route::post('', [PatientController::class, 'store'])->name('patients.store');
    Route::get('/{patient}/details', [PatientController::class, 'details'])->name('patients.details');
    Route::get('/{patient}/edit', [PatientController::class, 'edit'])->name('patients.edit');
    Route::put('/{patient}', [PatientController::class, 'update'])->name('patients.update');
    Route::delete('/{patient}', [PatientController::class, 'destroy'])->name('patients.destroy');
});
});
