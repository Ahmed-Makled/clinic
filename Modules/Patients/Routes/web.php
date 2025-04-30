<?php

use Illuminate\Support\Facades\Route;
use Modules\Patients\Http\Controllers\PatientController;

Route::middleware(['web', 'auth:web', 'role:Admin'])->group(function () {
    Route::resource('patients', PatientController::class);

    // مسارات إضافة بيانات مريض من مستخدم موجود
    Route::get('/patients/create-from-user', [PatientController::class, 'createFromUser'])->name('patients.createFromUser');
    Route::post('/patients/store-from-user', [PatientController::class, 'storeFromUser'])->name('patients.storeFromUser');
});
