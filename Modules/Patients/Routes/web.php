<?php

use Illuminate\Support\Facades\Route;
use Modules\Patients\Http\Controllers\PatientController;

Route::middleware(['web', 'auth:web', 'role:Admin'])->group(function () {
    Route::get('/patients', [PatientController::class, 'index'])->name('patients.index');
    Route::get('/patients/create', [PatientController::class, 'create'])->name('patients.create');
    Route::post('/patients', [PatientController::class, 'store'])->name('patients.store');
    Route::get('/patients/{patient}/details', [PatientController::class, 'details'])->name('patients.details');
    Route::get('/patients/{patient}/edit', [PatientController::class, 'edit'])->name('patients.edit');
    Route::put('/patients/{patient}', [PatientController::class, 'update'])->name('patients.update');
    Route::delete('/patients/{patient}', [PatientController::class, 'destroy'])->name('patients.destroy');

    // مسارات إضافة بيانات مريض من مستخدم موجود
    Route::get('/patients/create-from-user', [PatientController::class, 'createFromUser'])->name('patients.createFromUser');
    Route::post('/patients/store-from-user', [PatientController::class, 'storeFromUser'])->name('patients.storeFromUser');
});
