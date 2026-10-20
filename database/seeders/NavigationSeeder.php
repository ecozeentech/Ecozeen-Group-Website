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
            ['label' => 'Products', 'url' => '/products'],
            ['label' => 'Services', 'url' => '/services'],
            ['label' => 'Portfolio', 'url' => '/portfolio'],
            ['label' => 'Insights', 'url' => '/insights'],
            ['label' => 'Careers', 'url' => '/careers'],
            ['label' => 'Contact', 'url' => '/contact'],
        ]);

        $footer = NavigationMenu::query()->updateOrCreate(
            ['location' => 'footer'],
            ['name' => 'Footer Navigation']
        );

        $this->replaceItems($footer, [
            ['label' => 'About', 'url' => '/about'],
            ['label' => 'Careers', 'url' => '/careers'],
            ['label' => 'Contact', 'url' => '/contact'],
            ['label' => 'Privacy Policy', 'url' => '/privacy-policy'],
            ['label' => 'Terms of Service', 'url' => '/terms-of-service'],
            ['label' => 'Cookie Policy', 'url' => '/cookie-policy'],
        ]);
    }

    private function replaceItems(NavigationMenu $menu, array $items): void
    {
        $menu->allItems()->delete();

        foreach ($items as $index => $item) {
            NavigationItem::query()->create([
                'navigation_menu_id' => $menu->id,
                'label' => $item['label'],
                'url' => $item['url'],
                'sort_order' => $index,
                'is_visible' => true,
            ]);
        }
    }
}
