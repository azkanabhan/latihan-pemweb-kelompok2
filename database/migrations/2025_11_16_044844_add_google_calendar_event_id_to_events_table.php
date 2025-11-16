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
        // Logika Anda ditaruh DI DALAM method 'up' yang sudah ada
        Schema::table('events', function (Blueprint $table) {
            $table->string('google_calendar_event_id')->nullable()->after('rejected_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Ini adalah kebalikan dari 'up', untuk menghapus kolomnya
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn('google_calendar_event_id');
        });
    }
};