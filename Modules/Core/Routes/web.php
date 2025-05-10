<?php

/*
|--------------------------------------------------------------------------
| Core Module Routes
|--------------------------------------------------------------------------
|
| These routes are intended for shared functionality across your application.
| They are loaded by the RouteServiceProvider within the Core module.
|
*/

use Illuminate\Support\Facades\Route;
use Modules\Core\Http\Controllers\CoreController;

Route::prefix('core')->group(function() {
    // Core module routes will be defined here when needed
});
