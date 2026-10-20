@php
    $groups = [
        'Overview' => [
            ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'icon' => 'home'],
            ['label' => 'Activity Log', 'route' => 'admin.activity.index', 'icon' => 'clock', 'permission' => 'activity.view'],
        ],
        'Website Content' => [
            ['label' => 'Pages', 'route' => 'admin.pages.index', 'icon' => 'document', 'permission' => 'pages.view'],
            ['label' => 'Navigation Menus', 'route' => 'admin.navigation.index', 'icon' => 'menu', 'permission' => 'navigation.manage'],
            ['label' => 'Media Library', 'route' => 'admin.media.index', 'icon' => 'image', 'permission' => 'media.view'],
            ['label' => 'Redirects', 'route' => 'admin.redirects.index', 'icon' => 'arrow', 'permission' => 'redirects.manage'],
        ],
        'Business Directory' => [
            ['label' => 'Companies', 'route' => 'admin.companies.index', 'icon' => 'building', 'permission' => 'companies.view'],
            ['label' => 'Products & Platforms', 'route' => 'admin.products.index', 'icon' => 'cube', 'permission' => 'products.view'],
            ['label' => 'Services', 'route' => 'admin.services.index', 'icon' => 'briefcase', 'permission' => 'services.view'],
            ['label' => 'Portfolio Projects', 'route' => 'admin.projects.index', 'icon' => 'folder', 'permission' => 'projects.view'],
        ],
        'Publishing' => [
            ['label' => 'Blog & Articles', 'route' => 'admin.blog-posts.index', 'icon' => 'newspaper', 'permission' => 'blog.view'],
            ['label' => 'Job Listings', 'route' => 'admin.job-listings.index', 'icon' => 'identification', 'permission' => 'jobs.view'],
        ],
        'Inbound' => [
            ['label' => 'Inquiries', 'route' => 'admin.inquiries.index', 'icon' => 'inbox', 'permission' => 'inquiries.view'],
            ['label' => 'Newsletter', 'route' => 'admin.newsletter.index', 'icon' => 'mail', 'permission' => 'newsletter.view'],
            ['label' => 'Job Applications', 'route' => 'admin.job-applications.index', 'icon' => 'document-text', 'permission' => 'jobs.view'],
        ],
        'Configuration' => [
            ['label' => 'Website Settings', 'route' => 'admin.settings.website.edit', 'icon' => 'cog', 'permission' => 'settings.manage'],
            ['label' => 'Theme & Design', 'route' => 'admin.settings.theme.edit', 'icon' => 'swatch', 'permission' => 'theme.manage'],
            ['label' => 'Users', 'route' => 'admin.users.index', 'icon' => 'users', 'permission' => 'users.manage'],
            ['label' => 'Roles & Permissions', 'route' => 'admin.roles.index', 'icon' => 'shield', 'permission' => 'roles.manage'],
        ],
    ];
@endphp
<aside class="hidden w-[260px] shrink-0 flex-col bg-brand-navy text-slate-200 lg:flex" aria-label="Admin navigation">
    <div class="flex h-16 items-center gap-2 border-b border-white/10 px-5">
        <x-brand.logo-mark class="h-8 w-8 brightness-0 invert" />
        <div>
            <span class="block font-heading text-sm font-bold text-white">Ecozeen Admin</span>
            <span class="block text-[10px] uppercase tracking-wider text-slate-400">Enterprise CMS</span>
        </div>
    </div>

    <nav class="flex-1 overflow-y-auto px-3 py-4">
        @foreach ($groups as $groupLabel => $items)
            @php
                $visibleItems = collect($items)->filter(fn ($item) => empty($item['permission']) || auth()->user()?->can($item['permission']));
            @endphp
            @if ($visibleItems->isNotEmpty())
                <p class="mb-2 mt-5 px-3 text-xs font-semibold uppercase tracking-wider text-slate-500 first:mt-0">{{ $groupLabel }}</p>
                <div class="space-y-0.5">
                    @foreach ($visibleItems as $item)
                        <a href="{{ route($item['route']) }}" class="admin-nav-link {{ request()->routeIs($item['route'].'*') ? 'active' : '' }}">
                            <span>{{ $item['label'] }}</span>
                        </a>
                    @endforeach
                </div>
            @endif
        @endforeach
    </nav>

    <div class="border-t border-white/10 p-4 text-xs text-slate-400">
        Signed in as<br>
        <span class="font-medium text-slate-200">{{ auth()->user()->name }}</span>
    </div>
</aside>
