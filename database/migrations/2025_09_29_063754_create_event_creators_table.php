<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventCreatorsTable extends Migration
{
    public function up()
    {
        Schema::create('event_creators', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id');
            $table->primary('user_id');

            $table->string('email')->unique();
            $table->string('username');
            $table->string('password');
            $table->unsignedInteger('age')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('event_creators');
    }
}