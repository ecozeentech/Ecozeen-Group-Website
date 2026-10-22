@props(['variant' => 'desktop'])
@php
    $inquiryCount = \App\Models\ContactInquiry::query()->where('status', 'new')->count();
    $groups = [
        'Overview' => [
            ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'icon' => 'dashboard'],
            ['label' => 'Activity Log', 'route' => 'admin.activity.index', 'icon' => 'history', 'permission' => 'activity.view'],
        ],
        'Website Content' => [
            ['label' => 'Visual Editor', 'route' => 'admin.pages.index', 'icon' => 'view_quilt', 'permission' => 'pages.view'],
            ['label' => 'Navigation Menus', 'route' => 'admin.navigation.index', 'icon' => 'segment', 'permission' => 'navigation.manage'],
            ['label' => 'Media Library', 'route' => 'admin.media.index', 'icon' => 'photo_library', 'permission' => 'media.view'],
            ['label' => 'Redirects', 'route' => 'admin.redirects.index', 'icon' => 'alt_route', 'permission' => 'redirects.manage'],
        ],
        'Business Directory' => [
            ['label' => 'Companies', 'route' => 'admin.companies.index', 'icon' => 'domain', 'permission' => 'companies.view'],
            ['label' => 'Products & Platforms', 'route' => 'admin.products.index', 'icon' => 'devices', 'permission' => 'products.view'],
            ['label' => 'Services', 'route' => 'admin.services.index', 'icon' => 'work_outline', 'permission' => 'services.view'],
            ['label' => 'Portfolio Projects', 'route' => 'admin.projects.index', 'icon' => 'folder', 'permission' => 'projects.view'],
        ],
        'Publishing' => [
            ['label' => 'Blog & Articles', 'route' => 'admin.blog-posts.index', 'icon' => 'edit_note', 'permission' => 'blog.view'],
            ['label' => 'Job Listings', 'route' => 'admin.job-listings.index', 'icon' => 'badge', 'permission' => 'jobs.view'],
        ],
        'Operations' => [
            ['label' => 'Inquiries', 'route' => 'admin.inquiries.index', 'icon' => 'contact_support', 'permission' => 'inquiries.view', 'badge' => $inquiryCount],
            ['label' => 'Newsletter', 'route' => 'admin.newsletter.index', 'icon' => 'mail', 'permission' => 'newsletter.view'],
            ['label' => 'Job Applications', 'route' => 'admin.job-applications.index', 'icon' => 'description', 'permission' => 'jobs.view'],
        ],
        'Configuration' => [
            ['label' => 'Website Settings', 'route' => 'admin.settings.website.edit', 'icon' => 'settings_suggest', 'permission' => 'settings.manage'],
            ['label' => 'Theme & Design', 'route' => 'admin.settings.theme.edit', 'icon' => 'palette', 'permission' => 'theme.manage'],
            ['label' => 'Users', 'route' => 'admin.users.index', 'icon' => 'group', 'permission' => 'users.manage'],
            ['label' => 'Roles & Permissions', 'route' => 'admin.roles.index', 'icon' => 'verified_user', 'permission' => 'roles.manage'],
        ],
    ];
@endphp
<aside
    {{ $attributes->merge([
        'class' => $variant === 'mobile'
            ? 'admin-sidebar-mobile flex h-full min-h-0 w-full flex-col border-r border-surface-border bg-white'
            : 'admin-sidebar-desktop hidden w-64 shrink-0 flex-col border-r border-surface-border bg-white lg:flex',
    ]) }}
    aria-label="Admin navigation"
>
    <div class="flex h-16 items-center justify-between gap-3 border-b border-surface-border px-5">
        <div class="flex min-w-0 items-center gap-3">
            <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-brand-navy p-1">
                <x-brand.logo-mark class="h-7 w-7 brightness-0 invert" />
            </span>
            <div>
                <span class="block font-heading text-sm font-bold text-ink">Ecozeen Admin</span>
                <span class="block text-[10px] font-medium uppercase tracking-wider text-ink-muted">Enterprise CMS</span>
            </div>
        </div>
        @if ($variant === 'mobile')
            <button type="button" class="rounded-token p-1.5 text-ink hover:bg-slate-50" @click="sidebarOpen = false" aria-label="Close admin menu">
                <x-icon name="close" />
            </button>
        @endif
    </div>

    @can('pages.create')
        <div class="px-4 pt-4">
            <a href="{{ route('admin.pages.create') }}" class="btn-primary w-full">
                <x-icon name="add_circle" class="text-[18px]" />
                New Page
            </a>
        </div>
    @endcan

    <nav class="flex-1 overflow-y-auto px-3 py-4">
        @foreach ($groups as $groupLabel => $items)
            @php
                $visibleItems = collect($items)->filter(fn ($item) => empty($item['permission']) || auth()->user()?->can($item['permission']));
            @endphp
            @if ($visibleItems->isNotEmpty())
                <p class="mb-1.5 mt-5 px-3 text-[11px] font-semibold uppercase tracking-wider text-ink-muted first:mt-0">{{ $groupLabel }}</p>
                <div class="space-y-0.5">
                    @foreach ($visibleItems as $item)
                        <a href="{{ route($item['route']) }}" class="admin-nav-link {{ request()->routeIs($item['route'].'*') ? 'active' : '' }}">
                            <x-icon :name="$item['icon']" :filled="request()->routeIs($item['route'].'*')" class="text-[18px]" />
                            <span class="flex-1">{{ $item['label'] }}</span>
                            @if (! empty($item['badge']))
                                <span class="rounded-full bg-red-100 px-1.5 py-0.5 text-[10px] font-semibold text-red-700">{{ $item['badge'] }}</span>
                            @endif
                        </a>
                    @endforeach
                </div>
            @endif
        @endforeach
    </nav>

    <div class="border-t border-surface-border p-4">
        <div class="flex items-center gap-2 rounded-lg bg-slate-50 p-2">
            <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-brand-navy text-[11px] font-bold text-white">
                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
            </span>
            <div class="min-w-0">
                <p class="truncate text-xs font-semibold text-ink">{{ auth()->user()->name }}</p>
                <p class="truncate text-[10px] text-ink-muted">{{ auth()->user()->email }}</p>
            </div>
        </div>
    </div>
</aside>
