<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('parent_approvals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('child_user_id')->constrained('users')->cascadeOnDelete();
            $table->string('parent_email');
            $table->string('token')->unique();
            $table->string('status')->default('pending');
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('parent_approvals');
    }
};