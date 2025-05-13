<?php

namespace Modules\Payments\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Appointments\Entities\Appointment;
use Modules\Payments\Entities\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Exception\ApiErrorException;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class PaymentController extends Controller
{
    public function __construct()
    {
        Stripe::setApiKey(config('stripe.secret'));
    }

    /**
     * Redirect to Stripe payment page
     */
    public function checkout(Appointment $appointment)
    {
        // Prevent double payment
        if ($appointment->payment && $appointment->payment->isCompleted()) {
            return redirect()->route('appointments.show', $appointment)
                ->with('info', 'تم دفع رسوم هذا الحجز مسبقًا.');
        }

        try {
            // Create a pending payment record in database
            $payment = Payment::firstOrCreate(
                ['appointment_id' => $appointment->id],
                [
                    'amount' => $appointment->fees,
                    'currency' => config('stripe.currency'),
                    'status' => 'pending',
                    'payment_method' => 'stripe',
                    'transaction_id' => Payment::generateTransactionId()
                ]
            );

            $checkout_session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [
                    [
                        'price_data' => [
                            'currency' => $payment->currency,
                            'unit_amount' => $payment->amount * 100, // Amount in cents
                            'product_data' => [
                                'name' => 'حجز كشف - ' . $appointment->doctor->name,
                                'description' => 'تاريخ الحجز: ' . $appointment->formatted_date . ' الساعة ' . $appointment->scheduled_at->format('h:i A'),
                            ],
                        ],
                        'quantity' => 1,
                    ]
                ],
                'mode' => 'payment',
                'success_url' => route('payments.stripe.success') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('payments.stripe.cancel') . '?appointment_id=' . $appointment->id,
                'client_reference_id' => $payment->transaction_id,
                'metadata' => [
                    'appointment_id' => $appointment->id,
                    'payment_id' => $payment->id
                ]
            ]);

            // Update payment with Stripe checkout session ID
            $payment->update([
                'payment_id' => $checkout_session->id,
                'metadata' => [
                    'stripe_session_id' => $checkout_session->id,
                    'stripe_session_url' => $checkout_session->url
                ]
            ]);

            return view('payments::checkout', [
                'checkout_session' => $checkout_session,
                'appointment' => $appointment,
                'payment' => $payment
            ]);

        } catch (ApiErrorException $e) {
            Log::error('Stripe error: ' . $e->getMessage());
            return back()->with('error', 'حدث خطأ أثناء إنشاء جلسة الدفع: ' . $e->getMessage());
        }
    }

    // We've removed the success and cancel methods as they're now handled by StripeController

    /**
     * Handle Stripe webhook
     */
    public function handleWebhook(Request $request)
    {
        $payload = $request->getContent();
        $sig_header = $request->header('stripe-signature');
        $endpoint_secret = config('stripe.webhook.secret');

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload, $sig_header, $endpoint_secret
            );
        } catch (\UnexpectedValueException $e) {
            // Invalid payload
            return response()->json(['error' => 'Invalid payload'], 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            // Invalid signature
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        // Handle the event
        switch ($event->type) {
            case 'checkout.session.completed':
                $session = $event->data->object;
                $appointmentId = $session->metadata->appointment_id;
                $paymentId = $session->metadata->payment_id ?? null;

                DB::transaction(function () use ($appointmentId, $paymentId, $session) {
                    // Update the payment record
                    $payment = $paymentId ? Payment::find($paymentId) :
                            Payment::where('appointment_id', $appointmentId)->first();

                    if ($payment) {
                        $payment->update([
                            'status' => 'completed',
                            'payment_id' => $session->payment_intent,
                            'paid_at' => now(),
                            'metadata' => array_merge((array) $payment->metadata, [
                                'stripe_payment_intent' => $session->payment_intent,
                                'stripe_payment_status' => $session->payment_status,
                            ])
                        ]);

                        // Update the appointment with payment reference
                        $appointment = Appointment::find($appointmentId);
                        if ($appointment) {
                            $appointment->update(['payment_id' => $payment->id]);
                            Log::info('Payment completed for appointment #' . $appointmentId);
                        }
                    }
                });
                break;
            default:
                Log::info('Unhandled event type: ' . $event->type);
        }

        return response()->json(['success' => true]);
    }
}
