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
            // إضافة حقل التخصص الواحد
            $table->foreignId('category_id')->nullable()->after('city_id')->constrained('categories');

            // نقل البيانات من الجدول الوسيط إلى الحقل الجديد (سيتم تنفيذه يدوياً بعد الترحيل)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('doctors', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn('category_id');
        });
    }
};
