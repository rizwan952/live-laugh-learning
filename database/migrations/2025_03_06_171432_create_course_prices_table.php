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
        Schema::create('course_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained('courses');
            $table->integer('duration');
            $table->decimal('single_lesson_price', 8, 2)->nullable();
            $table->decimal('five_lessons_price', 8, 2)->nullable();
            $table->decimal('ten_lessons_price', 8, 2)->nullable();
            $table->decimal('fifteen_lessons_price', 8, 2)->nullable();
            $table->decimal('twenty_lessons_price', 8, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_prices');
    }
};
