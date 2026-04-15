<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->string('order_number')->unique();

            $table->string('parent_email')->nullable();

            $table->string('shipping_recipient_name');
            $table->string('shipping_email')->nullable();
            $table->string('shipping_phone')->nullable();
            $table->string('shipping_address_line1');
            $table->string('shipping_address_line2')->nullable();
            $table->string('shipping_city');
            $table->string('shipping_state');
            $table->string('shipping_postal_code')->nullable();
            $table->string('shipping_country')->default('Bangladesh');

            $table->decimal('subtotal', 10, 2);
            $table->decimal('shipping_fee', 10, 2)->default(0);
            $table->decimal('total', 10, 2);

            $table->string('payment_method')->default('card');
            $table->string('payment_status')->default('paid');
            $table->string('payment_last_four')->nullable();

            $table->string('order_status')->default('confirmed');
            $table->string('shipping_status')->default('pending');

            $table->text('customer_note')->nullable();
            $table->text('admin_note')->nullable();

            $table->timestamp('ordered_at')->nullable();
            $table->timestamps();

            $table->index(['order_status', 'shipping_status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};