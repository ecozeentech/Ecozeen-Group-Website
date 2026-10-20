<x-layouts.public seo-title="Products &amp; Platforms" seo-description="Explore the digital products and platforms built across Ecozeen Group.">
    <section class="section-spacing">
        <div class="container-site">
            <div class="max-w-2xl">
                <h1 class="font-heading text-3xl font-bold text-ink sm:text-4xl">Products &amp; Platforms</h1>
                <p class="mt-3 text-ink-muted">Digital products and platforms built and operated by Ecozeen Group companies.</p>
            </div>

            @if ($categories->isNotEmpty())
                <form method="GET" class="mt-6 flex items-center gap-3">
                    <select name="category" class="form-select max-w-xs" onchange="this.form.submit()">
                        <option value="">All categories</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->slug }}" @selected(request('category') === $category->slug)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </form>
            @endif

            @if ($products->isEmpty())
                <p class="mt-10 text-ink-muted">No products are published yet. Check back soon.</p>
            @else
                <div class="mt-8 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($products as $product)
                        <a href="{{ route('products.show', $product) }}" class="card block p-6 transition hover:shadow-level2">
                            @if ($product->logoMedia)
                                <img src="{{ $product->logoMedia->url() }}" alt="{{ $product->name }}" class="h-10 w-auto">
                            @endif
                            <h2 class="mt-4 font-heading text-lg font-semibold text-ink">{{ $product->name }}</h2>
                            <p class="mt-1 text-xs font-medium uppercase tracking-wide text-brand-blue">{{ $product->tagline }}</p>
                            <p class="mt-2 text-sm text-ink-muted">{{ \Illuminate\Support\Str::limit($product->short_description, 110) }}</p>
                            @if ($product->company)
                                <p class="mt-3 text-xs text-ink-muted">By {{ $product->company->name }}</p>
                            @endif
                        </a>
                    @endforeach
                </div>
                <div class="mt-8">{{ $products->links() }}</div>
            @endif
        </div>
    </section>
</x-layouts.public>
