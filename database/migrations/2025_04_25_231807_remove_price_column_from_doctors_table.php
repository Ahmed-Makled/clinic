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
        Schema::table('doctors', function (Blueprint $table) {
            if (Schema::hasColumn('doctors', 'price')) {
                // First copy price values to consultation_fee if they exist
                DB::statement('UPDATE doctors SET consultation_fee = price WHERE price IS NOT NULL AND consultation_fee IS NULL');

                // Then remove the price column
                $table->dropColumn('price');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('doctors', function (Blueprint $table) {
            if (!Schema::hasColumn('doctors', 'price')) {
                $table->decimal('price', 10, 2)->nullable()->after('title');

                // Copy consultation_fee values back to price if consultation_fee exists
                if (Schema::hasColumn('doctors', 'consultation_fee')) {
                    DB::statement('UPDATE doctors SET price = consultation_fee WHERE consultation_fee IS NOT NULL');
                }
            }
        });
    }
};
