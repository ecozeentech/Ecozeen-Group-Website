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
            $table->foreignId('company_id')->nullable()->constrained('companies')->nullOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('tagline', 255)->nullable();
            $table->string('short_description', 500)->nullable();
            $table->longText('description')->nullable();
            $table->foreignId('logo_media_id')->nullable()->constrained('media_assets')->nullOnDelete();
            $table->foreignId('screenshot_media_id')->nullable()->constrained('media_assets')->nullOnDelete();
            $table->string('website_url')->nullable();
            $table->json('features')->nullable();
            $table->json('metrics')->nullable();
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->boolean('is_featured')->default(false);
            $table->unsignedInteger('sort_order')->default(0);
            $table->string('seo_title')->nullable();
            $table->string('seo_description', 320)->nullable();
            $table->foreignId('seo_image_media_id')->nullable()->constrained('media_assets')->nullOnDelete();
            $table->boolean('no_index')->default(false);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'is_featured']);
        });

        Schema::create('product_category_product', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_category_id')->constrained()->cascadeOnDelete();
            $table->unique(['product_id', 'product_category_id'], 'product_category_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_category_product');
        Schema::dropIfExists('products');
    }
};
