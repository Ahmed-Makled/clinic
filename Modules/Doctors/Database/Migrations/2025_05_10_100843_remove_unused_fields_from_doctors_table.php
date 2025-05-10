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
        Schema::table('doctors', function (Blueprint $table) {
            // Check if bio column exists and remove it (data should have been migrated to description)
            if (Schema::hasColumn('doctors', 'bio')) {
                $table->dropColumn('bio');
            }

            // Check if price column exists and remove it (should be using consultation_fee instead)
            if (Schema::hasColumn('doctors', 'price')) {
                $table->dropColumn('price');
            }

            // Check if rating column exists - should be using rating_avg instead
            if (Schema::hasColumn('doctors', 'rating')) {
                $table->dropColumn('rating');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('doctors', function (Blueprint $table) {
            // Add back removed columns if needed
            if (!Schema::hasColumn('doctors', 'bio')) {
                $table->text('bio')->nullable()->after('name');
            }

            if (!Schema::hasColumn('doctors', 'price')) {
                $table->decimal('price', 10, 2)->nullable()->after('degree');
            }

            if (!Schema::hasColumn('doctors', 'rating')) {
                $table->decimal('rating', 3, 1)->nullable()->after('price');
            }
        });
    }
};
