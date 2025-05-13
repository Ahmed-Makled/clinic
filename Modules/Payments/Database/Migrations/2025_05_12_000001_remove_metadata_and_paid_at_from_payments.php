<?php

namespace Modules\Payments\Database\Migrations;

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
        Schema::table('payments', function (Blueprint $table) {
            if (Schema::hasColumn('payments', 'metadata')) {
                $table->dropColumn('metadata');
            }
            if (Schema::hasColumn('payments', 'paid_at')) {
                $table->dropColumn('paid_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            if (!Schema::hasColumn('payments', 'metadata')) {
                $table->json('metadata')->nullable()->comment('Additional payment information');
            }
            if (!Schema::hasColumn('payments', 'paid_at')) {
                $table->timestamp('paid_at')->nullable();
            }
        });
    }
};
