<?php

use Illuminate\Support\Facades\Route;
use Modules\Users\Http\Controllers\UsersController;

Route::middleware(['web', 'auth:web', 'role:Admin'])->group(function () {
    Route::resource('users', UsersController::class);
    Route::get('users/{user}/details', [UsersController::class, 'show'])->name('users.details');
    Route::patch('users/{user}/toggle-status', [UsersController::class, 'toggleStatus'])->name('users.toggle-status');
});
