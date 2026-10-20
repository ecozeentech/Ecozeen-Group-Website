<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('template')->default('default');
            $table->enum('status', ['draft', 'in_review', 'scheduled', 'published', 'archived'])->default('draft');
            $table->timestamp('published_at')->nullable();
            $table->timestamp('scheduled_at')->nullable();
            $table->boolean('is_homepage')->default(false);
            $table->string('seo_title')->nullable();
            $table->string('seo_description', 320)->nullable();
            $table->string('canonical_url')->nullable();
            $table->foreignId('seo_image_media_id')->nullable()->constrained('media_assets')->nullOnDelete();
            $table->boolean('no_index')->default(false);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'published_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};
