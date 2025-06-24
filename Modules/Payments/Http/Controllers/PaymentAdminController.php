<?php

namespace Modules\Payments\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Payments\Entities\Payment;
use Modules\Appointments\Entities\Appointment;

class PaymentAdminController extends Controller
{
    /**
     * Display a listing of the payment records.
     */
    public function index(Request $request)
    {
        $query = Payment::query()
            ->with(['appointment.doctor', 'appointment.patient.user'])
            ->latest('created_at');

        // Filter by status if provided
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter by payment method if provided
        if ($request->has('payment_method') && $request->payment_method) {
            $query->where('payment_method', $request->payment_method);
        }

        // Filter by date range if provided
        if ($request->has('start_date') && $request->start_date) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->has('end_date') && $request->end_date) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        // Search by transaction ID or appointment patient name
        if ($request->has(key: 'search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('transaction_id', 'like', "%{$search}%")
                  ->orWhereHas('appointment.patient.user', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $payments = $query->paginate(15)->withQueryString();

        // Get payment statistics
        $stats = [
            'total' => Payment::count(),
            'completed' => Payment::where('status', 'completed')->count(),
            'pending' => Payment::where('status', 'pending')->count(),
            'failed' => Payment::where('status', 'failed')->count(),
            'total_amount' => Payment::where('status', 'completed')->sum('amount')
        ];

        return view('payments::admin.index', [
            'payments' => $payments,
            'stats' => $stats
        ]);
    }

    /**
     * Show the specified payment record.
     */
    public function show(Payment $payment)
    {
        $payment->load(['appointment.doctor', 'appointment.patient.user']);

        return view('payments::admin.show', [
            'payment' => $payment
        ]);
    }

    /**
     * Manually mark a payment as completed.
     */
    public function markAsCompleted(Payment $payment)
    {
        $payment->update([
            'status' => 'completed',
            'paid_at' => now()
        ]);

        // Update the appointment reference
        if ($payment->appointment) {
            $payment->appointment->update(['payment_id' => $payment->id]);
        }

        return redirect()->route('admin.payments.show', $payment)
            ->with('success', 'تم تحديث حالة الدفع بنجاح.');
    }

    /**
     * Manually mark a payment as failed.
     */
    public function markAsFailed(Payment $payment)
    {
        $payment->update([
            'status' => 'failed'
        ]);

        return redirect()->route('admin.payments.show', $payment)
            ->with('success', 'تم تحديث حالة الدفع بنجاح.');
    }

    /**
     * Export payments to CSV file.
     */
    public function export(Request $request)
    {
        $query = Payment::with(['appointment.doctor', 'appointment.patient.user'])
            ->latest('created_at');

        // Apply filters
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        if ($request->has('start_date') && $request->start_date) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->has('end_date') && $request->end_date) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $payments = $query->get();

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename=payments.csv',
        ];

        $callback = function() use ($payments) {
            $file = fopen('php://output', 'w');
            // Add UTF-8 BOM
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // Column headers
            fputcsv($file, [
                'معرف الدفع',
                'معرف الحجز',
                'المبلغ',
                'العملة',
                'الحالة',
                'طريقة الدفع',
                'معرف المعاملة',
                'الطبيب',
                'المريض',
                'تاريخ الدفع',
                'تاريخ الإنشاء'
            ]);

            // Data rows
            foreach ($payments as $payment) {
                fputcsv($file, [
                    $payment->id,
                    $payment->appointment_id ?? 'غير متوفر',
                    $payment->amount,
                    $payment->currency,
                    $payment->status,
                    $payment->payment_method,
                    $payment->transaction_id,
                    $payment->appointment->doctor->name ?? 'غير متوفر',
                    $payment->appointment->patient->user->name ?? 'غير متوفر',
                    $payment->paid_at ? $payment->paid_at->format('Y-m-d H:i:s') : 'غير متوفر',
                    $payment->created_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
