<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('child_conversations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('child_match_id')->constrained('child_matches')->cascadeOnDelete();
            $table->timestamps();

            $table->unique('child_match_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('child_conversations');
    }
};