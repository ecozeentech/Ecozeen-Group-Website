<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('theme_settings', function (Blueprint $table) {
            $table->id();
            $table->string('primary_color', 7)->default('#0F172A');
            $table->string('secondary_color', 7)->default('#2563EB');
            $table->string('accent_color', 7)->default('#38BDF8');
            $table->string('success_color', 7)->default('#15803D');
            $table->string('text_color', 7)->default('#111827');
            $table->string('muted_text_color', 7)->default('#64748B');
            $table->string('background_color', 7)->default('#F8FAFC');
            $table->string('surface_color', 7)->default('#FFFFFF');
            $table->string('border_color', 7)->default('#E2E8F0');
            $table->string('heading_font')->default('Manrope');
            $table->string('body_font')->default('Inter');
            $table->enum('container_width', ['narrow', 'default', 'wide'])->default('default');
            $table->enum('section_spacing', ['compact', 'default', 'spacious'])->default('default');
            $table->enum('radius_scale', ['sharp', 'soft', 'rounded'])->default('soft');
            $table->enum('shadow_scale', ['flat', 'subtle', 'elevated'])->default('subtle');
            $table->enum('header_variant', ['standard', 'glass', 'transparent'])->default('glass');
            $table->enum('footer_variant', ['standard', 'minimal', 'expanded'])->default('expanded');
            $table->enum('button_style', ['solid', 'outline', 'pill'])->default('solid');
            $table->enum('card_style', ['bordered', 'shadowed', 'flat'])->default('bordered');
            $table->boolean('dark_mode_enabled')->default(false);
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('theme_settings');
    }
};
