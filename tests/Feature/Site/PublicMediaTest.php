<?php

namespace Tests\Feature\Site;

use App\Models\MediaAsset;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PublicMediaTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
        config(['ecozeen.media.disk' => 'public']);
    }

    public function test_a_public_media_url_is_host_relative_and_does_not_use_storage(): void
    {
        $asset = MediaAsset::factory()->create(['path' => 'pages/hero.jpg']);

        $this->assertSame('/media/pages/hero.jpg', $asset->url());
        $this->assertStringNotContainsString('/storage/', $asset->url());
    }

    public function test_uploaded_media_is_served_at_the_media_route(): void
    {
        Storage::disk('public')->put('pages/hero.jpg', 'fake-image-bytes');

        $response = $this->get('/media/pages/hero.jpg')->assertOk();

        $this->assertStringContainsString('max-age=604800', (string) $response->headers->get('Cache-Control'));
        $this->assertStringContainsString('immutable', (string) $response->headers->get('Cache-Control'));
    }

    public function test_path_traversal_in_media_urls_is_rejected(): void
    {
        $this->get('/media/../.env')->assertNotFound();
        $this->get('/media/%2e%2e/.env')->assertNotFound();
    }
}
