<x-layouts.public
    :seo-title="$page->seoTitle()"
    :seo-description="$page->seoDescription()"
    :seo-image="$page->seoImageMedia?->url()"
    :seo-canonical="$page->canonical_url"
    :no-index="$page->no_index"
    :structured-data="[
        '@context' => 'https://schema.org',
        '@type' => 'WebPage',
        'name' => $page->title,
        'url' => url($page->is_homepage ? '/' : $page->slug),
    ]"
>
    @foreach ($page->sections as $section)
        @continue(! $section->is_visible)
        @php($visibilityClasses = trim(($section->visible_desktop ? '' : 'hidden lg:!hidden ').($section->visible_mobile ? '' : 'lg:block hidden')))
        <div class="{{ $visibilityClasses }}">
            @includeFirst(['site.sections.'.$section->type, 'site.sections.custom_content'], ['settings' => $section->effectiveSettings()])
        </div>
    @endforeach
</x-layouts.public>
