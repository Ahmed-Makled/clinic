<?php

namespace Modules\Doctors\Database\Migrations;

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
        // Only drop unused fields that we confirmed are not needed
        Schema::table('doctors', function (Blueprint $table) {
            if (Schema::hasColumn('doctors', 'bio')) {
                $table->dropColumn('bio');
            }
            
            if (Schema::hasColumn('doctors', 'price')) {
                $table->dropColumn('price');
            }
            
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
            // Restore removed columns if needed
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
