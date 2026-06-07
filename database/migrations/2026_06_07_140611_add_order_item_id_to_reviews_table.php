<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->foreignId('order_item_id')
                  ->nullable()
                  ->after('user_id')
                  ->constrained('order_items')
                  ->nullOnDelete();

            // One review per (user, product, order_item) — prevents duplicates at DB level
            $table->unique(
                ['user_id', 'product_id', 'order_item_id'],
                'unique_review_per_order_item'
            );
        });
    }

    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropUnique('unique_review_per_order_item');
            $table->dropConstrainedForeignId('order_item_id');
        });
    }
};
