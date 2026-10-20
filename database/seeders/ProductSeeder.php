<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $fintechCategory = ProductCategory::query()->where('name', 'Fintech & Payments')->first();
        $financeSoftwareCategory = ProductCategory::query()->where('name', 'Financial Software')->first();

        $swap = Company::query()->where('slug', 'ecozeen-swap')->first();
        $transvoo = Company::query()->where('slug', 'transvoo')->first();
        $accountSwift = Company::query()->where('slug', 'accountswift')->first();

        $products = [
            [
                'slug' => 'ecozeen-swap-exchange',
                'company_id' => $swap?->id,
                'name' => 'Ecozeen Swap Exchange',
                'tagline' => 'Fast, secure digital asset and currency conversion.',
                'short_description' => 'A digital asset exchange platform for converting between digital currencies and fiat.',
                'description' => '<p>The Ecozeen Swap Exchange platform lets users convert between digital currencies and fiat with transparent pricing and secure transaction handling.</p>',
                'features' => ['Digital currency conversion', 'Transparent, real-time pricing', 'Secure transaction processing'],
                'is_featured' => true,
                'category' => $fintechCategory,
            ],
            [
                'slug' => 'transvoo-transfer',
                'company_id' => $transvoo?->id,
                'name' => 'Transvoo Transfer',
                'tagline' => 'Cross-border money movement, simplified.',
                'short_description' => 'A payments platform for sending money across borders.',
                'description' => '<p>Transvoo Transfer is a cross-border payments product built to make sending money between countries simpler and more reliable for individuals and businesses.</p>',
                'features' => ['Cross-border transfers', 'Multiple funding options', 'Transfer status tracking'],
                'is_featured' => true,
                'category' => $fintechCategory,
            ],
            [
                'slug' => 'accountswift-books',
                'company_id' => $accountSwift?->id,
                'name' => 'AccountSwift Books',
                'tagline' => 'Accounting software built for growing businesses.',
                'short_description' => 'Accounting and bookkeeping software for tracking income, expenses, and financial reports.',
                'description' => '<p>AccountSwift Books helps businesses track income and expenses, reconcile accounts, and generate the financial reports they need to manage their operations.</p>',
                'features' => ['Income & expense tracking', 'Bank reconciliation', 'Financial reporting'],
                'is_featured' => false,
                'category' => $financeSoftwareCategory,
            ],
        ];

        foreach ($products as $data) {
            $category = $data['category'];
            unset($data['category']);

            $product = Product::query()->updateOrCreate(
                ['slug' => $data['slug']],
                array_merge($data, [
                    'status' => 'published',
                    'seo_title' => $data['name'].' — Ecozeen Group',
                    'seo_description' => $data['short_description'],
                ])
            );

            if ($category) {
                $product->categories()->syncWithoutDetaching([$category->id]);
            }
        }
    }
}
