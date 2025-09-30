<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('reviews_ratings', function (Blueprint $table) {
            $table->id('review_id'); // PK
            $table->unsignedBigInteger('user_id');  // FK ke attendees
            $table->unsignedBigInteger('event_id'); // FK ke events

            $table->text('body');
            $table->integer('rating')->checkBetween(1, 5);
            $table->timestamps();

            // FK
            $table->foreign('user_id')
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
        Schema::dropIfExists('reviews_ratings');
    }
};
