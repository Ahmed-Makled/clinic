<?php

namespace Modules\Appointments\Database\Migrations;

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
        Schema::table('appointments', function (Blueprint $table) {
            $table->index('doctor_id');
            $table->index('patient_id');
            $table->index('scheduled_at');
            $table->index(['status', 'scheduled_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropIndex(['doctor_id']);
            $table->dropIndex(['patient_id']);
            $table->dropIndex(['scheduled_at']);
            $table->dropIndex(['status', 'scheduled_at']);
        });
    }
};
