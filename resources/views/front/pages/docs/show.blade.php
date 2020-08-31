@php /** @var \App\Docs\Repository $repository */ @endphp

<x-page title="{{ $page->title }} | {{ $repository->slug }}"
        description="{{ $repository->slug }}"
>
    @push('head')
        <meta name="docsearch:version" content="{{ $alias->slug }}">
        <meta name="docsearch:project" content="{{ $repository->slug }}">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/docsearch.js@2/dist/cdn/docsearch.min.css">
    @endpush

    @include('front.pages.docs.partials.breadcrumbs')

    <section class="wrap grid pb-24 gap-8 md:grid-cols-3 items-stretch">
        <div class="z-10 print:hidden">
            @include('front.pages.docs.partials.navigation')
        </div>

        <div class="md:col-span-2">
            @if ($showBigTitle)
                <div class="mb-16">
                    <h1 class="banner-slogan">{{ $repository->slug }}</h1>
                    <div class="banner-intro flex items-center justify-start">
                        {{ $alias->slogan }}
                    </div>
                </div>

                <h2 class="title-xl mb-8">{{ $page->title }}</h2>
            @else
                <h1 class="title-xl mb-8">{{ $page->title }}</h1>
            @endif

            <div class="markup markup-titles markup-lists markup-code links-black links-underline no-title-uppercase {{ str_replace('/', '-', $page->slug) }}">
                {!! $page->contents !!}
            </div>

            <div class="pt-8 pb-6 mt-12 border-t-2">
                <x-doc-footer-links :previous="$alias->previousPage()" :next="$alias->nextPage()" />
            </div>
        </div>
    </section>

    @push('js')
        <script src="https://cdn.jsdelivr.net/npm/docsearch.js@2/dist/cdn/docsearch.min.js"></script>

        <script>
            docsearch({
                apiKey: '{{ config('services.algolia.key') }}',
                indexName: '{{ config('services.algolia.index') }}',
                inputSelector: '#algolia-search',
                debug: false,

                algoliaOptions: {
                    hitsPerPage: 5,
                    facetFilters: ['project:{{ $repository->slug }}', 'version:{{ $alias->slug }}'],
                },
            });
        </script>
    @endpush
</x-page>
