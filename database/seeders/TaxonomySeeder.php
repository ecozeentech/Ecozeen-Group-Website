<?php

namespace Database\Seeders;

use App\Models\BlogCategory;
use App\Models\ProductCategory;
use App\Models\ProjectCategory;
use App\Models\ServiceCategory;
use Illuminate\Database\Seeder;

class TaxonomySeeder extends Seeder
{
    public function run(): void
    {
        $this->seed(ProductCategory::class, [
            ['name' => 'Fintech & Payments', 'description' => 'Digital payments, remittance, and currency exchange products.'],
            ['name' => 'Financial Software', 'description' => 'Accounting, bookkeeping, and financial management software.'],
        ], withSortOrder: true);

        $this->seed(ServiceCategory::class, [
            ['name' => 'Software Development', 'description' => 'Custom software, web, and mobile application development.'],
            ['name' => 'Systems Integration', 'description' => 'Integrating enterprise systems, APIs, and data platforms.'],
            ['name' => 'IT Consulting', 'description' => 'Technology strategy, architecture, and advisory services.'],
        ], withSortOrder: true);

        $this->seed(ProjectCategory::class, [
            ['name' => 'Fintech Platforms', 'description' => 'Case studies from payments and exchange platform delivery.'],
            ['name' => 'Enterprise Systems', 'description' => 'Case studies from enterprise software and integration projects.'],
        ], withSortOrder: true);

        $this->seed(BlogCategory::class, [
            ['name' => 'Company News', 'description' => 'Announcements and updates from across Ecozeen Group.'],
            ['name' => 'Fintech & Markets', 'description' => 'Perspectives on financial technology and markets.'],
            ['name' => 'Technology', 'description' => 'Engineering and enterprise technology insights.'],
        ], withSortOrder: false);
    }

    /**
     * `php artisan db:seed` wraps every seeder call in `Model::unguarded()`,
     * which bypasses `$fillable` entirely for the duration of the command.
     * We therefore must not rely on mass-assignment guarding to silently
     * drop attributes a given model doesn't define (e.g. `sort_order` on
     * `BlogCategory`) — only add fields the target model actually has.
     */
    private function seed(string $modelClass, array $items, bool $withSortOrder): void
    {
        foreach ($items as $index => $data) {
            if ($withSortOrder) {
                $data['sort_order'] = $index;
            }

            $modelClass::query()->updateOrCreate(
                ['name' => $data['name']],
                $data
            );
        }
    }
}
