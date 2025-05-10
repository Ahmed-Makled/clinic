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
            // Add fields from multiple migrations in one place
            if (!Schema::hasColumn('doctors', 'title')) {
                $table->string('title')->nullable()->after('phone');
            }

            if (!Schema::hasColumn('doctors', 'gender')) {
                $table->enum('gender', ['ذكر', 'انثي'])->nullable()->after('experience_years');
            }

            if (!Schema::hasColumn('doctors', 'status')) {
                $table->boolean('status')->default(true);
            }

            if (!Schema::hasColumn('doctors', 'description')) {
                $table->text('description')->nullable()->after('bio');
            }

            if (!Schema::hasColumn('doctors', 'rating_avg')) {
                $table->decimal('rating_avg', 3, 2)->default(0);
            }

            if (!Schema::hasColumn('doctors', 'specialization')) {
                $table->string('specialization')->nullable()->after('title');
            }

            if (!Schema::hasColumn('doctors', 'is_profile_completed')) {
                $table->boolean('is_profile_completed')->default(false)->after('status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('doctors', function (Blueprint $table) {
            // Drop all columns that were added in this migration
            $columns = [
                'title',
                'gender',
                'status',
                'description',
                'rating_avg',
                'specialization',
                'is_profile_completed'
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('doctors', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
