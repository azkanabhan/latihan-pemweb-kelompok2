<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_creators', function (Blueprint $table) {
            $table->id('user_id'); // Primary Key
            $table->string('email')->unique();
            $table->string('username')->unique();
            $table->string('password');
            $table->integer('age')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_creators');
    }
};
