<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('child_match_requests', 'accepted_by')) {
            Schema::table('child_match_requests', function (Blueprint $table) {
                $table->foreignId('accepted_by')
                    ->nullable()
                    ->after('reviewed_at')
                    ->constrained('users')
                    ->nullOnDelete();

                $table->timestamp('accepted_at')
                    ->nullable()
                    ->after('accepted_by');

                $table->foreignId('declined_by')
                    ->nullable()
                    ->after('accepted_at')
                    ->constrained('users')
                    ->nullOnDelete();

                $table->timestamp('declined_at')
                    ->nullable()
                    ->after('declined_by');
            });
        }

        DB::table('child_match_requests')
            ->where('status', 'pending_admin_approval')
            ->update([
                'status' => 'pending_receiver_approval',
            ]);

        DB::table('child_match_requests')
            ->where('status', 'approved')
            ->update([
                'status' => 'accepted',
                'accepted_at' => DB::raw('COALESCE(reviewed_at, updated_at)'),
                'accepted_by' => DB::raw('reviewed_by'),
            ]);
    }

    public function down(): void
    {
        DB::table('child_match_requests')
            ->where('status', 'pending_receiver_approval')
            ->update([
                'status' => 'pending_admin_approval',
            ]);

        DB::table('child_match_requests')
            ->where('status', 'accepted')
            ->update([
                'status' => 'approved',
            ]);

        if (Schema::hasColumn('child_match_requests', 'accepted_by')) {
            Schema::table('child_match_requests', function (Blueprint $table) {
                $table->dropForeign(['accepted_by']);
                $table->dropForeign(['declined_by']);

                $table->dropColumn([
                    'accepted_by',
                    'accepted_at',
                    'declined_by',
                    'declined_at',
                ]);
            });
        }
    }
};