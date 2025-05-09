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
            // إضافة عمود طريقة الدفع إذا لم يكن موجوداً بالفعل
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
            // إزالة العمود عند التراجع
            if (Schema::hasColumn('appointments', 'payment_method')) {
                $table->dropColumn('payment_method');
            }
        });
    }
};
