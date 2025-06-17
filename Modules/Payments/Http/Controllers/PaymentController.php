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
     * Show the payment checkout page for an appointment
     */
    public function checkout(Appointment $appointment)
    {
        // Eager load the doctor relationship
        $appointment->load(['doctor', 'patient', 'payment']);


        return view('payments::.checkout', compact('appointment'));
    }

    /**
     * Create a payment checkout session for an appointment
     */
    public function createSession(Request $request, Appointment $appointment)
    {
        try {
            // Eager load the doctor relationship if not already loaded
            if (!$appointment->relationLoaded('doctor')) {
                $appointment->load('doctor');
            }

            // Check if the doctor exists
            if (!$appointment->doctor) {
                Log::error('Doctor not found for appointment #' . $appointment->id);
                return back()->with('error', 'لم يتم العثور على معلومات الطبيب. يرجى المحاولة مرة أخرى لاحقاً.');
            }

            $doctorName = $appointment->doctor->name ?? 'غير معروف';

            $checkout_session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [
                    [
                        'price_data' => [
                            'currency' => config('stripe.currency', 'egp'),
                            'product_data' => [
                                'name' => 'رسوم حجز الطبيب ' . $doctorName,
                                'description' => 'حجز يوم ' . $appointment->scheduled_at->format('Y-m-d') . ' الساعة ' . $appointment->scheduled_at->format('h:i A'),
                            ],
                            'unit_amount' => $appointment->fees * 100, // Payment amount in cents
                        ],
                        'quantity' => 1,
                    ]
                ],
                'metadata' => [
                    'appointment_id' => $appointment->id
                ],
                'mode' => 'payment',
                'success_url' => route('payments.payment.success') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('payments.payment.cancel') . '?appointment_id=' . $appointment->id,
            ]);

            return redirect($checkout_session->url);
        } catch (ApiErrorException $e) {
            return back()->with('error', 'حدث خطأ أثناء إنشاء جلسة الدفع. يرجى المحاولة مرة أخرى.');
        }
    }

    /**
     * Create an appointment and redirect to payment checkout
     */
    public function createAppointmentAndCheckout(Request $request)
    {
        // Validate appointment data
        $validated = $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required',
            'notes' => 'nullable|string|max:500',
        ]);

        // Get the authenticated user's patient record
        $patient = auth()->user()->patient;

        if (!$patient) {
            return redirect()->back()->with('error', 'يجب إكمال ملف المريض الخاص بك أولاً');
        }

        // Get the doctor and verify it exists
        $doctor = Doctor::find($validated['doctor_id']);
        if (!$doctor) {
            return redirect()->back()->with('error', 'لم يتم العثور على بيانات الطبيب المطلوب');
        }

        // Convert appointment_date and appointment_time to a single datetime
        $scheduledAt = \Carbon\Carbon::parse($validated['appointment_date'] . ' ' . $validated['appointment_time']);

        // Create new appointment
        $appointment = new Appointment();
        $appointment->doctor_id = $validated['doctor_id'];
        $appointment->patient_id = $patient->id;
        $appointment->scheduled_at = $scheduledAt;
        $appointment->notes = $validated['notes'] ?? null;
        $appointment->status = 'scheduled';
        $appointment->fees = $doctor->consultation_fee;

        $appointment->save();

        // Explicitly load the doctor relationship to avoid null reference
        $appointment->load('doctor');

        // Create payment session and redirect
        try {
            $doctorName = $doctor->name ?? 'غير معروف';

            $checkout_session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [
                    [
                        'price_data' => [
                            'currency' => config('stripe.currency', 'egp'),
                            'product_data' => [
                                'name' => 'رسوم حجز الطبيب ' . $doctorName,
                                'description' => 'حجز يوم ' . $appointment->scheduled_at->format('Y-m-d') . ' الساعة ' . $appointment->scheduled_at->format('h:i A'),
                            ],
                            'unit_amount' => $appointment->fees * 100, // Payment amount in cents
                        ],
                        'quantity' => 1,
                    ]
                ],
                'metadata' => [
                    'appointment_id' => $appointment->id
                ],
                'mode' => 'payment',
                'success_url' => route('payments.payment.success') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('payments.payment.cancel') . '?appointment_id=' . $appointment->id,
            ]);

            return redirect($checkout_session->url);
        } catch (ApiErrorException $e) {
            return back()->with('error', 'حدث خطأ أثناء إنشاء جلسة الدفع. يرجى المحاولة مرة أخرى.');
        } catch (\Exception $e) {
            return back()->with('error', 'حدث خطأ غير متوقع. يرجى المحاولة مرة أخرى لاحقاً.');
        }
    }

    /**
     * Handle successful payment
     */
    public function success(Request $request)
    {
        try {
            // Verify session_id is present
            if (!$request->has('session_id')) {
                return redirect('/appointments')
                    ->with('error', 'بيانات الدفع غير مكتملة. يرجى التحقق من حالة الحجز الخاص بك.');
            }

            $sessionId = $request->get('session_id');

            // Retrieve session data from Stripe
            $session = Session::retrieve($sessionId);

            // Check if session contains appointment ID in metadata
            if (!isset($session->metadata->appointment_id)) {
                return redirect('/appointments')
                    ->with('warning', 'لم نتمكن من تحديد الحجز المرتبط بعملية الدفع. يرجى التحقق من حالة حجوزاتك.');
            }

            $appointmentId = $session->metadata->appointment_id;

            // Find appointment
            $appointment = Appointment::find($appointmentId);

            if (!$appointment) {
                return redirect('/appointments')
                    ->with('error', 'لم نتمكن من العثور على الحجز المرتبط بعملية الدفع.');
            }

            // Always create a new payment record for online payments (no caching)
            $payment = Payment::create([
                'appointment_id' => $appointment->id,
                'amount' => $appointment->fees,
                'currency' => config('stripe.currency', 'egp'),
                'status' => 'pending',
                'payment_method' => 'stripe',
                'transaction_id' => Payment::generateTransactionId()
            ]);

            // Update payment status to completed
            $payment->update([
                'status' => 'completed',
                'payment_id' => $session->id
            ]);

            // The payment connection is tracked from the Payment model


            // Redirect to success page with appointment
            return view('payments::success', ['appointment' => $appointment]);

        } catch (\Exception $e) {
            // Log detailed error information
            return redirect('/appointments')
                ->with('error', 'حدث خطأ في معالجة نتيجة الدفع الخاصة بك. يرجى الاتصال بالدعم.');
        }
    }

    /**
     * Handle cancelled payment
     */
    public function cancel(Request $request)
    {
        $appointmentId = $request->get('appointment_id');
        if ($appointmentId) {
            $appointment = Appointment::find($appointmentId);
            if ($appointment) {
                // Update or create a payment record
                $payment = Payment::create([
                    'appointment_id' => $appointment->id,
                    [
                        'amount' => $appointment->fees,
                        'currency' => config('stripe.currency', 'egp'),
                        'status' => 'pending',
                        'payment_method' => 'stripe',
                        'transaction_id' => Payment::generateTransactionId()
                    ]]
                    );

                // Update payment status to failed
                $payment->update([
                    'status' => 'failed',
                ]);

                // Return the cancel view directly
                return view('payments::cancel', ['appointment' => $appointment]);
            }
        }

        return redirect()->route('dashboard.index')
            ->with('warning', 'تم إلغاء عملية الدفع.');
    }

    /**
     * Handle payment webhooks
     */
    public function handleWebhook(Request $request)
    {
        $payload = $request->getContent();
        $sig_header = $request->header('Payment-Signature');
        $endpoint_secret = config('stripe.webhook.secret');

        try {
            if ($endpoint_secret) {
                $event = Webhook::constructEvent(
                    $payload,
                    $sig_header,
                    $endpoint_secret
                );
            } else {
                // For testing only - without signature verification
                $event = json_decode($payload, true);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }

        // Handle the event
        switch ($event->type) {
            case 'checkout.session.completed':
                $session = $event->data->object;

                if (isset($session->metadata->appointment_id)) {
                    $appointment = Appointment::find($session->metadata->appointment_id);
                    if ($appointment) {
                        // Always create a new payment record for online payments (no caching)
                        $payment = Payment::create([
                            'appointment_id' => $appointment->id,
                            'amount' => $appointment->fees,
                            'currency' => config('stripe.currency', 'egp'),
                            'status' => 'pending',
                            'payment_method' => 'stripe',
                            'transaction_id' => Payment::generateTransactionId()
                        ]);

                        // Update payment status to completed
                        $payment->update([
                            'status' => 'completed',
                            'payment_id' => $session->id
                        ]);

                        // We're not updating the appointment since we've removed payment fields
                        // The payment connection is tracked from the Payment model

                    }
                }
                break;
            default:
        }

        return response()->json(['status' => 'success']);
    }


}
