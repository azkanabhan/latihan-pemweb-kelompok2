<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void
  {
    Schema::table('tickets', function (Blueprint $table) {
      if (! Schema::hasColumn('tickets', 'event_day_id')) {
        $table->unsignedBigInteger('event_day_id')->nullable()->after('event_id');
        $table->foreign('event_day_id')
          ->references('id')
          ->on('event_days')
          ->onDelete('set null');
      }
    });

    Schema::table('payments', function (Blueprint $table) {
      if (! Schema::hasColumn('payments', 'event_day_id')) {
        $table->unsignedBigInteger('event_day_id')->nullable()->after('event_id');
        $table->foreign('event_day_id')
          ->references('id')
          ->on('event_days')
          ->onDelete('set null');
      }
    });

    Schema::table('ticket_holders', function (Blueprint $table) {
      if (! Schema::hasColumn('ticket_holders', 'event_day_id')) {
        $table->unsignedBigInteger('event_day_id')->nullable()->after('event_id');
        $table->foreign('event_day_id')
          ->references('id')
          ->on('event_days')
          ->onDelete('set null');
      }
    });
  }

  public function down(): void
  {
    Schema::table('tickets', function (Blueprint $table) {
      if (Schema::hasColumn('tickets', 'event_day_id')) {
        $table->dropForeign(['event_day_id']);
        $table->dropColumn('event_day_id');
      }
    });

    Schema::table('payments', function (Blueprint $table) {
      if (Schema::hasColumn('payments', 'event_day_id')) {
        $table->dropForeign(['event_day_id']);
        $table->dropColumn('event_day_id');
      }
    });

    Schema::table('ticket_holders', function (Blueprint $table) {
      if (Schema::hasColumn('ticket_holders', 'event_day_id')) {
        $table->dropForeign(['event_day_id']);
        $table->dropColumn('event_day_id');
      }
    });
  }
};
