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
            // Add fields from multiple migrations in one place
            if (!Schema::hasColumn('appointments', 'fees')) {
                $table->decimal('fees', 10, 2)->nullable()->after('notes');
            }

            if (!Schema::hasColumn('appointments', 'is_important')) {
                $table->boolean('is_important')->default(false)->after('fees');
            }

            if (!Schema::hasColumn('appointments', 'waiting_time')) {
                $table->integer('waiting_time')->nullable()->after('fees')->comment('Waiting time in minutes');
            }

            if (!Schema::hasColumn('appointments', 'payment_id')) {
                $table->string('payment_id')->nullable()->after('waiting_time');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            // Drop all columns that were added in this migration
            $columns = [
                'fees',
                'is_important',
                'waiting_time',
                'payment_id'
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('appointments', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
