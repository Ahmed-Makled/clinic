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
        // Check if columns already exist before attempting to add them
        Schema::table('doctors', function (Blueprint $table) {
            // Skip this migration as these columns are already added in the consolidated doctors table
            $hasConsultationFee = $this->columnExists('doctors', 'consultation_fee');
            $hasExperienceYears = $this->columnExists('doctors', 'experience_years');

            if (!$hasConsultationFee) {
                $table->decimal('consultation_fee', 10, 2)->nullable();
            }

            if (!$hasExperienceYears) {
                if (!$hasConsultationFee) {
                    $table->integer('experience_years')->nullable()->after('consultation_fee');
                } else {
                    $table->integer('experience_years')->nullable();
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // We won't remove these columns during rollback since they're essential to the schema
        // and are managed by the consolidated migration
    }

    /**
     * Check if a column exists in a table
     */
    protected function columnExists($table, $column)
    {
        return DB::getSchemaBuilder()->hasColumn($table, $column);
    }
};
