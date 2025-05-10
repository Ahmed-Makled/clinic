<?php

namespace Modules\Doctors\Database\Migrations;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('doctors', function (Blueprint $table) {
            // Drop old columns
            $table->dropColumn(['governorate', 'city']);

            // Add new foreign key columns
            $table->foreignId('governorate_id')->nullable()->constrained('governorates')->nullOnDelete();
            $table->foreignId('city_id')->nullable()->constrained('cities')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('doctors', function (Blueprint $table) {
            // Remove foreign key constraints
            $table->dropForeign(['governorate_id']);
            $table->dropForeign(['city_id']);

            // Drop new columns
            $table->dropColumn(['governorate_id', 'city_id']);

            // Restore old columns
            $table->string('governorate')->nullable();
            $table->string('city')->nullable();
        });
    }
};
