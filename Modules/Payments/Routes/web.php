<?php

use Illuminate\Support\Facades\Route;
use Modules\Payments\Http\Controllers\PaymentController;
use Modules\Payments\Http\Controllers\PaymentsController;
use Modules\Payments\Http\Controllers\StripeController;

// Payment routes that require authentication
Route::middleware(['web', 'auth:web'])->group(function () {
    // Checkout and payment result routes
    Route::get('/payments/checkout/{appointment}', [PaymentController::class, 'checkout'])->name('payments.checkout');
    Route::get('/payments/success/{appointment}', [PaymentController::class, 'success'])->name('payments.success');
    Route::get('/payments/cancel/{appointment}', [PaymentController::class, 'cancel'])->name('payments.cancel');
});

// Webhook route (no auth needed as it's called by Stripe)
Route::post('/stripe/webhook', [PaymentController::class, 'handleWebhook'])->name('stripe.webhook');

Route::middleware(['auth'])->group(function () {
    // Regular payment routes
    Route::get('/checkout/{appointment}', [PaymentsController::class, 'checkout'])->name('payments.checkout');
    Route::post('/process-payment/{appointment}', [PaymentsController::class, 'processPayment'])->name('payments.process');
    
    // Stripe payment routes
    Route::get('/stripe/checkout/{appointment}', [StripeController::class, 'checkout'])->name('payments.stripe.checkout');
    Route::post('/stripe/create-session/{appointment}', [StripeController::class, 'createSession'])->name('payments.stripe.create-session');
    Route::post('/stripe/webhook', [StripeController::class, 'handleWebhook'])->name('payments.stripe.webhook');
    Route::get('/stripe/success', [StripeController::class, 'success'])->name('payments.stripe.success');
    Route::get('/stripe/cancel', [StripeController::class, 'cancel'])->name('payments.stripe.cancel');
});

// Route for direct stripe booking during appointment creation
Route::post('/appointments/stripe/create', [StripeController::class, 'createAppointmentAndCheckout'])
    ->middleware(['auth'])
    ->name('appointments.stripe.create');
