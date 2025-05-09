<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('doctor_ratings', function (Blueprint $table) {
            // إضافة قيد فريد على حقلي المريض والحجز
            // يضمن هذا القيد أن كل حجز يمكن تقييمه مرة واحدة فقط من قبل المريض
            $table->unique(['patient_id', 'appointment_id'], 'unique_patient_appointment_rating');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('doctor_ratings', function (Blueprint $table) {
            // حذف القيد الفريد في حالة التراجع عن الهجرة
            $table->dropUnique('unique_patient_appointment_rating');
        });
    }
};
