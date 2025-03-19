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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users');
            $table->foreignId('course_id')->constrained('courses');
            $table->string('course_name');
            $table->decimal('course_price',8,2);
            $table->decimal('tax_percent',8,2)->nullable();
            $table->decimal('total_tax',8,2)->nullable();
            $table->decimal('final_amount',8,2);
            $table->enum('status',['pending','in_progress','completed','cancelled'])->default('pending');
            $table->string('payment_method')->nullable();
            $table->string('payment_id')->nullable();
            $table->enum('payment_status',['pending','processing','completed','failed'])->default('pending');
            $table->json('payment_details')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
