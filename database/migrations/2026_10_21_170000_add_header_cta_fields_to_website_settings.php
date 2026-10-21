<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('website_settings', function (Blueprint $table) {
            $table->string('header_cta_label')->nullable()->after('announcement_enabled');
            $table->string('header_cta_url')->nullable()->after('header_cta_label');
            $table->string('header_secondary_label')->nullable()->after('header_cta_url');
            $table->string('header_secondary_url')->nullable()->after('header_secondary_label');
        });

        foreach (DB::table('website_settings')->get() as $row) {
            $legal = trim((string) $row->footer_legal_text);
            $looksLikeCopyright = $legal !== '' && (
                str_contains(mb_strtolower($legal), 'all rights reserved')
                || str_starts_with($legal, '©')
                || str_starts_with($legal, '(c)')
                || str_starts_with($legal, '(C)')
            );

            if ($looksLikeCopyright) {
                DB::table('website_settings')->where('id', $row->id)->update(['footer_legal_text' => null]);
            }
        }
    }

    public function down(): void
    {
        Schema::table('website_settings', function (Blueprint $table) {
            $table->dropColumn([
                'header_cta_label',
                'header_cta_url',
                'header_secondary_label',
                'header_secondary_url',
            ]);
        });
    }
};
