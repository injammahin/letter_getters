<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_subscriptions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('subscription_plan_id')->constrained()->cascadeOnDelete();

            $table->string('stripe_checkout_session_id')->nullable()->unique();
            $table->string('stripe_customer_id')->nullable();
            $table->string('stripe_subscription_id')->nullable();
            $table->string('stripe_price_id')->nullable();

            $table->decimal('amount', 10, 2)->default(0);
            $table->string('currency', 10)->default('USD');
            $table->string('billing_interval', 30)->nullable();

            $table->string('status')->default('pending'); // pending, active, expired, cancelled, replaced
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->timestamp('purchased_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();

            $table->json('payload')->nullable();

            $table->timestamps();

            $table->index(['user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_subscriptions');
    }
};