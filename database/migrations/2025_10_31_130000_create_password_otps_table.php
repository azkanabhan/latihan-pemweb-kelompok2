<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('password_otps')) {
            Schema::create('password_otps', function (Blueprint $table) {
                $table->id();
                $table->string('email')->index();
                $table->string('code', 10);
                $table->dateTime('expires_at');
                $table->dateTime('consumed_at')->nullable();
                $table->unsignedTinyInteger('attempts')->default(0);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('password_otps');
    }
};



