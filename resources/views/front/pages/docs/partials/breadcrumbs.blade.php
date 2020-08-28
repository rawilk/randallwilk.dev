<section id="breadcrumb" class="hidden md:block py-4 md:py-6 lg:py-8">
    <div class="wrap">
        <div class="mt-4">
            <a href="{!! route('docs') !!}" class="link-underline link-black">Docs</a>
            <span class="icon mx-2 opacity-50 fill-current text-black">
                <x-heroicon-s-chevron-right />
            </span>

            <a class="link-underline link-black"
               href="{{ action([\App\Http\Controllers\DocsController::class, 'repository'], [$repository->slug, $alias->slug]) }}"
            >
                {{ $repository->slug }}
            </a>

            @if (! $page->isRootPage())
                <span class="icon mx-2 opacity-50 fill-current text-black">
                    <x-heroicon-s-chevron-right />
                </span>

                <span>{{ ucfirst($page->section) }}</span>
            @endif

            <span class="icon mx-2 opacity-50 fill-current text-black">
                <x-heroicon-s-chevron-right />
            </span>

            <span>{{ $page->title }}</span>
        </div>
    </div>
</section>
