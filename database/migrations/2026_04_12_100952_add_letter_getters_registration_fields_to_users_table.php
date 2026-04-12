<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'username')) {
                $table->string('username')->unique()->nullable()->after('name');
            }

            if (! Schema::hasColumn('users', 'role')) {
                $table->string('role')->default('adult')->after('email');
            }

            if (! Schema::hasColumn('users', 'account_status')) {
                $table->string('account_status')->default('active')->after('role');
            }

            if (! Schema::hasColumn('users', 'parent_email')) {
                $table->string('parent_email')->nullable()->after('account_status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'parent_email')) {
                $table->dropColumn('parent_email');
            }

            if (Schema::hasColumn('users', 'account_status')) {
                $table->dropColumn('account_status');
            }

            if (Schema::hasColumn('users', 'role')) {
                $table->dropColumn('role');
            }

            if (Schema::hasColumn('users', 'username')) {
                $table->dropColumn('username');
            }
        });
    }
};