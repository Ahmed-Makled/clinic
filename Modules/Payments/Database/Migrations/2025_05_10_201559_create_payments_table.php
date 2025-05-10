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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('appointment_id')->nullable()->constrained()->onDelete('set null');
            $table->decimal('amount', 10, 2);
            $table->string('currency', 10)->default('EGP');
            $table->enum('status', ['pending', 'completed', 'failed', 'refunded'])->default('pending');
            $table->string('payment_method')->default('stripe');
            $table->string('payment_id')->nullable()->comment('Payment gateway reference ID');
            $table->string('transaction_id')->nullable()->unique()->comment('Internal transaction reference');
            $table->json('metadata')->nullable()->comment('Additional payment information');
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
