<?php

namespace Tests\Feature\Admin;

use App\Models\NavigationItem;
use App\Models\NavigationMenu;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NavigationEditorTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolesAndPermissionsSeeder::class);

        $this->admin = User::factory()->create();
        $this->admin->assignRole('Super Administrator');
    }

    public function test_navigation_items_can_be_updated_and_deleted_inline(): void
    {
        $menu = NavigationMenu::query()->create([
            'name' => 'Main Navigation',
            'location' => 'main',
        ]);

        $item = NavigationItem::query()->create([
            'navigation_menu_id' => $menu->id,
            'label' => 'About',
            'url' => '/about',
            'sort_order' => 0,
            'is_visible' => true,
        ]);

        $this->actingAs($this->admin)
            ->get("/admin/navigation/{$menu->id}/edit")
            ->assertOk()
            ->assertSee('Edit')
            ->assertSee('Delete');

        $this->actingAs($this->admin)
            ->put("/admin/navigation/{$menu->id}/items/{$item->id}", [
                'label' => 'Our Group',
                'url' => '/about',
                'is_visible' => '1',
            ])
            ->assertRedirect(route('admin.navigation.edit', $menu));

        $this->assertSame('Our Group', $item->fresh()->label);

        $this->actingAs($this->admin)
            ->delete("/admin/navigation/{$menu->id}/items/{$item->id}")
            ->assertRedirect(route('admin.navigation.edit', $menu));

        $this->assertDatabaseMissing('navigation_items', ['id' => $item->id]);
    }
}
