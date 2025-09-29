<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttendeesTable extends Migration
{
    public function up()
    {
        Schema::create('attendees', function (Blueprint $table) {
            $table->bigIncrements('attendance_id');

            $table->string('username')->nullable();
            $table->string('email')->unique();
            $table->string('password');
            $table->unsignedInteger('age')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('attendees');
    }
}