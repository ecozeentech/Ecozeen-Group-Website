<?php

namespace Tests\Feature\Admin;

use App\Models\Page;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAccessTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolesAndPermissionsSeeder::class);
    }

    public function test_guests_are_redirected_to_login(): void
    {
        $this->get('/admin')->assertRedirect('/login');
    }

    public function test_super_administrator_can_access_the_dashboard(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('Super Administrator');

        $this->actingAs($admin)->get('/admin')->assertOk();
    }

    public function test_read_only_analyst_cannot_create_a_page(): void
    {
        $analyst = User::factory()->create();
        $analyst->assignRole('Read-only Analyst');

        $this->actingAs($analyst)
            ->get('/admin/pages/create')
            ->assertForbidden();

        $this->actingAs($analyst)
            ->post('/admin/pages', ['title' => 'Should Not Save'])
            ->assertForbidden();

        $this->assertDatabaseMissing('pages', ['title' => 'Should Not Save']);
    }

    public function test_read_only_analyst_can_view_pages_index(): void
    {
        $analyst = User::factory()->create();
        $analyst->assignRole('Read-only Analyst');

        $this->actingAs($analyst)->get('/admin/pages')->assertOk();
    }

    public function test_only_super_administrator_can_manage_roles(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('Administrator');

        $this->actingAs($admin)->get('/admin/roles/create')->assertForbidden();

        $superAdmin = User::factory()->create();
        $superAdmin->assignRole('Super Administrator');

        $this->actingAs($superAdmin)->get('/admin/roles/create')->assertOk();
    }

    public function test_a_user_cannot_delete_their_own_account(): void
    {
        $superAdmin = User::factory()->create();
        $superAdmin->assignRole('Super Administrator');

        $this->actingAs($superAdmin)
            ->delete("/admin/users/{$superAdmin->id}")
            ->assertForbidden();

        $this->assertNotNull($superAdmin->fresh());
    }

    public function test_a_user_cannot_grant_themselves_additional_roles(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('Administrator');

        $this->actingAs($admin)
            ->put("/admin/users/{$admin->id}/roles", ['roles' => ['Super Administrator']])
            ->assertForbidden();

        $this->assertFalse($admin->fresh()->hasRole('Super Administrator'));
    }

    public function test_inquiry_manager_cannot_access_page_builder(): void
    {
        $manager = User::factory()->create();
        $manager->assignRole('Inquiry Manager');

        $this->actingAs($manager)->get('/admin/pages')->assertForbidden();
        $this->actingAs($manager)->get('/admin/inquiries')->assertOk();
    }

    public function test_content_manager_can_create_a_page_but_not_manage_users(): void
    {
        $manager = User::factory()->create();
        $manager->assignRole('Content Manager');

        $this->actingAs($manager)->get('/admin/pages/create')->assertOk();
        $this->actingAs($manager)->get('/admin/users')->assertForbidden();
    }

    public function test_page_resource_show_route_does_not_exist(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('Super Administrator');
        $page = Page::factory()->create();

        $this->actingAs($admin)
            ->get("/admin/pages/{$page->id}")
            ->assertMethodNotAllowed();
    }
}
