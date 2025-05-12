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
        Schema::dropIfExists('doctor_category');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recreate the doctor_category table if needed to rollback
        if (!Schema::hasTable('doctor_category')) {
            Schema::create('doctor_category', function (Blueprint $table) {
                $table->id();
                $table->foreignId('doctor_id')->constrained()->onDelete('cascade');
                $table->foreignId('category_id')->constrained()->onDelete('cascade');
                $table->timestamps();
            });
        }
    }
};
