<?php

use Illuminate\Support\Facades\Route;
use Modules\Appointments\Http\Controllers\AppointmentsController;

Route::middleware(['web'])->group(function () {
    Route::resource('appointments', AppointmentsController::class);
});
