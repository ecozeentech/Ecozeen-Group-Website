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
            'header_cta_label' => 'Partner With Us',
            'header_cta_url' => '/partner',
            'header_secondary_label' => 'Contact',
            'header_secondary_url' => '/contact',
            'footer_text' => 'Ecozeen Group is a diversified holding company overseeing operating businesses across multiple sectors.',
            // Copyright is rendered once by the public footer. Do not seed a
            // second copyright line into footer_legal_text.
            'footer_legal_text' => null,
            'newsletter_enabled' => true,
            'sitemap_enabled' => true,
            'search_indexing_enabled' => true,
        ]);
    }
}
