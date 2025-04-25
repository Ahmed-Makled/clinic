<?php

use Illuminate\Support\Facades\Route;
use Modules\Patients\Http\Controllers\PatientController;



Route::middleware(['web', 'auth:web', 'role:Administrator'])->group(function () {
    Route::resource('patients', PatientController::class);
});
