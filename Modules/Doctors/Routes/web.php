<?php

use Illuminate\Support\Facades\Route;
use Modules\Doctors\Http\Controllers\DoctorController;

Route::middleware(['web', 'auth:web', 'role:Administrator'])->group(function () {
    Route::resource('doctors', DoctorController::class);
});
