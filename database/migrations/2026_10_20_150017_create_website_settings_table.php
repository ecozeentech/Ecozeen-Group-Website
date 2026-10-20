<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('website_settings', function (Blueprint $table) {
            $table->id();
            $table->string('site_name')->default('Ecozeen Group');
            $table->string('tagline')->nullable();
            $table->string('supporting_statement')->nullable();
            $table->foreignId('logo_media_id')->nullable()->constrained('media_assets')->nullOnDelete();
            $table->foreignId('logo_dark_media_id')->nullable()->constrained('media_assets')->nullOnDelete();
            $table->foreignId('favicon_media_id')->nullable()->constrained('media_assets')->nullOnDelete();
            $table->foreignId('default_og_image_media_id')->nullable()->constrained('media_assets')->nullOnDelete();
            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('inquiry_email')->nullable();
            $table->string('press_email')->nullable();
            $table->text('address_primary')->nullable();
            $table->text('address_secondary')->nullable();
            $table->json('social_links')->nullable();
            $table->string('announcement_text')->nullable();
            $table->boolean('announcement_enabled')->default(false);
            $table->string('footer_text', 500)->nullable();
            $table->string('footer_legal_text', 500)->nullable();
            $table->string('google_analytics_id', 32)->nullable();
            $table->boolean('maintenance_mode')->default(false);
            $table->string('maintenance_message', 500)->nullable();
            $table->boolean('newsletter_enabled')->default(true);
            $table->boolean('sitemap_enabled')->default(true);
            $table->boolean('search_indexing_enabled')->default(true);
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('website_settings');
    }
};
