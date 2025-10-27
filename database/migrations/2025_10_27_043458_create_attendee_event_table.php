<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('attendee_event')) {
            Schema::create('attendee_event', function (Blueprint $table) {

                // Foreign key ke tabel attendees
                $table->unsignedBigInteger('attendee_id'); 
                
                // Foreign key ke tabel events (pakai event_id karena di tabel events primary key-nya event_id)
                $table->unsignedBigInteger('event_id'); 

                // Relasi ke tabel attendees (kolom id)
                $table->foreign('attendee_id')
                      ->references('id')
                      ->on('attendees')
                      ->onDelete('cascade');

                // Relasi ke tabel events (kolom event_id)
                $table->foreign('event_id')
                      ->references('event_id')
                      ->on('events')
                      ->onDelete('cascade');

                // Kombinasi unik untuk mencegah duplikasi data peserta
                $table->primary(['attendee_id', 'event_id']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('attendee_event');
    }
};
