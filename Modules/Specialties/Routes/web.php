<?php

use Illuminate\Support\Facades\Route;
use Modules\Specialties\Http\Controllers\SpecialtyController;

Route::middleware(['web', 'auth:web', 'role:Admin'])->group(function () {
    Route::resource('specialties', SpecialtyController::class);
});
