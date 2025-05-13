<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Modules\Appointments\Entities\Appointment;
use Modules\Payments\Entities\Payment;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Sync is_paid values based on the payment relationship
        $appointments = DB::table('appointments')
            ->leftJoin('payments', 'appointments.payment_id', '=', 'payments.id')
            ->select(
                'appointments.id',
                'appointments.is_paid',
                'payments.status as payment_status'
            )
            ->get();

        foreach ($appointments as $appointment) {
            $isPaid = $appointment->payment_status === 'completed';

            // Only update if the value is different
            if ($appointment->is_paid != $isPaid) {
                DB::table('appointments')
                    ->where('id', $appointment->id)
                    ->update(['is_paid' => $isPaid]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No need for down method as this is a data reconciliation
    }
};
