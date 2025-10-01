<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (! Schema::hasTable('payments')) {
            Schema::create('payments', function (Blueprint $table) {
                $table->id('payment_id');
                $table->unsignedBigInteger('attendee_id'); // FK -> attendees.id (attendee)
                $table->unsignedBigInteger('event_id');    // FK -> events.event_id
                $table->string('method');
                $table->decimal('amount', 10, 2);
                $table->dateTime('payment_date');
                $table->string('qr_code')->nullable();
                $table->enum('status', ['active', 'used', 'cancelled']);
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
        // no-op
    }
};
