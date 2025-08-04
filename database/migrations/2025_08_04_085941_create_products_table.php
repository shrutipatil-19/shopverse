<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);  // Product title
            $table->string('slug', 255);  // SEO-friendly URL
            $table->text('description')->nullable();  // Long product description
            $table->text('short_description')->nullable();  // Summary
            $table->string('sku', 100);  // SKU
            $table->decimal('price', 10, 2);  // Regular price
            $table->decimal('discount_price', 10, 2);  // Sale price
            $table->string('currency', 10);  // e.g., INR
            $table->unsignedInteger('quantity');  // Stock
            $table->enum('stock_status', ['in_stock', 'out_of_stock', 'pre_order']);  // Stock status
            $table->boolean('visibility')->default(false);  // Show/hide
            $table->boolean('is_featured')->default(false);  // Featured?
            $table->enum('status', ['active', 'inactive', 'draft']);  // Status
            $table->unsignedBigInteger('category_id');  // FK
            $table->unsignedBigInteger('brand_id');  // FK
            $table->unsignedBigInteger('user_id');  // FK
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
