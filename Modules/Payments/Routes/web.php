<?php

use Illuminate\Support\Facades\Route;
use Modules\Payments\Http\Controllers\PaymentController;
use Modules\Payments\Http\Controllers\PaymentAdminController;

// Payment routes that require authentication
Route::middleware(['web', 'auth:web'])->group(function () {
    // Checkout route
    Route::get('/payments/checkout/{appointment}', [PaymentController::class, 'checkout'])->name('payments.checkout');
});

// Webhook route (no auth needed as it's called by payment provider)
Route::post('/payment/webhook', [PaymentController::class, 'handleWebhook'])->name('payment.webhook');

Route::middleware(['auth'])->group(function () {
    // Regular payment routes - using the main checkout route from above instead of duplicating
    Route::post('/process-payment/{appointment}', [PaymentController::class, 'processPayment'])->name('payments.process');

    // Payment routes
    Route::get('/payment/checkout/{appointment}', [PaymentController::class, 'checkout'])->name('payment.checkout');
    Route::post('/payment/create-session/{appointment}', [PaymentController::class, 'createSession'])->name('payments.payment.create-session');
    // Removed duplicate webhook route that was causing conflict
    Route::get('/payment/success', [PaymentController::class, 'success'])->name('payments.payment.success');
    Route::get('/payment/cancel', [PaymentController::class, 'cancel'])->name('payments.payment.cancel');
});

// Route for direct payment booking during appointment creation
Route::post('/appointments/payment/create', [PaymentController::class, 'createAppointmentAndCheckout'])
    ->middleware(['auth'])
    ->name('appointments.payment.create');

// Admin payment management routes
Route::prefix('admin')->middleware(['web', 'auth', 'role:Admin'])->group(function () {
    // Payment management routes
    Route::get('/payments', [PaymentAdminController::class, 'index'])->name('admin.payments.index');
    Route::get('/payments/export', [PaymentAdminController::class, 'export'])->name('admin.payments.export');
    Route::get('/payments/{payment}', [PaymentAdminController::class, 'show'])->name('admin.payments.show');
    Route::patch('/payments/{payment}/mark-completed', [PaymentAdminController::class, 'markAsCompleted'])->name('admin.payments.mark-completed');
    Route::patch('/payments/{payment}/mark-failed', [PaymentAdminController::class, 'markAsFailed'])->name('admin.payments.mark-failed');
});
