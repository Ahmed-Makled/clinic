<?php

use Illuminate\Support\Facades\Route;
use Modules\Admin\Http\Controllers\AdminDashboardController;
use Modules\Admin\Http\Controllers\AdminController;
use Modules\Admin\Http\Controllers\DoctorController;
use Modules\Admin\Http\Controllers\PatientController;
use Modules\Admin\Http\Controllers\AppointmentController;
use Modules\Admin\Http\Controllers\SpecialtyController;
use Modules\Admin\Http\Controllers\UserController;

Route::prefix('admin')->middleware(['web', 'auth', 'role:Administrator'])->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::resource('doctors', DoctorController::class);
    Route::resource('patients', PatientController::class);
    Route::resource('appointments', AppointmentController::class);
    Route::resource('specialties', SpecialtyController::class);
    Route::resource('users', UserController::class);
});
