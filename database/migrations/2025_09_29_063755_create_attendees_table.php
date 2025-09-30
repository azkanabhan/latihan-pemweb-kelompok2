<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('attendees', function (Blueprint $table) {
            $table->id('attendee_id'); // PK
            $table->string('username');
            $table->string('email')->unique();
            $table->string('password');
            $table->integer('age');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendees');
    }
};
