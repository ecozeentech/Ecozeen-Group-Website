<x-layouts.admin title="Dashboard" subtitle="Live content management and publishing hub">
    <div class="mb-6 flex flex-col justify-between gap-4 rounded-xl border border-surface-border bg-white p-6 shadow-level1 md:flex-row md:items-center">
        <div>
            <p class="mb-1 inline-flex items-center gap-2 text-[11px] font-bold uppercase tracking-wider text-brand-blue">
                <x-icon name="security" class="text-[16px]" />
                Enterprise governance portal
            </p>
            <h2 class="font-heading text-2xl font-extrabold text-ink">Welcome back, {{ auth()->user()->name }}</h2>
            <p class="mt-1 text-sm text-ink-muted">Real-time overview of Ecozeen Group domains, subsidiary catalogues, and digital platforms.</p>
        </div>
        <div class="flex flex-wrap items-center gap-2.5">
            @can('pages.view')
                <a href="{{ route('admin.pages.index') }}" class="btn-outline">
                    <x-icon name="view_quilt" class="text-brand-blue text-[18px]" />
                    Visual page builder
                </a>
            @endcan
            @can('companies.view')
                <a href="{{ route('admin.companies.index') }}" class="btn-outline">
                    <x-icon name="domain" class="text-brand-blue text-[18px]" />
                    Manage companies
                </a>
            @endcan
            @can('media.view')
                <a href="{{ route('admin.media.index') }}" class="btn-outline">
                    <x-icon name="photo_library" class="text-brand-blue text-[18px]" />
                    Media library
                </a>
            @endcan
            @can('settings.manage')
                <a href="{{ route('admin.settings.website.edit') }}" class="btn-outline">
                    <x-icon name="tune" class="text-brand-blue text-[18px]" />
                    Header &amp; footer
                </a>
            @endcan
            @can('pages.create')
                <a href="{{ route('admin.pages.create') }}" class="btn-secondary">
                    <x-icon name="add_circle" class="text-[18px]" />
                    New page
                </a>
            @endcan
        </div>
    </div>

    @canany(['navigation.manage', 'settings.manage', 'pages.view'])
        <section class="mb-6 grid grid-cols-1 gap-4 md:grid-cols-3">
            @can('navigation.manage')
                <a href="{{ route('admin.navigation.index') }}" class="card-hover p-5">
                    <p class="text-[11px] font-bold uppercase tracking-wider text-brand-blue">Site chrome</p>
                    <h3 class="mt-1 font-heading text-base font-semibold text-ink">Header, nav &amp; footer</h3>
                    <p class="mt-1 text-sm text-ink-muted">Rename links, reorder menus, and edit dropdowns without touching code.</p>
                </a>
            @endcan
            @can('settings.manage')
                <a href="{{ route('admin.settings.website.edit') }}" class="card-hover p-5">
                    <p class="text-[11px] font-bold uppercase tracking-wider text-brand-blue">Buttons &amp; copy</p>
                    <h3 class="mt-1 font-heading text-base font-semibold text-ink">Header CTAs &amp; footer text</h3>
                    <p class="mt-1 text-sm text-ink-muted">Change Partner With Us, Contact, logo, and the footer description.</p>
                </a>
            @endcan
            @can('pages.view')
                <a href="{{ route('admin.pages.index') }}" class="card-hover p-5">
                    <p class="text-[11px] font-bold uppercase tracking-wider text-brand-blue">Page canvas</p>
                    <h3 class="mt-1 font-heading text-base font-semibold text-ink">Edit or delete sections</h3>
                    <p class="mt-1 text-sm text-ink-muted">Every homepage and inner-page block has Edit and Delete in the visual editor.</p>
                </a>
            @endcan
        </section>
    @endcanany

    <section class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-5">
        <a href="{{ route('admin.pages.index') }}" class="kpi-stripe card p-4">
            <div class="mb-2 flex items-center justify-between text-[11px] font-semibold uppercase tracking-wider text-ink-muted">
                <span>Published pages</span>
                <x-icon name="pages" class="text-brand-blue" />
            </div>
            <p class="font-heading text-2xl font-bold text-ink">{{ $stats['pages_published'] }} <span class="text-sm font-normal text-ink-muted">live</span></p>
            <p class="mt-2 border-t border-surface-border pt-2 text-[11px] text-ink-muted">{{ $stats['pages'] - $stats['pages_published'] }} other statuses</p>
        </a>
        <a href="{{ route('admin.companies.index') }}" class="kpi-stripe card p-4">
            <div class="mb-2 flex items-center justify-between text-[11px] font-semibold uppercase tracking-wider text-ink-muted">
                <span>Group companies</span>
                <x-icon name="corporate_fare" class="text-brand-blue" />
            </div>
            <p class="font-heading text-2xl font-bold text-ink">{{ $stats['companies'] }} <span class="text-sm font-normal text-ink-muted">entities</span></p>
            <p class="mt-2 border-t border-surface-border pt-2 text-[11px] text-ink-muted">Live directory count</p>
        </a>
        <a href="{{ route('admin.products.index') }}" class="kpi-stripe card p-4">
            <div class="mb-2 flex items-center justify-between text-[11px] font-semibold uppercase tracking-wider text-ink-muted">
                <span>Products &amp; platforms</span>
                <x-icon name="hub" class="text-ink" />
            </div>
            <p class="font-heading text-2xl font-bold text-ink">{{ $stats['products'] }} <span class="text-sm font-normal text-ink-muted">platforms</span></p>
            <p class="mt-2 border-t border-surface-border pt-2 text-[11px] text-ink-muted">{{ $stats['services'] }} services</p>
        </a>
        <a href="{{ route('admin.inquiries.index') }}" class="kpi-stripe card p-4">
            <div class="mb-2 flex items-center justify-between text-[11px] font-semibold uppercase tracking-wider text-ink-muted">
                <span>Pending inquiries</span>
                <x-icon name="mark_email_unread" class="text-red-600" />
            </div>
            <p class="font-heading text-2xl font-bold text-ink">{{ $stats['new_inquiries'] }} <span class="text-sm font-normal text-ink-muted">awaiting</span></p>
            <p class="mt-2 border-t border-surface-border pt-2 text-[11px] text-ink-muted">{{ $stats['newsletter_subscribers'] }} newsletter subscribers</p>
        </a>
        <a href="{{ route('admin.blog-posts.index') }}" class="kpi-stripe card p-4">
            <div class="mb-2 flex items-center justify-between text-[11px] font-semibold uppercase tracking-wider text-ink-muted">
                <span>Published insights</span>
                <x-icon name="edit_note" class="text-emerald-600" />
            </div>
            <p class="font-heading text-2xl font-bold text-ink">{{ $stats['blog_posts_published'] }} <span class="text-sm font-normal text-ink-muted">/ {{ $stats['blog_posts'] }}</span></p>
            <p class="mt-2 border-t border-surface-border pt-2 text-[11px] text-ink-muted">{{ $stats['open_job_listings'] }} open jobs</p>
        </a>
    </section>

    <div class="mt-6 grid grid-cols-1 items-start gap-6 lg:grid-cols-12">
        <div class="card flex flex-col lg:col-span-7">
            <div class="flex flex-col justify-between gap-4 border-b border-surface-border p-5 sm:flex-row sm:items-center">
                <div>
                    <h2 class="flex items-center gap-2 font-heading text-lg font-bold text-ink">Live content management</h2>
                    <p class="text-sm text-ink-muted">Recently updated pages, catalogues, and institutional landings</p>
                </div>
                <a href="{{ route('admin.pages.index') }}" class="text-xs font-semibold text-brand-blue hover:underline">View all pages</a>
            </div>
            <div class="overflow-x-auto">
                @if ($recentPages->isEmpty())
                    <p class="p-5 text-sm text-ink-muted">No pages yet. Create the first page to start the public website.</p>
                @else
                    <table class="admin-table w-full">
                        <thead>
                            <tr>
                                <th>Name &amp; slug</th>
                                <th>Status</th>
                                <th>Last modified</th>
                                <th class="text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($recentPages as $page)
                                <tr>
                                    <td>
                                        <p class="font-medium">{{ $page->title }}</p>
                                        <p class="text-xs text-ink-muted">/{{ $page->is_homepage ? '' : $page->slug }}</p>
                                    </td>
                                    <td><x-admin.status-badge :status="$page->status" /></td>
                                    <td class="text-ink-muted">{{ $page->updated_at?->diffForHumans() }}</td>
                                    <td class="text-right">
                                        <a href="{{ route('admin.pages.edit', $page) }}" class="btn-outline">Edit</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>

        <div class="space-y-6 lg:col-span-5">
            <div class="card p-6">
                <div class="flex items-center justify-between">
                    <h2 class="flex items-center gap-2 font-heading text-sm font-semibold text-ink">
                        <x-icon name="contact_support" class="text-brand-blue text-[18px]" />
                        Recent inquiries
                    </h2>
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
                    <h2 class="flex items-center gap-2 font-heading text-sm font-semibold text-ink">
                        <x-icon name="history" class="text-brand-blue text-[18px]" />
                        Recent activity
                    </h2>
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
    </div>
</x-layouts.admin>
