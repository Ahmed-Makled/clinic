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
        Schema::table('appointments', function (Blueprint $table) {
            $table->decimal('fees', 10, 2)->nullable()->after('notes');
            $table->boolean('is_paid')->default(false)->after('fees');
            $table->boolean('is_important')->default(false)->after('is_paid');
        });

        Schema::table('doctors', function (Blueprint $table) {
            $table->decimal('consultation_fee', 10, 2)->nullable()->after('price');
            $table->integer('experience_years')->nullable()->after('consultation_fee');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn(['fees', 'is_paid', 'is_important']);
        });

        Schema::table('doctors', function (Blueprint $table) {
            $table->dropColumn(['consultation_fee', 'experience_years']);
        });
    }
};
