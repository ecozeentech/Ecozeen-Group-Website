<x-layouts.admin title="Dashboard" subtitle="Live content management and publishing hub">
    <div class="mb-8 flex flex-col justify-between gap-4 lg:flex-row lg:items-end">
        <div>
            <p class="text-eyebrow">Analytics overview</p>
            <h2 class="mt-2 font-heading text-2xl font-bold text-ink">Welcome back, {{ auth()->user()->name }}</h2>
            <p class="mt-1 text-sm text-ink-muted">Real-time overview of the Ecozeen Group website and CMS.</p>
        </div>
        @can('pages.create')
            <a href="{{ route('admin.pages.create') }}" class="btn-secondary">+ New Page</a>
        @endcan
    </div>

    <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4">
        @foreach ([
            ['label' => 'Companies', 'value' => $stats['companies'], 'route' => 'admin.companies.index'],
            ['label' => 'Products', 'value' => $stats['products'], 'route' => 'admin.products.index'],
            ['label' => 'Services', 'value' => $stats['services'], 'route' => 'admin.services.index'],
            ['label' => 'Portfolio Projects', 'value' => $stats['projects'], 'route' => 'admin.projects.index'],
            ['label' => 'Pages Published', 'value' => $stats['pages_published'].' / '.$stats['pages'], 'route' => 'admin.pages.index'],
            ['label' => 'Blog Posts Published', 'value' => $stats['blog_posts_published'].' / '.$stats['blog_posts'], 'route' => 'admin.blog-posts.index'],
            ['label' => 'Media Assets', 'value' => $stats['media_assets'], 'route' => 'admin.media.index'],
            ['label' => 'Open Job Listings', 'value' => $stats['open_job_listings'], 'route' => 'admin.job-listings.index'],
            ['label' => 'New Applications', 'value' => $stats['new_job_applications'], 'route' => 'admin.job-applications.index'],
            ['label' => 'New Inquiries', 'value' => $stats['new_inquiries'], 'route' => 'admin.inquiries.index'],
            ['label' => 'Newsletter Subscribers', 'value' => $stats['newsletter_subscribers'], 'route' => 'admin.newsletter.index'],
        ] as $card)
            <a href="{{ route($card['route']) }}" class="card-accent p-5">
                <p class="text-[11px] font-semibold uppercase tracking-wider text-ink-muted">{{ $card['label'] }}</p>
                <p class="mt-2 font-heading text-2xl font-bold text-ink">{{ $card['value'] }}</p>
            </a>
        @endforeach
    </div>

    <div class="mt-8 grid grid-cols-1 gap-6 lg:grid-cols-2">
        <div class="card p-6">
            <div class="flex items-center justify-between">
                <h2 class="font-heading text-sm font-semibold text-ink">Recent Inquiries</h2>
                <a href="{{ route('admin.inquiries.index') }}" class="text-xs font-semibold text-brand-blue hover:underline">View all</a>
            </div>
            @if ($recentInquiries->isEmpty())
                <p class="mt-3 text-sm text-ink-muted">No inquiries yet.</p>
            @else
                <div class="mt-3 divide-y divide-surface-border">
                    @foreach ($recentInquiries as $inquiry)
                        <a href="{{ route('admin.inquiries.show', $inquiry) }}" class="flex items-center justify-between gap-3 py-3 text-sm hover:text-brand-blue">
                            <span>{{ $inquiry->full_name }} &mdash; {{ \Illuminate\Support\Str::headline($inquiry->inquiry_type) }}</span>
                            <x-admin.status-badge :status="$inquiry->status" />
                        </a>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="card p-6">
            <div class="flex items-center justify-between">
                <h2 class="font-heading text-sm font-semibold text-ink">Recent Activity</h2>
                @can('activity.view')
                    <a href="{{ route('admin.activity.index') }}" class="text-xs font-semibold text-brand-blue hover:underline">Audit log</a>
                @endcan
            </div>
            @if ($recentActivity->isEmpty())
                <p class="mt-3 text-sm text-ink-muted">No activity recorded yet.</p>
            @else
                <ul class="mt-3 space-y-3">
                    @foreach ($recentActivity as $log)
                        <li class="text-sm text-ink">
                            <span class="font-medium">{{ $log->user?->name ?? 'System' }}</span>
                            {{ $log->description }}
                            <span class="block text-xs text-ink-muted">{{ $log->created_at?->diffForHumans() }}</span>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</x-layouts.admin>
