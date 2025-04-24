<?php

use Illuminate\Support\Facades\Route;
use Modules\Admin\Http\Controllers\DoctorController;



Route::prefix('doctors')->middleware(['web', 'auth:web,sanctum', 'role:Administrator'])->group(function () {
    Route::resource('index', DoctorController::class)->names('doctors.index');

});
