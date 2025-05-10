<?php

/*
|--------------------------------------------------------------------------
| Contacts Module Routes
|--------------------------------------------------------------------------
*/

use Illuminate\Support\Facades\Route;
use Modules\Contacts\Http\Controllers\ContactsController;

Route::prefix('contact')->group(function() {
    Route::get('/', [ContactsController::class, 'index'])->name('contact');
    Route::post('/', [ContactsController::class, 'store'])->name('contact.store');

    // مسارات إدارة الاتصال
    Route::middleware(['web', 'auth:web', 'role:Admin'])->prefix('admin')->group(function() {
        Route::get('/list', [ContactsController::class, 'adminIndex'])->name('admin.contacts.index');
    });
});
