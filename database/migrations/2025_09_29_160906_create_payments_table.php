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

                // Foreign keys
                $table->unsignedBigInteger('event_id');    // FK -> events.event_id
                $table->unsignedBigInteger('ticket_id');   // FK -> tickets.ticket_id
                $table->unsignedBigInteger('attendee_id')->nullable(); // FK -> attendees.id
                $table->unsignedBigInteger('user_id')->nullable(); // FK -> users.id

                // Payment details
                $table->integer('quantity')->default(1);
                $table->string('method');
                $table->decimal('amount', 10, 2);
                $table->dateTime('payment_date')->nullable();
                $table->enum('status', ['paid', 'pending', 'cancelled'])->default('pending');

                // VA/Payment gateway fields
                $table->string('external_id')->nullable();
                $table->string('va_number')->nullable();
                $table->text('payment_url')->nullable();
                $table->dateTime('expired_at')->nullable();

                $table->timestamps();

                $table->foreign('event_id')
                    ->references('event_id')
                    ->on('events')
                    ->onDelete('cascade');

                $table->foreign('ticket_id')
                    ->references('ticket_id')
                    ->on('tickets')
                    ->onDelete('cascade');

                $table->foreign('attendee_id')
                    ->references('id')
                    ->on('attendees')
                    ->onDelete('set null');

                $table->foreign('user_id')
                    ->references('id')
                    ->on('users')
                    ->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        // no-op
    }
};
