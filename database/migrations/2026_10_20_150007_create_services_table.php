<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_category_id')->nullable()->constrained('service_categories')->nullOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('summary', 500)->nullable();
            $table->longText('description')->nullable();
            $table->string('icon')->nullable();
            $table->foreignId('cover_media_id')->nullable()->constrained('media_assets')->nullOnDelete();
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

        Schema::create('company_service', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('service_id')->constrained()->cascadeOnDelete();
            $table->unique(['company_id', 'service_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('company_service');
        Schema::dropIfExists('services');
    }
};
