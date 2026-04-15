<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('child_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('child_conversation_id')->constrained('child_conversations')->cascadeOnDelete();
            $table->foreignId('sender_user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('receiver_user_id')->constrained('users')->cascadeOnDelete();
            $table->text('message');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            $table->index(['receiver_user_id', 'read_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('child_messages');
    }
};