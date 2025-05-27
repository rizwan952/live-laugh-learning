<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('order_package_lessons', function (Blueprint $table) {
            $table->string('zoom_meeting_id')->nullable()->after('time_zone');
            $table->text('zoom_start_url')->nullable()->after('zoom_meeting_id');
            $table->text('zoom_join_url')->nullable()->after('zoom_start_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_package_lessons', function (Blueprint $table) {
            $table->dropColumn(['zoom_meeting_id', 'zoom_start_url', 'zoom_join_url']);
        });
    }
};
