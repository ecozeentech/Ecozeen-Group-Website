<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Seeder;

/**
 * Seeds the real Ecozeen Group corporate structure. Only verifiable,
 * non-sensitive identity data is included — no phone numbers, physical
 * addresses, legal registration numbers, financial figures, or customer
 * quotes are fabricated. Administrators can add that information later
 * through the CMS once it is confirmed.
 */
class CompanySeeder extends Seeder
{
    public function run(): void
    {
        $group = Company::query()->updateOrCreate(
            ['slug' => 'ecozeen-group'],
            [
                'name' => 'Ecozeen Group',
                'type' => 'holding',
                'category' => 'Diversified Holding',
                'short_description' => 'The parent holding company overseeing a diversified portfolio of operating businesses across financial technology, enterprise IT services, leisure, mining, and agriculture.',
                'description' => '<p>Ecozeen Group is a diversified holding company that owns and oversees a portfolio of operating businesses spanning financial technology, enterprise IT services, leisure and entertainment, natural resources, and agriculture. Each operating company is managed independently within its sector while benefiting from the Group\'s shared governance, strategic direction, and investment capability.</p>',
                'status' => 'published',
                'is_featured' => true,
                'sort_order' => 0,
                'seo_title' => 'Ecozeen Group — Diversified Holding Company',
                'seo_description' => 'Ecozeen Group is a diversified holding company overseeing operating businesses in fintech, enterprise IT, leisure, mining, and agriculture.',
            ]
        );

        $companies = [
            [
                'slug' => 'ecozeen-swap',
                'name' => 'Ecozeen Swap',
                'type' => 'subsidiary',
                'category' => 'Financial Technology',
                'short_description' => 'A digital asset exchange and currency swap platform enabling fast, secure conversions between digital and fiat currencies.',
                'description' => '<p>Ecozeen Swap is Ecozeen Group\'s financial technology platform for digital asset exchange, enabling users to convert between digital currencies and fiat with a focus on speed, security, and transparent pricing.</p>',
                'website_url' => 'https://ecozeenswap.com',
                'is_featured' => true,
            ],
            [
                'slug' => 'transvoo',
                'name' => 'Transvoo',
                'type' => 'subsidiary',
                'category' => 'Financial Technology',
                'short_description' => 'A financial technology platform focused on cross-border payments and money movement for individuals and businesses.',
                'description' => '<p>Transvoo is Ecozeen Group\'s payments technology business, building tools that make cross-border money movement simpler and more reliable for individuals and businesses.</p>',
                'website_url' => 'https://transvoo.com',
                'is_featured' => true,
            ],
            [
                'slug' => 'accountswift',
                'name' => 'AccountSwift',
                'type' => 'subsidiary',
                'category' => 'Financial Technology',
                'short_description' => 'Accounting and financial management software designed to help businesses track, reconcile, and report on their finances.',
                'description' => '<p>AccountSwift develops accounting and financial management software that helps businesses track transactions, reconcile accounts, and generate financial reports.</p>',
                'website_url' => 'https://accountswift.org',
                'is_featured' => false,
            ],
            [
                'slug' => 'ecozeen-tech-uk',
                'name' => 'Ecozeen Tech UK',
                'type' => 'subsidiary',
                'category' => 'Enterprise IT Services',
                'region' => 'United Kingdom',
                'short_description' => 'Enterprise IT services and software delivery for clients across the United Kingdom and Europe.',
                'description' => '<p>Ecozeen Tech UK delivers enterprise IT services — including software development, systems integration, and technology consulting — to clients across the United Kingdom and Europe.</p>',
                'website_url' => 'https://ecozeentech.org',
                'is_featured' => true,
            ],
            [
                'slug' => 'ecozeen-tech-nigeria',
                'name' => 'Ecozeen Tech Nigeria',
                'type' => 'subsidiary',
                'category' => 'Enterprise IT Services',
                'region' => 'Nigeria',
                'short_description' => 'Enterprise IT services and software delivery for clients across Nigeria and the wider West African region.',
                'description' => '<p>Ecozeen Tech Nigeria delivers enterprise IT services — including software development, systems integration, and technology consulting — to clients across Nigeria and the wider West African region.</p>',
                'website_url' => 'https://ecozeentech.org',
                'is_featured' => true,
            ],
            [
                'slug' => 'playsnooker',
                'name' => 'PlaySnooker',
                'type' => 'subsidiary',
                'category' => 'Leisure & Entertainment',
                'short_description' => 'A leisure and entertainment business centered on snooker and cue-sports venues and events.',
                'description' => '<p>PlaySnooker is Ecozeen Group\'s leisure and entertainment business, operating cue-sports venues and organizing snooker events and tournaments.</p>',
                'is_featured' => false,
            ],
            [
                'slug' => 'ecozeen-mining',
                'name' => 'Ecozeen Mining',
                'type' => 'subsidiary',
                'category' => 'Mining & Commodities',
                'short_description' => 'A natural resources business engaged in mineral exploration, extraction, and commodity trade.',
                'description' => '<p>Ecozeen Mining is Ecozeen Group\'s natural resources business, engaged in mineral exploration, extraction, and commodity trade.</p>',
                'is_featured' => false,
            ],
            [
                'slug' => 'ecozeen-agro-allied',
                'name' => 'Ecozeen Agro Allied',
                'type' => 'subsidiary',
                'category' => 'Agriculture',
                'short_description' => 'An agriculture and agro-allied business focused on farming, processing, and supply of agricultural produce.',
                'description' => '<p>Ecozeen Agro Allied is Ecozeen Group\'s agriculture business, active in farming, processing, and the supply of agricultural produce.</p>',
                'is_featured' => false,
            ],
        ];

        foreach ($companies as $index => $data) {
            Company::query()->updateOrCreate(
                ['slug' => $data['slug']],
                array_merge($data, [
                    'parent_company_id' => $group->id,
                    'status' => 'published',
                    'sort_order' => $index + 1,
                    'seo_title' => $data['name'].' — Ecozeen Group',
                    'seo_description' => $data['short_description'],
                ])
            );
        }
    }
}
