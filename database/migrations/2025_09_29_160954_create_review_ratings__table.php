<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('reviews_ratings', function (Blueprint $table) {
            $table->id('review_id');
            $table->unsignedBigInteger('attendee_id');  // FK -> attendees.id
            $table->unsignedBigInteger('event_id');      // FK -> events.event_id

            $table->text('body');
            $table->unsignedTinyInteger('rating');
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

    public function down(): void
    {
        // no-op
    }
};
