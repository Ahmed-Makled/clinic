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
        // Drop old tables if they exist
        Schema::dropIfExists('doctor_category');
        Schema::dropIfExists('doctors');

        // Create the consolidated doctors table with all necessary fields
        Schema::create('doctors', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index('user_id');

            // Name fields
            $table->string('name')->nullable();

            // Main fields
            $table->string('image')->nullable();
            $table->text('description')->nullable();
            $table->string('degree')->nullable();
            $table->string('title')->nullable();
            $table->string('specialization')->nullable();
            $table->string('address')->nullable();

            // Foreign keys for location
            $table->foreignId('governorate_id')->nullable()->constrained('governorates')->nullOnDelete();
            $table->foreignId('city_id')->nullable()->constrained('cities')->nullOnDelete();

            // Professional details
            $table->decimal('consultation_fee', 10, 2)->nullable();
            $table->integer('experience_years')->nullable();
            $table->enum('gender', ['ذكر', 'انثي'])->nullable();

            // Status and ratings
            $table->boolean('status')->default(true);
            $table->integer('waiting_time')->default(15)->comment('Average waiting time in minutes');
            $table->decimal('rating_avg', 3, 2)->default(0);
            $table->boolean('is_profile_completed')->default(false);

            $table->timestamps();
        });

        // Create the doctor_category pivot table
        Schema::create('doctor_category', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doctor_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctor_category');
        Schema::dropIfExists('doctors');
    }
};
