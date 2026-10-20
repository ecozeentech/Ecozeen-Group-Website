<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contact_inquiries', function (Blueprint $table) {
            $table->id();
            $table->enum('inquiry_type', [
                'institutional_investment',
                'business_partnership',
                'enterprise_it_services',
                'commodity_trade',
                'media_press',
                'executive_careers',
                'general',
            ])->default('general');
            $table->string('full_name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('organization')->nullable();
            $table->foreignId('target_company_id')->nullable()->constrained('companies')->nullOnDelete();
            $table->string('jurisdiction')->nullable();
            $table->text('message');
            $table->timestamp('consent_at')->nullable();
            $table->enum('status', ['new', 'in_progress', 'resolved', 'spam'])->default('new');
            $table->foreignId('handled_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('internal_notes')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();

            $table->index(['status', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_inquiries');
    }
};
