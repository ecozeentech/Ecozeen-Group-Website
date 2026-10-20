<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\JobListing;
use Illuminate\Database\Seeder;

class JobListingSeeder extends Seeder
{
    public function run(): void
    {
        $techUk = Company::query()->where('slug', 'ecozeen-tech-uk')->first();
        $techNg = Company::query()->where('slug', 'ecozeen-tech-nigeria')->first();

        $listings = [
            [
                'slug' => 'software-engineer-ecozeen-tech-uk',
                'title' => 'Software Engineer',
                'company_id' => $techUk?->id,
                'department' => 'Engineering',
                'employment_type' => 'full_time',
                'description' => '<p>We are looking for a Software Engineer to join our enterprise IT services team, building and maintaining client software projects.</p>',
                'requirements' => '<p>Experience building web applications and working directly with clients on technical requirements.</p>',
            ],
            [
                'slug' => 'it-consultant-ecozeen-tech-nigeria',
                'title' => 'IT Consultant',
                'company_id' => $techNg?->id,
                'department' => 'Consulting',
                'employment_type' => 'full_time',
                'description' => '<p>We are looking for an IT Consultant to advise clients on technology strategy and architecture decisions.</p>',
                'requirements' => '<p>Experience advising organizations on technology strategy, systems architecture, or digital transformation.</p>',
            ],
        ];

        foreach ($listings as $data) {
            JobListing::query()->updateOrCreate(
                ['slug' => $data['slug']],
                array_merge($data, [
                    'status' => 'open',
                    'seo_title' => $data['title'].' — Ecozeen Group Careers',
                    'seo_description' => strip_tags($data['description']),
                ])
            );
        }
    }
}
