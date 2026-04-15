<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('child_letters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('child_match_id')->constrained('child_matches')->cascadeOnDelete();
            $table->foreignId('sender_user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('receiver_user_id')->constrained('users')->cascadeOnDelete();
            $table->string('subject');
            $table->longText('body');
            $table->string('status')->default('submitted');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            $table->index(['sender_user_id', 'receiver_user_id']);
            $table->index(['status', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('child_letters');
    }
};