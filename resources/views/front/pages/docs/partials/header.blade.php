<header>
    <div class="lg:-mb-8 pt-4 lg:pt-0 lg:mt-8">
        <x-breadcrumbs breadcrumbs="docs.repository" :params="[$repository, $alias, $page]" />
    </div>

    <div class="flex flex-col items-end lg:mt-8 lg:flex-row-reverse">
        {{-- version select --}}
        <div class="mt-8 w-full lg:mt-0 lg:w-64 lg:pl-12">
            <div>
                <label class="text-blue-gray-600 text-xs tracking-widest uppercase"
                       for="version-switch"
                >
                    Version
                </label>
                <div x-data class="relative w-full">
                    <x-select name="version-switcher"
                              aria-label="Label version"
                              x-on:change="window.location = $event.target.value"
                    >
                        @foreach ($repository->aliases as $aliasOption)
                            <option value="/docs/{{ $repository->slug }}/{{ $aliasOption->slug }}"
                                    @if ($page->alias === $aliasOption->slug) selected @endif
                            >
                                {{ $aliasOption->slug }} @if ($aliasOption->isMasterBranch()) ({{ $aliasOption->branch }}) @endif
                            </option>
                        @endforeach
                    </x-select>
                </div>
            </div>
        </div>

        {{-- search --}}
        <div class="relative mt-4 flex justify-end w-full lg:mt-0">
            <div class="relative w-full">
                <x-input type="search"
                         name="algolia-search"
                         placeholder="Search Docs"
                />
            </div>
        </div>
    </div>
</header>

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
