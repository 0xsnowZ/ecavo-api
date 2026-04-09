<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('label')->default('Home'); // Home, Work, etc.
            $table->string('name');
            $table->string('phone');
            $table->string('street');
            $table->string('city');
            $table->string('country')->default('EG');
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });

        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->enum('discount_type', ['percent', 'fixed'])->default('percent');
            $table->decimal('value', 8, 2);
            $table->unsignedInteger('max_uses')->nullable();
            $table->unsignedInteger('used_count')->default(0);
            $table->decimal('min_order_amount', 10, 2)->default(0);
            $table->timestamp('expires_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('address_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('status', [
                'placed', 'preparing', 'awaiting_shipment',
                'shipped', 'in_transit', 'delivered',
                'no_answer', 'postponed', 'wrong_address',
                'cancelled', 'returned',
            ])->default('placed');
            $table->decimal('subtotal', 10, 2);
            $table->decimal('delivery_fee', 8, 2)->default(0);
            $table->decimal('discount', 8, 2)->default(0);
            $table->decimal('total', 10, 2);
            $table->string('coupon_code')->nullable();
            $table->text('notes')->nullable();
            // Guest info (no account)
            $table->string('guest_name')->nullable();
            $table->string('guest_phone')->nullable();
            $table->string('guest_address')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('variant_id')->nullable()->constrained('product_variants')->nullOnDelete();
            $table->string('product_name'); // snapshot at order time
            $table->decimal('unit_price', 10, 2);
            $table->unsignedInteger('qty');
            $table->decimal('total', 10, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('coupons');
        Schema::dropIfExists('addresses');
    }
};
