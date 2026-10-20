<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Project;
use App\Models\ProjectCategory;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        $fintechCategory = ProjectCategory::query()->where('name', 'Fintech Platforms')->first();
        $enterpriseCategory = ProjectCategory::query()->where('name', 'Enterprise Systems')->first();

        $techUk = Company::query()->where('slug', 'ecozeen-tech-uk')->first();
        $techNg = Company::query()->where('slug', 'ecozeen-tech-nigeria')->first();

        $projects = [
            [
                'slug' => 'payments-platform-modernization',
                'title' => 'Payments Platform Modernization',
                'company_id' => $techUk?->id,
                'project_category_id' => $fintechCategory?->id,
                'summary' => 'Modernizing a legacy payments platform with a resilient, API-first architecture.',
                'description' => '<p>Our team led the modernization of a legacy payments platform, re-architecting core services around a resilient, API-first design to improve reliability and extensibility.</p>',
                'outcomes' => '<p>The modernized platform improved system reliability and gave the client\'s engineering team a clearer path for adding new payment capabilities going forward.</p>',
                'is_featured' => true,
            ],
            [
                'slug' => 'enterprise-data-integration',
                'title' => 'Enterprise Data Integration',
                'company_id' => $techNg?->id,
                'project_category_id' => $enterpriseCategory?->id,
                'summary' => 'Integrating disparate enterprise systems into a unified data platform.',
                'description' => '<p>We integrated several disparate enterprise systems into a unified data platform, enabling consistent reporting and reducing manual data reconciliation work.</p>',
                'outcomes' => '<p>The integration reduced manual reconciliation effort and gave stakeholders a single, consistent view of operational data.</p>',
                'is_featured' => true,
            ],
        ];

        foreach ($projects as $data) {
            Project::query()->updateOrCreate(
                ['slug' => $data['slug']],
                array_merge($data, [
                    'status' => 'published',
                    'seo_title' => $data['title'].' — Ecozeen Group',
                    'seo_description' => $data['summary'],
                ])
            );
        }
    }
}
