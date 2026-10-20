<?php

namespace Tests\Feature\Site;

use App\Models\NewsletterSubscriber;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class NewsletterTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_valid_subscription_is_stored_with_subscribed_status(): void
    {
        Mail::fake();

        $this->post('/newsletter/subscribe', [
            'email' => 'subscriber@example.com',
            'name' => 'Alex Subscriber',
            'consent' => '1',
            'website' => '',
        ])->assertRedirect()->assertSessionHas('status');

        $this->assertDatabaseHas('newsletter_subscribers', [
            'email' => 'subscriber@example.com',
            'status' => 'subscribed',
        ]);
    }

    public function test_subscribing_twice_with_the_same_email_updates_rather_than_duplicates(): void
    {
        Mail::fake();

        $this->post('/newsletter/subscribe', [
            'email' => 'repeat@example.com',
            'consent' => '1',
            'website' => '',
        ]);

        $this->post('/newsletter/subscribe', [
            'email' => 'repeat@example.com',
            'consent' => '1',
            'website' => '',
        ]);

        $this->assertSame(1, NewsletterSubscriber::where('email', 'repeat@example.com')->count());
    }

    public function test_subscribing_without_consent_is_rejected(): void
    {
        $this->post('/newsletter/subscribe', [
            'email' => 'noconsent@example.com',
            'consent' => null,
            'website' => '',
        ])->assertSessionHasErrors('consent');

        $this->assertDatabaseMissing('newsletter_subscribers', ['email' => 'noconsent@example.com']);
    }

    public function test_the_honeypot_field_rejects_automated_subscriptions(): void
    {
        $this->post('/newsletter/subscribe', [
            'email' => 'bot@example.com',
            'consent' => '1',
            'website' => 'http://spambot.example',
        ])->assertSessionHasErrors('website');

        $this->assertDatabaseMissing('newsletter_subscribers', ['email' => 'bot@example.com']);
    }

    public function test_a_subscriber_can_unsubscribe_via_their_token(): void
    {
        $subscriber = NewsletterSubscriber::create([
            'email' => 'unsub@example.com',
            'status' => 'subscribed',
            'unsubscribe_token' => 'test-token-123',
            'consent_at' => now(),
            'subscribed_at' => now(),
        ]);

        $this->get('/newsletter/unsubscribe/test-token-123')->assertOk();

        $this->assertSame('unsubscribed', $subscriber->refresh()->status);
    }
}
