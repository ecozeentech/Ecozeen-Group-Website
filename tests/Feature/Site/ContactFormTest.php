<?php

namespace Tests\Feature\Site;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ContactFormTest extends TestCase
{
    use RefreshDatabase;

    private function validPayload(array $overrides = []): array
    {
        return array_merge([
            'inquiry_type' => 'general',
            'full_name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'message' => 'I would like to learn more about Ecozeen Group.',
            'consent' => '1',
            'website' => '',
        ], $overrides);
    }

    public function test_the_contact_page_renders(): void
    {
        $this->get('/contact')->assertOk();
    }

    public function test_a_valid_contact_submission_is_stored(): void
    {
        Mail::fake();

        $this->post('/contact', $this->validPayload())
            ->assertRedirect()
            ->assertSessionHas('status');

        $this->assertDatabaseHas('contact_inquiries', [
            'email' => 'jane@example.com',
            'full_name' => 'Jane Doe',
        ]);
    }

    public function test_the_submission_is_rejected_without_consent(): void
    {
        $this->post('/contact', $this->validPayload(['consent' => null]))
            ->assertSessionHasErrors('consent');

        $this->assertDatabaseMissing('contact_inquiries', ['email' => 'jane@example.com']);
    }

    public function test_the_honeypot_field_silently_rejects_automated_submissions(): void
    {
        $this->post('/contact', $this->validPayload(['website' => 'http://spambot.example']))
            ->assertSessionHasErrors('website');

        $this->assertDatabaseMissing('contact_inquiries', ['email' => 'jane@example.com']);
    }

    public function test_the_message_field_is_required(): void
    {
        $this->post('/contact', $this->validPayload(['message' => '']))
            ->assertSessionHasErrors('message');
    }

    public function test_an_invalid_inquiry_type_is_rejected(): void
    {
        $this->post('/contact', $this->validPayload(['inquiry_type' => 'not_a_real_type']))
            ->assertSessionHasErrors('inquiry_type');
    }
}
