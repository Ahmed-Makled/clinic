<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\DoctorController;


Route::get('/', [HomeController::class, 'index'])->name('home');


Route::get('/search', function () {
    return view('search');
})->name('search');


Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
Route::get('/about', [AboutController::class, 'index'])->name('about');

// Temporary diagnostic route to check roles
Route::get('/check-roles', function () {
    return [
        'roles' => \Spatie\Permission\Models\Role::all()->toArray(),
        'permissions' => \Spatie\Permission\Models\Permission::all()->toArray()
    ];
});
