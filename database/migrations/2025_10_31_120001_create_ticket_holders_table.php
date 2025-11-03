<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (! Schema::hasTable('ticket_holders')) {
            Schema::create('ticket_holders', function (Blueprint $table) {
                $table->id('ticket_holder_id');

                $table->unsignedBigInteger('attendee_id');
                $table->unsignedBigInteger('event_id');
                $table->string('qr_code')->unique();
                $table->enum('status', ['active', 'used', 'canceled'])->default('active');

                $table->timestamps();

                $table->foreign('attendee_id')
                    ->references('id')
                    ->on('attendees')
                    ->onDelete('cascade');

                $table->foreign('event_id')
                    ->references('event_id')
                    ->on('events')
                    ->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('ticket_holders');
    }
};





