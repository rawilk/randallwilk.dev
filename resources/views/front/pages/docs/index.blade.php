<x-page
    title="Documentation"
    description="Documentation for my open source packages."
>
    <x-front.page-banner>
        Docs

        <x-slot:content>
            <p class="banner-intro">
                Documentation for my open source packages.
            </p>
        </x-slot:content>
    </x-front.page-banner>

    <section
        class="wrap pb-16"
        id="docs-repositories"
    >
        <x-front.repositories.repository-search-form
            :haystacks="$haystacks"
            :query-from-url="$search"
        >
            <div class="w-full">
                <x-forms.front-search-input
                    placeholder="Filter packages..."
                    x-model="query"
                    :value="$search"
                />
            </div>

            <div class="w-full py-6">
                <div class="flex flex-col justify-between lg:flex-row lg:items-center gap-6 lg:gap-12">
                    <x-front.link>
                        <p class="text-black">
                            Showing {{ $repositories->count() }} packages with documentation.
                            View the full list of my <a href="{{ route('open-source.packages') }}">open-source packages</a>.
                        </p>
                    </x-front.link>
                </div>
            </div>

            @php
                $initialQuery = str($search ?? '')->lower()->trim()->toString();
                $initialNoMatches = $initialQuery !== ''
                    && ! $haystacks->contains(fn (string $haystack): bool => str($haystack)->contains($initialQuery));
            @endphp

            <div class="grid border-t border-gray-300 lg:grid-cols-2 lg:gap-x-12">
                @foreach ($repositories as $i => $repository)
                    @include('front.pages.docs.partials.repository', [
                        'initiallyVisible' => $initialQuery === '' || str($haystacks[$i])->contains($initialQuery),
                        'repository' => $repository,
                        'repositoryIndex' => $i,
                    ])
                @endforeach
            </div>

            {{-- no search results --}}
            <p
                x-show="noMatches"
                style="{{ $initialNoMatches ? '' : 'display: none;' }}"
                class="text-center py-16 text-black"
            >
                No packages match your search "<span x-text="query">{{ $search }}</span>"
            </p>
        </x-front.repositories.repository-search-form>
    </section>

    <x-slot:call-to-action>
        <x-layout.front.support-cta />
    </x-slot:call-to-action>
</x-page>
