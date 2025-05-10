<?php

namespace Modules\Payments\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Appointments\Entities\Appointment;
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
        if ($appointment->is_paid) {
            return redirect()->route('appointments.show', $appointment)
                ->with('info', 'تم دفع رسوم هذا الحجز مسبقًا.');
        }

        try {
            $checkout_session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [
                    [
                        'price_data' => [
                            'currency' => config('stripe.currency'),
                            'unit_amount' => $appointment->fees * 100, // Amount in cents
                            'product_data' => [
                                'name' => 'حجز كشف - ' . $appointment->doctor->name,
                                'description' => 'تاريخ الحجز: ' . $appointment->formatted_date . ' الساعة ' . $appointment->scheduled_at->format('h:i A'),
                            ],
                        ],
                        'quantity' => 1,
                    ]
                ],
                'mode' => 'payment',
                'success_url' => route('payments.success', ['appointment' => $appointment->id]),
                'cancel_url' => route('payments.cancel', ['appointment' => $appointment->id]),
                'client_reference_id' => $appointment->id,
                'metadata' => [
                    'appointment_id' => $appointment->id
                ]
            ]);

            return view('payments::checkout', [
                'checkout_session' => $checkout_session,
                'appointment' => $appointment
            ]);

        } catch (ApiErrorException $e) {
            Log::error('Stripe error: ' . $e->getMessage());
            return back()->with('error', 'حدث خطأ أثناء إنشاء جلسة الدفع: ' . $e->getMessage());
        }
    }

    /**
     * Handle successful payment
     */
    public function success(Request $request, Appointment $appointment)
    {
        $appointment->update(['is_paid' => true]);

        return view('payments::success', [
            'appointment' => $appointment
        ]);
    }

    /**
     * Handle cancelled payment
     */
    public function cancel(Request $request, Appointment $appointment)
    {
        return view('payments::cancel', [
            'appointment' => $appointment
        ]);
    }

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

                if ($appointmentId) {
                    $appointment = Appointment::find($appointmentId);
                    if ($appointment) {
                        $appointment->update(['is_paid' => true]);
                        Log::info('Payment completed for appointment #' . $appointmentId);
                    }
                }
                break;
            default:
                Log::info('Unhandled event type: ' . $event->type);
        }

        return response()->json(['success' => true]);
    }
}
