<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->nullable()->constrained('companies')->nullOnDelete();
            $table->foreignId('project_category_id')->nullable()->constrained('project_categories')->nullOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('client', 255)->nullable();
            $table->string('summary', 500)->nullable();
            $table->longText('description')->nullable();
            $table->longText('outcomes')->nullable();
            $table->foreignId('cover_media_id')->nullable()->constrained('media_assets')->nullOnDelete();
            $table->date('completed_at')->nullable();
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

        Schema::create('project_service', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->foreignId('service_id')->constrained()->cascadeOnDelete();
            $table->unique(['project_id', 'service_id']);
        });

        Schema::create('project_media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->foreignId('media_asset_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('sort_order')->default(0);
            $table->unique(['project_id', 'media_asset_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_media');
        Schema::dropIfExists('project_service');
        Schema::dropIfExists('projects');
    }
};
