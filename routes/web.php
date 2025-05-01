<?php

use App\Http\Controllers\PageController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\GovernorateController;
use Modules\Doctors\Http\Controllers\DoctorsController;
use Illuminate\Support\Facades\Route;

// Main routes
Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/search', [PageController::class, 'search'])->name('search');

// Contact routes
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

// Categories routes
Route::get('/categories/{category}', [CategoryController::class, 'show'])->name('categories.show');

// Dynamic data loading routes
Route::get('/governorates/{governorate}/cities', [GovernorateController::class, 'getCities'])->name('cities.by.governorate');
Route::get('/governorates/{governorate}/cities', [PageController::class, 'getCities'])->name('governorates.cities');
Route::get('/doctors/filter', [DoctorsController::class, 'filter'])->name('doctors.filter');

// Profile routes
Route::middleware(['auth:web'])->group(function () {
    Route::get('/profile', [PageController::class, 'profile'])->name('profile');
});
