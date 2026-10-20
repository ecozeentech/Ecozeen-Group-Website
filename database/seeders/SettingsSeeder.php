<?php

namespace Database\Seeders;

use App\Models\WebsiteSetting;
use Illuminate\Database\Seeder;

/**
 * Seeds non-sensitive default site copy. Contact email/phone/address
 * fields are intentionally left blank — they are not fabricated here and
 * should be filled in by an administrator with verified information
 * through Settings → Website.
 */
class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = WebsiteSetting::current();

        $settings->update([
            'tagline' => 'A diversified holding company.',
            'supporting_statement' => 'Ecozeen Group oversees a portfolio of operating businesses across financial technology, enterprise IT services, leisure, mining, and agriculture.',
            'footer_text' => 'Ecozeen Group is a diversified holding company overseeing operating businesses across multiple sectors.',
            'footer_legal_text' => '© '.now()->year.' Ecozeen Group. All rights reserved.',
            'newsletter_enabled' => true,
            'sitemap_enabled' => true,
            'search_indexing_enabled' => true,
        ]);
    }
}
