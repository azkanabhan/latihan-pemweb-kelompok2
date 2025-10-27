<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (! Schema::hasTable('events')) {
            Schema::create('events', function (Blueprint $table) {
                $table->id('event_id');
                $table->unsignedBigInteger('events_creators_id'); // FK ke event_creators.id (creator)

                $table->string('event_name');
                $table->text('event_description');
                $table->string('event_location');
                $table->dateTime('event_date');
                $table->integer('event_capacity');
                $table->enum('status', ['requested', 'approved', 'rejected'])->default('requested');
                $table->timestamp('approved_at')->nullable();
                $table->timestamp('rejected_at')->nullable();
                $table->timestamps();

                $table->foreign('events_creators_id')
                    ->references('id')
                    ->on('event_creators')
                    ->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        // no-op
    }
};
