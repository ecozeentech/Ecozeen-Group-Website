<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Http\Requests\Site\NewsletterSubscribeRequest;
use App\Mail\NewsletterWelcomeMail;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\View\View;

class NewsletterController extends Controller
{
    public function subscribe(NewsletterSubscribeRequest $request): RedirectResponse
    {
        $data = $request->validated();
        unset($data['website'], $data['consent']);

        $subscriber = NewsletterSubscriber::updateOrCreate(
            ['email' => $data['email']],
            [
                'name' => $data['name'] ?? null,
                'status' => 'subscribed',
                'unsubscribe_token' => NewsletterSubscriber::where('email', $data['email'])->value('unsubscribe_token') ?: Str::random(48),
                'consent_at' => now(),
                'subscribed_at' => now(),
                'unsubscribed_at' => null,
                'source' => 'website',
                'ip_address' => $request->ip(),
            ]
        );

        try {
            Mail::to($subscriber->email)->send(new NewsletterWelcomeMail($subscriber));
        } catch (\Throwable $e) {
            Log::error('Failed to send newsletter welcome email: '.$e->getMessage());
        }

        return back()->with('status', 'You are subscribed. Thank you for joining the Ecozeen Group mailing list.');
    }

    public function unsubscribe(string $token): View
    {
        $subscriber = NewsletterSubscriber::where('unsubscribe_token', $token)->firstOrFail();
        $subscriber->update(['status' => 'unsubscribed', 'unsubscribed_at' => now()]);

        return view('site.newsletter-unsubscribed', compact('subscriber'));
    }
}
