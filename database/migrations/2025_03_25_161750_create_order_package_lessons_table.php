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
        Schema::create('order_package_lessons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders');
            $table->foreignId('order_package_id')->constrained('order_packages');
            $table->string('status')->default('pending')->index();
            $table->decimal('amount',8,2);
            # pending, processing, completed, cancelled, refund_initiated,
            # refund_processing, refunded, refund_cancelled, refund_failed
            $table->timestamp('start_at')->nullable();
            $table->timestamp('end_at')->nullable();
            $table->string('time_zone')->nullable();
            // Refund fields
            $table->decimal('refundable_amount_percentage',8,2)->nullable();
            $table->decimal('refundable_amount',8,2)->nullable();
            $table->string('refund_method')->nullable(); // e.g., 'stripe', 'paypal'
            $table->string('refund_id')->nullable(); // Stripe refund ID or similar
            $table->json('refund_details')->nullable(); // Additional refund metadata
            $table->text('refund_reason')->nullable(); // reason for refund
            $table->timestamp('refund_initiated_at')->nullable(); // Optional: when refund completed
            $table->timestamp('refunded_at')->nullable(); // Optional: when refund completed
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_package_lessons');
    }
};
