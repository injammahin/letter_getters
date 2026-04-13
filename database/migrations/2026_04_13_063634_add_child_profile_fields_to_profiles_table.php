<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->string('avatar_type')->nullable()->after('avatar');
            $table->string('favorite_color')->nullable()->after('avatar_type');
            $table->timestamp('profile_completed_at')->nullable()->after('favorite_color');
        });
    }

    public function down(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->dropColumn([
                'avatar_type',
                'favorite_color',
                'profile_completed_at',
            ]);
        });
    }
};