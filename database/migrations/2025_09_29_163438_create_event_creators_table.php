<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('event_creators', function (Blueprint $table) {
        $table->unsignedBigInteger('user_id')->primary();
        $table->string('email')->unique();
        $table->string('username');
        $table->string('password');
        $table->unsignedInteger('age')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_creators');
    }
};
