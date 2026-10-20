<?php

namespace Database\Seeders;

use App\Models\NavigationItem;
use App\Models\NavigationMenu;
use Illuminate\Database\Seeder;

class NavigationSeeder extends Seeder
{
    public function run(): void
    {
        $main = NavigationMenu::query()->updateOrCreate(
            ['location' => 'main'],
            ['name' => 'Main Navigation']
        );

        $this->replaceItems($main, [
            ['label' => 'About', 'url' => '/about'],
            ['label' => 'Companies', 'url' => '/companies'],
            ['label' => 'Products & Platforms', 'url' => '/products'],
            ['label' => 'Services', 'url' => '/services'],
            ['label' => 'Portfolio', 'url' => '/portfolio'],
            ['label' => 'Insights', 'url' => '/insights'],
        ]);

        $footer = NavigationMenu::query()->updateOrCreate(
            ['location' => 'footer'],
            ['name' => 'Footer Navigation']
        );

        $this->replaceItems($footer, [
            [
                'label' => 'Group',
                'url' => '/about',
                'children' => [
                    ['label' => 'About', 'url' => '/about'],
                    ['label' => 'Companies', 'url' => '/companies'],
                    ['label' => 'Insights', 'url' => '/insights'],
                    ['label' => 'Partner With Us', 'url' => '/partner'],
                    ['label' => 'Contact', 'url' => '/contact'],
                ],
            ],
            [
                'label' => 'Legal',
                'url' => '/privacy-policy',
                'children' => [
                    ['label' => 'Careers', 'url' => '/careers'],
                    ['label' => 'Privacy Policy', 'url' => '/privacy-policy'],
                    ['label' => 'Terms of Service', 'url' => '/terms-of-service'],
                    ['label' => 'Cookie Policy', 'url' => '/cookie-policy'],
                ],
            ],
        ]);
    }

    private function replaceItems(NavigationMenu $menu, array $items, ?int $parentId = null): void
    {
        if ($parentId === null) {
            $menu->allItems()->delete();
        }

        foreach ($items as $index => $item) {
            $row = NavigationItem::query()->create([
                'navigation_menu_id' => $menu->id,
                'parent_id' => $parentId,
                'label' => $item['label'],
                'url' => $item['url'] ?? '#',
                'sort_order' => $index,
                'is_visible' => true,
            ]);

            if (! empty($item['children'])) {
                $this->replaceItems($menu, $item['children'], $row->id);
            }
        }
    }
}
