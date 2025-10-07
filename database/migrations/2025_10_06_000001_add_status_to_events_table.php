<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('events') && ! Schema::hasColumn('events', 'status')) {
            Schema::table('events', function (Blueprint $table) {
                $table->enum('status', ['requested','approved','rejected'])->default('requested')->after('event_capacity');
                $table->timestamp('approved_at')->nullable()->after('status');
                $table->timestamp('rejected_at')->nullable()->after('approved_at');
                $table->index('status');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('events') && Schema::hasColumn('events', 'status')) {
            Schema::table('events', function (Blueprint $table) {
                $table->dropIndex(['status']);
                $table->dropColumn(['status', 'approved_at', 'rejected_at']);
            });
        }
    }
};


