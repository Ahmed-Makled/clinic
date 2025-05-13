<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Create a database trigger to sync is_paid when payment status changes
        DB::unprepared('
            CREATE TRIGGER IF NOT EXISTS update_appointment_is_paid_after_payment_update
            AFTER UPDATE ON payments
            FOR EACH ROW
            BEGIN
                IF NEW.status != OLD.status THEN
                    UPDATE appointments
                    SET is_paid = (NEW.status = "completed")
                    WHERE payment_id = NEW.id;
                END IF;
            END
        ');

        // Create a trigger to sync is_paid when a new payment is linked to an appointment
        DB::unprepared('
            CREATE TRIGGER IF NOT EXISTS update_appointment_is_paid_after_payment_insert
            AFTER INSERT ON payments
            FOR EACH ROW
            BEGIN
                UPDATE appointments
                SET is_paid = (NEW.status = "completed")
                WHERE id = NEW.appointment_id;
            END
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop the triggers
        DB::unprepared('DROP TRIGGER IF EXISTS update_appointment_is_paid_after_payment_update');
        DB::unprepared('DROP TRIGGER IF EXISTS update_appointment_is_paid_after_payment_insert');
    }
};
