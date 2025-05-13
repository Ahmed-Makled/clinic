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
            if (!Schema::hasColumn('appointments', 'is_paid')) {
                $table->boolean('is_paid')->default(false)->after('fees');
            }
            
            if (!Schema::hasColumn('appointments', 'payment_method')) {
                $table->string('payment_method')->nullable()->after('is_paid');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            if (Schema::hasColumn('appointments', 'is_paid')) {
                $table->dropColumn('is_paid');
            }
            
            if (Schema::hasColumn('appointments', 'payment_method')) {
                $table->dropColumn('payment_method');
            }
        });
    }
};
