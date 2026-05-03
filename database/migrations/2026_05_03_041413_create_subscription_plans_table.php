<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscription_plans', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('slug')->unique();

            $table->string('badge')->nullable();
            $table->string('short_description')->nullable();
            $table->text('description')->nullable();

            $table->json('features')->nullable();

            $table->decimal('price', 10, 2)->default(0);
            $table->string('currency', 10)->default('USD');
            $table->string('billing_interval', 30)->default('monthly');

            $table->unsignedInteger('trial_days')->default(0);

            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);

            $table->timestamps();

            $table->index(['is_active', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscription_plans');
    }
};