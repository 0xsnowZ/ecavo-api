<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name_ar');
            $table->string('name_en');
            $table->string('slug')->unique();
            $table->text('description_ar')->nullable();
            $table->text('description_en')->nullable();
            $table->decimal('price', 10, 2);
            $table->decimal('original_price', 10, 2)->nullable();
            $table->unsignedTinyInteger('discount_percent')->nullable();
            $table->unsignedInteger('stock')->default(0);
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->json('images')->nullable();       // array of image paths
            $table->json('specifications')->nullable(); // key-value pairs
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->timestamp('deal_ends_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('attribute'); // color, size, etc.
            $table->string('value');
            $table->decimal('extra_price', 8, 2)->default(0);
            $table->unsignedInteger('stock')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_variants');
        Schema::dropIfExists('products');
    }
};
