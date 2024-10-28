<section id="{{ $type }}">
    <div class="wrap">
        {{-- search --}}
        <div class="md:flex justify-between items-baseline mb-20 w-full">
            <h2 class="text-[40px] font-bold leading-[0.9] mb-6 md:mb-0 uppercase">
                @if ($type === 'packages')
                    All<br>packages
                @else
                    All<br>projects
                @endif
            </h2>

            @if ($filterable)
                <div class="flex flex-col sm:flex-row gap-4 sm:gap-12 items-baseline sm:items-center justify-between mb-8 md:w-1/2">
                    {{-- sort --}}
                    <div>
                        <label for="sort" class="block text-[14px] mr-6 text-gray-500">
                            Sort by
                        </label>
                        <div class="relative select p-0 text-[14px] border-none">
                            <select name="sort" wire:model.live="sort" class="text-[14px]">
                                @foreach ($this->availableSorts as $case)
                                    <option value="{{ $case->selectValue() }}">{{ $case->getLabel() }}</option>
                                @endforeach
                            </select>

                            <span class="select-arrow pl-2.5 -mt-2" wire:ignore>
                                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 16 16">
                                    <rect class="fill-gray-200" width="16" height="16" rx="8"/>
                                    <path class="fill-gray-600" d="m8 11.61.471-.47 4-4 .473-.473L12 5.723l-.47.471L8 9.724 4.471 6.195l-.47-.473-.944.944.47.47 4 4 .473.474Z"/>
                                </svg>
                            </span>
                        </div>
                    </div>

                    {{-- search --}}
                    <div class="relative w-full">
                        <div class="form-input-wrp">
                            <div class="min-w-0 flex-1">
                                <input
                                    type="search"
                                    wire:model.debounce.live="search"
                                    placeholder="{{ $this->searchPlaceholder }}"
                                    class="w-full form-input py-3"
                                >
                            </div>

                            <div class="flex items-center gap-x-3 pe-3 ps-2">
                                <div class="flex items-center gap-3">
                                    <x-heroicon-m-magnifying-glass
                                        class="h-5 w-5 text-gray-500"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        {{-- repos --}}
        <div>
            @if ($repositories->isNotEmpty())
                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-10">
                    @foreach ($repositories as $repository)
                        <div>
                            <x-front.oss-link-card
                                tag="a"
                                :href="$repository->url"
                                :title="$repository->name"
                                :badge="$repository->new ? 'New' : null"
                                :wire:key="'repositories.' . $repository->name"
                            >
                                <div class="h-full flex flex-col">
                                    <div class="mb-12">
                                        <p class="mobile:line-clamp-2">{{ $repository->description }}</p>

                                        @if (filled($repository->documentation_url))
                                            <div class="mt-2">
                                                <a
                                                    href="{{ $repository->documentation_url }}"
                                                    class="relative z-20 text-sm text-gray-500 hover:underline after:content-['_↗']"
                                                >
                                                    Documentation
                                                </a>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="flex items-center gap-x-5 mt-auto">
                                        @if ($this->repositoryType === App\Enums\RepositoryType::Package)
                                            {{-- downloads --}}
                                            <span class="text-sm flex items-center gap-x-1">
                                                <x-heroicon-m-arrow-down-tray
                                                    class="w-4 h-4 text-gray-500"
                                                />
                                                <span>{{ Number::format($repository->downloads) }}</span>
                                            </span>
                                        @endif

                                        {{-- stars --}}
                                        <span class="text-sm flex items-center gap-x-1">
                                            <x-heroicon-m-star
                                                class="w-4 h-4 text-gray-500"
                                            />
                                            <span>{{ Number::format($repository->stars) }}</span>
                                        </span>
                                    </div>
                                </div>
                            </x-front.oss-link-card>
                        </div>
                    @endforeach
                </div>
            @endif

            @if ($repositories->isEmpty())
                <p class="text-lg">
                    {{ $this->noResultsText }}
                </p>
            @endif
        </div>

        {{-- load more --}}
        @if ($this->hasMore)
            <div
                class="w-full flex justify-center my-24"
                x-intersect="$wire.loadMore()"
            >
                <svg class="animate-spin w-10 h-10 fill-current text-gray-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                    <path class="fa-secondary" opacity=".4" d="M0 256C0 114.9 114.1 .5 255.1 0C237.9 .5 224 14.6 224 32c0 17.7 14.3 32 32 32C150 64 64 150 64 256s86 192 192 192c69.7 0 130.7-37.1 164.5-92.6c-3 6.6-3.3 14.8-1 22.2c1.2 3.7 3 7.2 5.4 10.3c1.2 1.5 2.6 3 4.1 4.3c.8 .7 1.6 1.3 2.4 1.9c.4 .3 .8 .6 1.3 .9s.9 .6 1.3 .8c5 2.9 10.6 4.3 16 4.3c11 0 21.8-5.7 27.7-16c-44.3 76.5-127 128-221.7 128C114.6 512 0 397.4 0 256z"/>
                    <path class="fa-primary" d="M224 32c0-17.7 14.3-32 32-32C397.4 0 512 114.6 512 256c0 46.6-12.5 90.4-34.3 128c-8.8 15.3-28.4 20.5-43.7 11.7s-20.5-28.4-11.7-43.7c16.3-28.2 25.7-61 25.7-96c0-106-86-192-192-192c-17.7 0-32-14.3-32-32z"/>
                </svg>
            </div>
        @endif
    </div>
</section>
