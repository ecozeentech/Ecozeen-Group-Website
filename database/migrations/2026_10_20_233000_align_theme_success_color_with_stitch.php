<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('theme_settings')) {
            return;
        }

        DB::table('theme_settings')
            ->where('success_color', '#15803D')
            ->update(['success_color' => '#10B981']);
    }

    public function down(): void
    {
        if (! Schema::hasTable('theme_settings')) {
            return;
        }

        DB::table('theme_settings')
            ->where('success_color', '#10B981')
            ->update(['success_color' => '#15803D']);
    }
};
