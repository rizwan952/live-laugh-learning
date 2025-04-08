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
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conversation_id')->constrained('conversations')->index('conversation_id'); // Links to conversation
            $table->foreignId('sender_id')->constrained('users')->index('sender_id'); // Who sent the message
            $table->text('content'); // Message content
            $table->string('message_type', 50)->default('text'); // Support for future types
            $table->boolean('is_read')->default(false)->index(); // Read status
            $table->boolean('is_delivered')->default(false); // Delivery status
            $table->boolean('is_deleted')->default(false); // Message-level soft delete
            $table->timestamp('delivered_at')->nullable(); // When delivered
            $table->timestamp('read_at')->nullable(); // When read
            $table->softDeletes(); // deleted_at for soft deletes
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
