<?php

namespace Tests\Feature\Admin;

use App\Models\MediaAsset;
use App\Models\Page;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MediaUploadTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
        config(['ecozeen.media.disk' => 'public']);

        $this->seed(RolesAndPermissionsSeeder::class);

        $this->admin = User::factory()->create();
        $this->admin->assignRole('Super Administrator');
    }

    public function test_an_administrator_can_upload_a_jpeg_to_the_media_library(): void
    {
        $file = UploadedFile::fake()->image('hero.jpg', 640, 480);

        $this->actingAs($this->admin)
            ->post('/admin/media', [
                'files' => [$file],
                'collection' => 'pages',
            ])
            ->assertRedirect(route('admin.media.index'))
            ->assertSessionHas('success')
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('media_assets', [
            'original_filename' => 'hero.jpg',
            'mime_type' => 'image/jpeg',
            'extension' => 'jpg',
            'collection' => 'pages',
            'width' => 640,
            'height' => 480,
        ]);

        $asset = MediaAsset::query()->first();
        $this->assertNotNull($asset);
        Storage::disk('public')->assertExists($asset->path);
    }

    public function test_an_administrator_can_upload_a_png_to_the_media_library(): void
    {
        $file = UploadedFile::fake()->image('mark.png', 200, 200);

        $this->actingAs($this->admin)
            ->post('/admin/media', ['files' => [$file]])
            ->assertRedirect(route('admin.media.index'))
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('media_assets', [
            'original_filename' => 'mark.png',
            'mime_type' => 'image/png',
            'extension' => 'png',
        ]);
    }

    public function test_page_seo_image_uploads_are_stored_and_attached(): void
    {
        $page = Page::factory()->create([
            'title' => 'About',
            'template' => 'default',
            'status' => 'draft',
        ]);

        $this->actingAs($this->admin)
            ->put("/admin/pages/{$page->id}", [
                'title' => 'About',
                'template' => 'default',
                'status' => 'draft',
                'seo_image_media_id_upload' => UploadedFile::fake()->image('og.jpg', 1200, 630),
            ])
            ->assertRedirect()
            ->assertSessionHasNoErrors();

        $page->refresh();
        $this->assertNotNull($page->seo_image_media_id);
        $this->assertDatabaseHas('media_assets', [
            'id' => $page->seo_image_media_id,
            'collection' => 'seo',
            'mime_type' => 'image/jpeg',
        ]);
    }

    public function test_hero_section_image_uploads_are_stored_in_section_settings(): void
    {
        $page = Page::factory()->create();
        $section = $page->sections()->create([
            'type' => 'hero',
            'settings' => ['heading' => 'Welcome'],
            'position' => 0,
        ]);

        $this->actingAs($this->admin)
            ->put("/admin/pages/{$page->id}/sections/{$section->id}", [
                'is_visible' => '1',
                'visible_desktop' => '1',
                'visible_mobile' => '1',
                'settings' => ['heading' => 'Welcome'],
                'image_media_id_upload' => UploadedFile::fake()->image('hero-banner.jpg', 800, 400),
            ])
            ->assertRedirect()
            ->assertSessionHasNoErrors();

        $settings = $section->fresh()->settings;
        $this->assertNotEmpty($settings['image_media_id']);
        $this->assertDatabaseHas('media_assets', [
            'id' => $settings['image_media_id'],
            'collection' => 'pages',
        ]);
    }

    public function test_disallowed_file_types_are_rejected_without_a_500(): void
    {
        $file = UploadedFile::fake()->createWithContent('payload.php', '<?php echo 1;');

        $this->actingAs($this->admin)
            ->from('/admin/media')
            ->post('/admin/media', ['files' => [$file]])
            ->assertRedirect('/admin/media')
            ->assertSessionHasErrors();

        $this->assertSame(0, MediaAsset::query()->count());
    }
}
