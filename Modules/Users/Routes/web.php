<?php

use Illuminate\Support\Facades\Route;
use Modules\Users\Http\Controllers\UsersController;

Route::middleware(['web', 'auth:web', 'role:Administrator'])->group(function () {
    Route::resource('users', UsersController::class);
});
