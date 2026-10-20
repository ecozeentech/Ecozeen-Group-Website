<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $devCategory = ServiceCategory::query()->where('name', 'Software Development')->first();
        $integrationCategory = ServiceCategory::query()->where('name', 'Systems Integration')->first();
        $consultingCategory = ServiceCategory::query()->where('name', 'IT Consulting')->first();

        $techUk = Company::query()->where('slug', 'ecozeen-tech-uk')->first();
        $techNg = Company::query()->where('slug', 'ecozeen-tech-nigeria')->first();

        $services = [
            [
                'slug' => 'custom-software-development',
                'name' => 'Custom Software Development',
                'summary' => 'Web and mobile application development tailored to your business requirements.',
                'description' => '<p>We design, build, and maintain custom web and mobile applications tailored to the specific needs of each client, from initial scoping through to long-term support.</p>',
                'icon' => 'code',
                'service_category_id' => $devCategory?->id,
                'is_featured' => true,
            ],
            [
                'slug' => 'systems-integration',
                'name' => 'Systems Integration',
                'summary' => 'Connecting enterprise systems, APIs, and data platforms so they work together.',
                'description' => '<p>We help organizations connect their enterprise systems, APIs, and data platforms so information flows smoothly between teams and tools.</p>',
                'icon' => 'link',
                'service_category_id' => $integrationCategory?->id,
                'is_featured' => true,
            ],
            [
                'slug' => 'it-strategy-consulting',
                'name' => 'IT Strategy & Consulting',
                'summary' => 'Technology strategy, architecture review, and advisory services for growing organizations.',
                'description' => '<p>Our consulting team works with clients on technology strategy, architecture review, and advisory services to help growing organizations make confident technology decisions.</p>',
                'icon' => 'briefcase',
                'service_category_id' => $consultingCategory?->id,
                'is_featured' => false,
            ],
        ];

        foreach ($services as $data) {
            $service = Service::query()->updateOrCreate(
                ['slug' => $data['slug']],
                array_merge($data, [
                    'status' => 'published',
                    'seo_title' => $data['name'].' — Ecozeen Group',
                    'seo_description' => $data['summary'],
                ])
            );

            $companyIds = array_filter([$techUk?->id, $techNg?->id]);
            if ($companyIds) {
                $service->companies()->syncWithoutDetaching($companyIds);
            }
        }
    }
}
