<section id="{{ $type }}">
    <div class="wrap">
        <x-front.repositories.repository-search-form
            :query-from-url="$search"
            :haystacks="$allRepositoryHaystacks"
        >
            {{-- header/search --}}
            @include('livewire.front.partials.repositories-header')

            {{-- results --}}
            @php
                $initialQuery = str($search ?? '')->lower()->trim()->toString();
                $initialNoMatches = $initialQuery !== ''
                    && ! $allRepositoryHaystacks->contains(fn (string $haystack): bool => str($haystack)->contains($initialQuery));
            @endphp

            <div class="lg:columns-2 lg:gap-x-12 border-t border-gray-300">
                @foreach ($allRepositories as $i => $repository)
                    @php
                        $initiallyVisible = $initialQuery === '' || str($allRepositoryHaystacks[$i])->contains($initialQuery);
                    @endphp

                    <x-front.repositories.repository-card
                        :repository="$repository"
                        :show-downloads="$type === 'packages'"
                        @class([
                            'flex' => $initiallyVisible,
                            'hidden' => ! $initiallyVisible,
                        ])
                        x-bind:class="{
                            'flex': (! query) || haystacks[{{ $i }}].includes(query.toLowerCase()),
                            'hidden': query && (! haystacks[{{ $i }}].includes(query.toLowerCase())),
                        }"
                    />
                @endforeach
            </div>

            {{-- no search results --}}
            <p
                x-show="noMatches"
                style="{{ $initialNoMatches ? '' : 'display: none;' }}"
                class="text-center py-16 text-black"
            >
                No {{ $this->repositoryType->noResultsLabel() }} match your search "<span x-text="query">{{ $search }}</span>"
            </p>
        </x-front.repositories.repository-search-form>
    </div>
</section>
