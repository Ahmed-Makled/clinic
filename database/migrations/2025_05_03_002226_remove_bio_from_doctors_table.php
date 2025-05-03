<?php

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
        // نقل البيانات من عمود bio إلى description إذا كان description فارغاً
        $doctors = DB::table('doctors')->whereNull('description')->whereNotNull('bio')->get();
        foreach ($doctors as $doctor) {
            DB::table('doctors')
                ->where('id', $doctor->id)
                ->update(['description' => $doctor->bio]);
        }

        Schema::table('doctors', function (Blueprint $table) {
            $table->dropColumn('bio');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('doctors', function (Blueprint $table) {
            $table->text('bio')->nullable()->after('name');
        });
    }
};
