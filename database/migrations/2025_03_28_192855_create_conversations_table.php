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
        Schema::create('conversations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user1_id')->constrained('users'); // First participant
            $table->foreignId('user2_id')->constrained('users'); // Second participant
            $table->timestamp('last_message_at')->nullable()->index(); // Timestamp of last message
            // Indexes
            $table->unique(['user1_id', 'user2_id']); // Prevent duplicate conversations
            $table->index(['user1_id', 'last_message_at']); // For user-specific conversation lists
            $table->index(['user2_id', 'last_message_at']);
            $table->softDeletes(); // deleted_at for soft deletes
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conversations');
    }
};
