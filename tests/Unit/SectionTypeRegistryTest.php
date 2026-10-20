<?php

namespace Tests\Unit;

use App\Support\PageBuilder\SectionTypeRegistry;
use Tests\TestCase;

class SectionTypeRegistryTest extends TestCase
{
    public function test_it_exposes_a_non_empty_list_of_known_types(): void
    {
        $types = SectionTypeRegistry::types();

        $this->assertNotEmpty($types);
        $this->assertContains('hero', $types);
        $this->assertTrue(SectionTypeRegistry::isValidType('hero'));
        $this->assertFalse(SectionTypeRegistry::isValidType('not_a_real_type'));
    }

    public function test_sanitize_strips_unknown_fields(): void
    {
        $clean = SectionTypeRegistry::sanitize('hero', [
            'heading' => 'Welcome',
            'not_declared_for_hero' => 'should be dropped',
        ]);

        $this->assertSame('Welcome', $clean['heading']);
        $this->assertArrayNotHasKey('not_declared_for_hero', $clean);
    }

    public function test_sanitize_returns_an_empty_array_for_an_unknown_type(): void
    {
        $this->assertSame([], SectionTypeRegistry::sanitize('not_a_real_type', ['heading' => 'x']));
    }

    public function test_sanitize_strips_tags_from_plain_string_fields(): void
    {
        $clean = SectionTypeRegistry::sanitize('hero', [
            'heading' => '<script>alert(1)</script>Hello',
        ]);

        $this->assertSame('alert(1)Hello', $clean['heading']);
    }

    public function test_sanitize_purifies_richtext_fields_but_keeps_safe_markup(): void
    {
        $clean = SectionTypeRegistry::sanitize('rich_text', [
            'content' => '<p>Hello</p><script>alert("xss")</script>',
        ]);

        $this->assertStringNotContainsString('<script>', $clean['content']);
        $this->assertStringContainsString('<p>Hello</p>', $clean['content']);
    }

    public function test_sanitize_coerces_bool_and_int_fields(): void
    {
        $clean = SectionTypeRegistry::sanitize('product_collection', [
            'featured_only' => '1',
            'limit' => '6',
        ]);

        $this->assertTrue($clean['featured_only']);
        $this->assertSame(6, $clean['limit']);
    }

    public function test_sanitize_rejects_non_numeric_int_fields(): void
    {
        $clean = SectionTypeRegistry::sanitize('product_collection', [
            'limit' => 'not-a-number',
        ]);

        $this->assertNull($clean['limit']);
    }

    public function test_sanitize_url_accepts_relative_paths_and_absolute_http_urls(): void
    {
        $clean = SectionTypeRegistry::sanitize('hero', [
            'primary_url' => '/contact',
        ]);
        $this->assertSame('/contact', $clean['primary_url']);

        $clean = SectionTypeRegistry::sanitize('hero', [
            'primary_url' => 'https://example.com/path',
        ]);
        $this->assertSame('https://example.com/path', $clean['primary_url']);
    }

    public function test_sanitize_url_rejects_javascript_and_other_dangerous_schemes(): void
    {
        $clean = SectionTypeRegistry::sanitize('hero', [
            'primary_url' => 'javascript:alert(1)',
        ]);

        $this->assertNull($clean['primary_url']);
    }

    public function test_sanitize_list_strips_html_from_nested_scalar_values(): void
    {
        $clean = SectionTypeRegistry::sanitize('faq', [
            'items' => [
                ['question' => '<b>Q1</b>', 'answer' => '<script>bad()</script>A1'],
            ],
        ]);

        $this->assertSame('Q1', $clean['items'][0]['question']);
        $this->assertSame('bad()A1', $clean['items'][0]['answer']);
    }

    public function test_sanitize_list_returns_an_empty_array_for_non_array_input(): void
    {
        $clean = SectionTypeRegistry::sanitize('faq', ['items' => 'not-a-list']);

        $this->assertSame([], $clean['items']);
    }

    public function test_video_embed_only_permits_known_providers(): void
    {
        $clean = SectionTypeRegistry::sanitize('video_embed', [
            'provider' => 'youtube',
            'video_id' => 'abc123_-XYZ',
        ]);

        $this->assertSame('youtube', $clean['provider']);
        $this->assertSame('abc123_-XYZ', $clean['video_id']);

        $clean = SectionTypeRegistry::sanitize('video_embed', [
            'provider' => 'evil-cdn',
            'video_id' => 'abc123',
        ]);

        $this->assertNull($clean['provider']);
    }

    public function test_video_embed_strips_dangerous_characters_from_the_video_id(): void
    {
        $clean = SectionTypeRegistry::sanitize('video_embed', [
            'provider' => 'vimeo',
            'video_id' => '123"><script>alert(1)</script>',
        ]);

        $this->assertSame('123alert1', $clean['video_id']);
    }
}
