<?php

use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\CategoryController;

Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/about', [PageController::class, 'about'])->name('about');

// Profile routes
Route::middleware(['auth:web'])->group(function () {
    Route::get('/profile', [PageController::class, 'profile'])->name('profile');
});

Route::get('/search', function () {
    return view('search');
})->name('search');

Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

Route::get('/categories/{category}', [CategoryController::class, 'show'])->name('categories.show');

// Temporary diagnostic route to check roles
Route::get('/check-roles', function () {
    return [
        'roles' => \Spatie\Permission\Models\Role::all()->toArray(),
        'permissions' => \Spatie\Permission\Models\Permission::all()->toArray()
    ];
});
