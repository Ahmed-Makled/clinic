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

// Notifications Routes
Route::prefix('admin')->middleware(['auth', 'role:Admin'])->group(function () {
    Route::get('/notifications', function () {
        $notifications = auth()->user()->notifications()->latest()->limit(10)->get()->map(function($notification) {
            return [
                'id' => $notification->id,
                'type' => $notification->type,
                'data' => $notification->data,
                'read_at' => $notification->read_at,
                'created_at' => $notification->created_at->diffForHumans(),
                'message' => $notification->data['message'] ?? 'إشعار جديد'
            ];
        });

        return response()->json([
            'notifications' => $notifications
        ]);
    });

    Route::get('/notifications/count', function () {
        return response()->json([
            'count' => auth()->user()->unreadNotifications()->count()
        ]);
    });

    Route::post('/notifications/mark-all-read', function () {
        auth()->user()->unreadNotifications->markAsRead();
        return response()->json(['success' => true]);
    });
});

// Temporary diagnostic route to check roles
Route::get('/check-roles', function () {
    return [
        'roles' => \Spatie\Permission\Models\Role::all()->toArray(),
        'permissions' => \Spatie\Permission\Models\Permission::all()->toArray()
    ];
});
