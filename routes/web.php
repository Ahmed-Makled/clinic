<?php

use App\Http\Controllers\PageController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\GovernorateController;
use Modules\Doctors\Http\Controllers\DoctorsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NotificationsController;

// Main routes
Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/search', [PageController::class, 'search'])->name('search');

// Contact routes
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

// Categories routes

// Dynamic data loading routes
Route::get('/governorates/{governorate}/cities', [GovernorateController::class, 'getCities'])->name('cities.by.governorate');
Route::get('/governorates/{governorate}/cities', [PageController::class, 'getCities'])->name('governorates.cities');
Route::get('/doctors/filter', [DoctorsController::class, 'filter'])->name('doctors.filter');

// Notification routes - تم نقل مسارات الملف الشخصي إلى Modules/Patients/Routes/web.php
Route::middleware(['auth:web'])->group(function () {
    // Notification routes
    Route::get('/admin/notifications', [NotificationsController::class, 'index']);
    Route::get('/admin/notifications/count', [NotificationsController::class, 'count']);
    Route::post('/admin/notifications/{id}/mark-as-read', [NotificationsController::class, 'markAsRead']);
    Route::post('/admin/notifications/mark-all-read', [NotificationsController::class, 'markAllAsRead']);
});
