<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id('ticket_id'); // PK
            $table->unsignedBigInteger('event_id');     // FK ke events
            $table->string('name');
            $table->decimal('price', 10, 2);
            $table->timestamps();

            // Foreign keys
            $table->foreign('event_id')
                ->references('event_id')
                ->on('events')
                ->onDelete('cascade');

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
