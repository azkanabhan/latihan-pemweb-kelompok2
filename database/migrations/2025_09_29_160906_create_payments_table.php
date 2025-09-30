<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id('payment_id'); // PK
            $table->unsignedBigInteger('attendee_id'); // FK ke attendees
            $table->unsignedBigInteger('event_id');    // FK ke events
            $table->string('method'); // metode pembayaran
            $table->decimal('amount', 10, 2); // jumlah
            $table->dateTime('payment_date'); // tanggal bayar
            $table->string('qr_code')->nullable();
            $table->enum('status', ['active', 'used', 'cancelled']);
            $table->timestamps();

            // Foreign keys
            $table->foreign('attendee_id')
                ->references('attendee_id')
                ->on('attendees')
                ->onDelete('cascade');

            $table->foreign('event_id')
                ->references('event_id')
                ->on('events')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
