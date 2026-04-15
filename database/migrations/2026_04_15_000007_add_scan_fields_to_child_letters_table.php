<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('child_letters', function (Blueprint $table) {
            $table->string('scan_status')->default('not_scanned')->after('admin_notes');
            $table->boolean('scan_flagged')->default(false)->after('scan_status');
            $table->json('scan_hits')->nullable()->after('scan_flagged');
            $table->text('scan_summary')->nullable()->after('scan_hits');
            $table->foreignId('scanned_by')->nullable()->after('scan_summary')->constrained('users')->nullOnDelete();
            $table->timestamp('scanned_at')->nullable()->after('scanned_by');
        });
    }

    public function down(): void
    {
        Schema::table('child_letters', function (Blueprint $table) {
            $table->dropForeign(['scanned_by']);
            $table->dropColumn([
                'scan_status',
                'scan_flagged',
                'scan_hits',
                'scan_summary',
                'scanned_by',
                'scanned_at',
            ]);
        });
    }
};