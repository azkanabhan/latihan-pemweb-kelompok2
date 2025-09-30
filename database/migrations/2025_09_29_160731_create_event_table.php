<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id('event_id'); // PK
            $table->unsignedBigInteger('user_id'); // FK ke event_creators

            $table->string('event_name');
            $table->text('event_description');
            $table->string('event_location');
            $table->dateTime('event_date');
            $table->integer('event_capacity');
            $table->timestamps();

            // FK
            $table->foreign('user_id')
                ->references('user_id')
                ->on('event_creators')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
