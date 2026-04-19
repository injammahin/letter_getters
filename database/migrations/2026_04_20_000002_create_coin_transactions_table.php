<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('coin_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->string('type')->default('credit');
            $table->string('reward_key')->nullable();
            $table->string('label');
            $table->integer('amount');

            $table->unsignedInteger('balance_before')->default(0);
            $table->unsignedInteger('balance_after')->default(0);

            $table->json('meta')->nullable();

            $table->timestamp('animation_seen_at')->nullable();

            $table->timestamps();

            $table->unique(['user_id', 'reward_key']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coin_transactions');
    }
};