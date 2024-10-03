<div>
    {{-- filters --}}
    @if ($filterable)
        <div class="print:hidden wrap">
            <div class="sm:flex sm:items-baseline sm:justify-between mb-8">
                <div class="sm:w-1/2">
                    <x-input
                            wire:model.debounce="search"
                            type="search"
                            :placeholder="$this->searchPlaceholder"
                            container-class="w-full"
                    />
                </div>

                <div class="mt-3 sm:mt-0 sm:ml-6 sm:flex sm:items-center sm:space-x-1">
                    <label for="sort" class="text-slate-600 mr-2">
                        {{ __('front.open_source.sort_label') }}
                    </label>

                    <div>
                        <x-select name="sort" wire:model="sort">
                            @foreach (\App\Enums\RepositorySort::cases() as $case)
                                <option value="{{ $case->selectValue() }}">{{ $case->label() }}</option>
                            @endforeach
                        </x-select>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- results --}}
    <section class="section section-group !pt-0">
        <div class="wrap">
            <ul role="list"
                class="grid grid-cols-1 gap-x-12 gap-y-16"
            >
                @php
                    /** @var \App\Models\GitHub\Repository $repository */
                @endphp
                @forelse ($repositories as $repository)
                    <li class="group relative flex flex-col items-start"
                        id="repo-{{ $repository->name }}"
                    >
                        @if ($repository->language)
                            <div class="relative z-10">
                                <x-badge :variant="$repository->language->color()" class="text-sm">
                                    {{ $repository->language->value }}
                                </x-badge>
                            </div>
                        @endif

                        <h2 @class([
                            'text-base font-semibold text-slate-800 static',
                            'mt-4' => $repository->language,
                        ])>
                            <div class="absolute -inset-y-6 -inset-x-4 z-0 scale-95 bg-slate-50 opacity-0 transition group-hover:scale-100 group-hover:opacity-100 sm:-inset-x-6 sm:rounded-2xl"></div>
                            <a href="{{ $repository->url }}"
                               rel="nofollow noreferrer"
                               class="static"
                            >
                                <span class="absolute -inset-y-6 -inset-x-4 z-20 sm:-inset-x-6 sm:rounded-2xl"></span>
                                <span class="relative z-10 group-hover:text-brand">{{ $repository->display_name }}</span>
                            </a>
                        </h2>

                        <p class="relative z-20 mt-2 text-sm text-slate-600">
                            {{ $repository->description }}
                        </p>

                        {{-- stats --}}
                        <div class="mt-4 text-xs grid grid-flow-col gap-4 justify-start items-center text-slate-600">
                            {{-- downloads --}}
                            @unless (is_null($repository->downloads))
                                <div class="flex items-center space-x-1">
                                    <x-heroicon-s-arrow-down-tray class="h-4 w-4 text-slate-500" />
                                    <span>{{ $repository->downloads_for_front }}</span>
                                </div>
                            @endunless

                            {{-- stars --}}
                            <div class="flex items-center space-x-1">
                                <x-heroicon-o-star class="h-4 w-4 text-slate-500" />
                                <span>{{ $repository->stars_for_front }}</span>
                            </div>
                        </div>

                        {{-- topics --}}
                        <div class="mt-2">
                            <ul class="text-xs grid grid-flow-col gap-2 justify-start items-center text-slate-600 list-disc list-inside">
                                @foreach ($repository->topics as $topic)
                                    <li class="first:list-none z-20">
                                        <span>{{ $topic }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        {{-- links --}}
                        @if ($repository->blogpost_url || $repository->documentation_url)
                            <div class="mt-2">
                                <x-front.link>
                                    <div class="flex flex-col -space-y-0.5">
                                        {{-- blogpost url --}}
                                        @if ($repository->blogpost_url)
                                            <div>
                                                <a href="{{ $repository->blogpost_url }}"
                                                   @if (isExternalLink($repository->blogpost_url))
                                                       target="_blank"
                                                   rel="nofollow noreferrer noopener"
                                                   @endif
                                                   class="z-20 text-xs after:content-['_↗'] -mb-3"
                                                >
                                                    <span>{{ __('front.open_source.blogpost_link') }}</span>
                                                </a>
                                            </div>
                                        @endif

                                        {{-- docs url --}}
                                        @if ($repository->documentation_url)
                                            <div>
                                                <a href="{{ $repository->documentation_url }}"
                                                   @if (isExternalLink($repository->documentation_url))
                                                       target="_blank"
                                                   rel="nofollow noreferrer noopener"
                                                   @endif
                                                   class="z-20 text-xs after:content-['_↗']"
                                                >
                                                    <span>{{ __('front.open_source.docs_url_link') }}</span>
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </x-front.link>
                            </div>
                        @endif
                    </li>
                @empty
                    <li id="no-results">
                        <p class="text-lg text-slate-600">
                            {!! $this->noResultsText !!}
                        </p>
                    </li>
                @endforelse
            </ul>
        </div>
    </section>
</div>
