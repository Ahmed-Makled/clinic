<?php

namespace Modules\Patients\Database\Migrations;

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
        Schema::table('patients', function (Blueprint $table) {
            // Add fields from multiple migrations in one place
            if (!Schema::hasColumn('patients', 'address')) {
                $table->text('address')->nullable()->after('gender');
            }

            if (!Schema::hasColumn('patients', 'status')) {
                $table->boolean('status')->default(true)->after('address');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            // Drop columns in reverse order
            $columns = ['status', 'address'];

            foreach ($columns as $column) {
                if (Schema::hasColumn('patients', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
