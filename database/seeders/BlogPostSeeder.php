<?php

namespace Database\Seeders;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\User;
use Illuminate\Database\Seeder;

class BlogPostSeeder extends Seeder
{
    public function run(): void
    {
        $companyNews = BlogCategory::query()->where('name', 'Company News')->first();
        $technology = BlogCategory::query()->where('name', 'Technology')->first();
        $author = User::query()->first();

        $posts = [
            [
                'slug' => 'welcome-to-the-new-ecozeen-group-website',
                'title' => 'Welcome to the New Ecozeen Group Website',
                'blog_category_id' => $companyNews?->id,
                'excerpt' => 'We have launched a new website to make it easier to learn about the businesses across Ecozeen Group.',
                'body' => '<p>We are glad to welcome you to the new Ecozeen Group website. This site brings together information about every operating company in our portfolio — from financial technology to enterprise IT services, leisure, mining, and agriculture — in one place.</p><p>We will continue to add updates, insights, and news here as our businesses grow.</p>',
                'is_featured' => true,
            ],
            [
                'slug' => 'how-we-think-about-enterprise-software-delivery',
                'title' => 'How We Think About Enterprise Software Delivery',
                'blog_category_id' => $technology?->id,
                'excerpt' => 'A look at the principles our enterprise IT teams apply when delivering software for clients.',
                'body' => '<p>Across our enterprise IT services companies, we favor incremental delivery, clear ownership, and architecture that can evolve with a client\'s needs. This post shares a few of the principles that guide how our teams approach software delivery projects.</p>',
                'is_featured' => false,
            ],
        ];

        foreach ($posts as $data) {
            BlogPost::query()->updateOrCreate(
                ['slug' => $data['slug']],
                array_merge($data, [
                    'author_id' => $author?->id,
                    'status' => 'published',
                    'published_at' => now(),
                    'seo_title' => $data['title'].' — Ecozeen Group',
                    'seo_description' => $data['excerpt'],
                ])
            );
        }
    }
}
