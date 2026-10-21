<?php

namespace Tests\Feature\Admin;

use App\Models\Page;
use App\Models\PageSection;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PageBuilderTest extends TestCase
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

    public function test_a_page_can_be_created_and_edited_in_the_builder(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/pages', [
            'title' => 'About Us',
            'status' => 'draft',
            'template' => 'default',
        ]);

        $page = Page::where('title', 'About Us')->first();
        $this->assertNotNull($page);
        $response->assertRedirect("/admin/pages/{$page->id}/edit");

        $this->actingAs($this->admin)
            ->get("/admin/pages/{$page->id}/edit")
            ->assertOk()
            ->assertSee('Edit')
            ->assertSee('Delete');
    }

    public function test_sections_can_be_added_configured_and_removed(): void
    {
        $page = Page::factory()->create();

        // Adding a section creates an empty placeholder of the chosen
        // type; its settings are configured afterwards via update().
        $this->actingAs($this->admin)->post("/admin/pages/{$page->id}/sections", [
            'type' => 'hero',
        ])->assertRedirect();

        $this->assertDatabaseHas('page_sections', [
            'page_id' => $page->id,
            'type' => 'hero',
        ]);

        $section = PageSection::where('page_id', $page->id)->first();

        // Only fields declared for the "hero" type in SectionTypeRegistry
        // are persisted; unknown keys must never reach the database.
        $this->actingAs($this->admin)->put("/admin/pages/{$page->id}/sections/{$section->id}", [
            'settings' => ['heading' => 'Updated Heading', 'not_a_real_field' => 'should be dropped'],
        ])->assertRedirect();

        $section->refresh();
        $this->assertSame('Updated Heading', $section->settings['heading']);
        $this->assertArrayNotHasKey('not_a_real_field', $section->settings);

        $this->actingAs($this->admin)
            ->delete("/admin/pages/{$page->id}/sections/{$section->id}")
            ->assertRedirect();

        $this->assertDatabaseMissing('page_sections', ['id' => $section->id]);
    }

    public function test_malicious_html_in_a_richtext_field_is_sanitized(): void
    {
        $page = Page::factory()->create();
        $section = $page->sections()->create(['type' => 'rich_text', 'settings' => [], 'position' => 0]);

        $this->actingAs($this->admin)->put("/admin/pages/{$page->id}/sections/{$section->id}", [
            'settings' => [
                'heading' => 'Safe heading',
                'content' => '<p>Hello</p><script>alert("xss")</script>',
            ],
        ])->assertRedirect();

        $section->refresh();

        $this->assertStringNotContainsString('<script>', $section->settings['content']);
        $this->assertStringContainsString('Hello', $section->settings['content']);
    }

    public function test_a_page_can_be_published_and_unpublished(): void
    {
        $page = Page::factory()->create(['status' => 'draft']);

        $this->actingAs($this->admin)->post("/admin/pages/{$page->id}/publish")->assertRedirect();
        $this->assertSame('published', $page->refresh()->status);
        $this->assertNotNull($page->published_at);

        $this->actingAs($this->admin)->post("/admin/pages/{$page->id}/unpublish")->assertRedirect();
        $this->assertSame('draft', $page->refresh()->status);
    }

    public function test_setting_a_page_as_homepage_unsets_the_previous_homepage(): void
    {
        $originalHome = Page::factory()->homepage()->create();
        $newPage = Page::factory()->create();

        $this->actingAs($this->admin)->put("/admin/pages/{$newPage->id}", [
            'title' => $newPage->title,
            'status' => $newPage->status,
            'template' => $newPage->template,
            'is_homepage' => '1',
        ])->assertRedirect();

        $this->assertTrue($newPage->refresh()->is_homepage);
        $this->assertFalse($originalHome->refresh()->is_homepage);
    }

    public function test_duplicating_a_page_copies_its_sections_as_a_draft(): void
    {
        $page = Page::factory()->published()->create(['title' => 'Original']);
        PageSection::create([
            'page_id' => $page->id,
            'type' => 'divider',
            'settings' => ['style' => 'solid'],
            'position' => 0,
        ]);

        $response = $this->actingAs($this->admin)->post("/admin/pages/{$page->id}/duplicate");
        $response->assertRedirect();

        $copy = Page::where('title', 'Original (Copy)')->first();
        $this->assertNotNull($copy);
        $this->assertSame('draft', $copy->status);
        $this->assertFalse($copy->is_homepage);
        $this->assertSame(1, $copy->sections()->count());
    }

    public function test_a_previous_revision_can_be_restored(): void
    {
        $page = Page::factory()->create(['title' => 'Original Title']);

        $this->actingAs($this->admin)->put("/admin/pages/{$page->id}", [
            'title' => 'Changed Title',
            'status' => $page->status,
            'template' => $page->template,
        ])->assertRedirect();

        $this->assertSame('Changed Title', $page->refresh()->title);

        $revision = $page->revisions()->first();
        $this->assertNotNull($revision);

        $this->actingAs($this->admin)
            ->post("/admin/pages/{$page->id}/restore-revision/{$revision->id}")
            ->assertRedirect();

        $this->assertSame('Original Title', $page->refresh()->title);
    }
}
