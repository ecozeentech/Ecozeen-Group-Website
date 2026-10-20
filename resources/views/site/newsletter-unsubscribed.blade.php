<x-layouts.public seo-title="Unsubscribed" no-index="true">
    <section class="section-spacing">
        <div class="container-site max-w-xl text-center">
            <h1 class="font-heading text-2xl font-bold text-ink">You've been unsubscribed</h1>
            <p class="mt-3 text-ink-muted">{{ $subscriber->email }} will no longer receive emails from Ecozeen Group. You can resubscribe at any time from our website footer.</p>
            <a href="{{ route('home') }}" class="btn-primary mt-6 inline-block">Back to Homepage</a>
        </div>
    </section>
</x-layouts.public>
