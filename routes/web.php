<?php

use App\Http\Controllers\PageController;
use Modules\Doctors\Http\Controllers\DoctorsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NotificationsController;

// Main routes
Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/search', [PageController::class, 'search'])->name('search');

// Dynamic data loading routes
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
