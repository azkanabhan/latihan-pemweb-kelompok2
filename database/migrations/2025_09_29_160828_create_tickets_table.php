<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (! Schema::hasTable('tickets')) {
            Schema::create('tickets', function (Blueprint $table) {
                $table->id('ticket_id');
                $table->unsignedBigInteger('event_id');
                $table->string('name');
                $table->decimal('price', 10, 2);
                $table->timestamps();

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
