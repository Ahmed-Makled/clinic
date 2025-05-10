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
        // Drop old tables if they exist
        Schema::dropIfExists('doctor_ratings');

        // Create the consolidated doctor_ratings table with all necessary fields
        Schema::create('doctor_ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doctor_id')->constrained()->onDelete('cascade');
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->foreignId('appointment_id')->nullable()->constrained()->onDelete('cascade');
            $table->decimal('rating', 2, 1); // تقييم من 5 نجوم
            $table->text('comment')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->timestamps();
            
            // Add unique constraint to ensure one rating per appointment
            $table->unique(['patient_id', 'appointment_id'], 'unique_patient_appointment_rating');
            
            // Add index for faster lookups
            $table->index('appointment_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctor_ratings');
    }
};