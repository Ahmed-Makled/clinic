<?php

use Illuminate\Support\Facades\Route;
use Modules\Admin\Http\Controllers\AdminDashboardController;
use Modules\Admin\Http\Controllers\PatientController;
use Modules\Admin\Http\Controllers\AppointmentController;
use Modules\Admin\Http\Controllers\SpecialtyController;
use Modules\Admin\Http\Controllers\UserController;
use Modules\Doctors\Http\Controllers\DoctorController;

Route::prefix('admin')->middleware(['web', 'auth:web,sanctum', 'role:Administrator'])->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::resource('patients', PatientController::class)->names('admin.patients');
    Route::resource('appointments', AppointmentController::class)->names('admin.appointments');
    Route::resource('specialties', SpecialtyController::class)->names('admin.specialties');
    Route::resource('users', UserController::class)->names('admin.users');
});
