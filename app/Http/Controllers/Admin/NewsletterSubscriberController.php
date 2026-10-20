<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class NewsletterSubscriberController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(NewsletterSubscriber::class, 'newsletter', ['except' => ['show']]);
    }

    public function index(): View
    {
        $this->authorize('viewAny', NewsletterSubscriber::class);

        $subscribers = NewsletterSubscriber::query()
            ->when(request('status'), fn ($q) => $q->where('status', request('status')))
            ->when(request('q'), fn ($q) => $q->where('email', 'like', '%'.request('q').'%'))
            ->latest('subscribed_at')
            ->paginate(30)
            ->withQueryString();

        return view('admin.newsletter.index', compact('subscribers'));
    }

    public function update(Request $request, NewsletterSubscriber $newsletter): RedirectResponse
    {
        $this->authorize('update', $newsletter);

        $data = $request->validate([
            'status' => ['required', Rule::in(['subscribed', 'unsubscribed'])],
        ]);

        $data['unsubscribed_at'] = $data['status'] === 'unsubscribed' ? now() : null;

        $newsletter->update($data);

        return back()->with('success', 'Subscriber updated.');
    }

    public function destroy(NewsletterSubscriber $newsletter): RedirectResponse
    {
        $this->authorize('delete', $newsletter);

        $newsletter->delete();

        return redirect()->route('admin.newsletter.index')->with('success', 'Subscriber removed.');
    }
}
