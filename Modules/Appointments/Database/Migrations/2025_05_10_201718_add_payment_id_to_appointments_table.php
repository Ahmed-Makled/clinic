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
        // First, check if the is_paid column exists and drop it if it does
        if (Schema::hasColumn('appointments', 'is_paid')) {
            Schema::table('appointments', function (Blueprint $table) {
                $table->dropColumn(['is_paid', 'payment_method', 'payment_id']);
            });
        }

        // Then add the foreign key to the payments table
        Schema::table('appointments', function (Blueprint $table) {
            $table->foreignId('payment_id')->nullable()->constrained('payments')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropForeign(['payment_id']);
            $table->dropColumn('payment_id');

            // Restore the original columns
            $table->boolean('is_paid')->default(false);
            $table->string('payment_method')->nullable();
            $table->string('payment_id')->nullable();
        });
    }
};
