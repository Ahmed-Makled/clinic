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
        Schema::create('doctors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('avatar')->nullable();
            $table->string('governorate')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('degree')->nullable();
            $table->decimal('price', 8, 2)->nullable();
            $table->decimal('rating', 3, 1)->nullable();
            $table->integer('waiting_time')->nullable();
            $table->foreignId('category_id')->nullable()->constrained()->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctors');
    }
};
