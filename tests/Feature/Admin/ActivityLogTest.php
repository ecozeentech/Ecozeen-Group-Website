<?php

namespace Tests\Feature\Admin;

use App\Models\ActivityLog;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ActivityLogTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolesAndPermissionsSeeder::class);
    }

    public function test_an_administrator_can_delete_a_single_activity_entry(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('Administrator');

        $log = ActivityLog::query()->create([
            'user_id' => $admin->id,
            'action' => 'updated',
            'description' => 'Temporary entry',
            'created_at' => now(),
        ]);

        $this->actingAs($admin)
            ->from('/admin/activity')
            ->delete("/admin/activity/{$log->id}")
            ->assertRedirect('/admin/activity')
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('activity_logs', ['id' => $log->id]);
    }

    public function test_an_administrator_can_clear_the_activity_log(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('Super Administrator');

        ActivityLog::query()->create([
            'user_id' => $admin->id,
            'action' => 'created',
            'description' => 'Keep me not',
            'created_at' => now(),
        ]);

        $this->actingAs($admin)
            ->from('/admin/activity')
            ->delete('/admin/activity/clear')
            ->assertRedirect(route('admin.activity.index'))
            ->assertSessionHas('success');

        $this->assertSame(0, ActivityLog::query()->count());
    }

    public function test_a_read_only_analyst_can_view_but_not_clear_the_activity_log(): void
    {
        $analyst = User::factory()->create();
        $analyst->assignRole('Read-only Analyst');

        $log = ActivityLog::query()->create([
            'action' => 'updated',
            'description' => 'Visible to analysts',
            'created_at' => now(),
        ]);

        $this->actingAs($analyst)->get('/admin/activity')->assertOk();

        $this->actingAs($analyst)
            ->delete('/admin/activity/clear')
            ->assertForbidden();

        $this->actingAs($analyst)
            ->delete("/admin/activity/{$log->id}")
            ->assertForbidden();

        $this->assertDatabaseHas('activity_logs', ['id' => $log->id]);
    }
}
