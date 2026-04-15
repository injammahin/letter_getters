<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('child_letters', function (Blueprint $table) {
            $table->foreignId('reviewed_by')->nullable()->after('status')->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable()->after('reviewed_by');
            $table->timestamp('approved_at')->nullable()->after('reviewed_at');
            $table->text('admin_notes')->nullable()->after('approved_at');
        });
    }

    public function down(): void
    {
        Schema::table('child_letters', function (Blueprint $table) {
            $table->dropForeign(['reviewed_by']);
            $table->dropColumn([
                'reviewed_by',
                'reviewed_at',
                'approved_at',
                'admin_notes',
            ]);
        });
    }
};