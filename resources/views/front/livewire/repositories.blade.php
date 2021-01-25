<div>
    @if ($this->filterable)
        <div class="print:hidden">
            <div class="sm:flex sm:items-baseline sm:justify-between mb-8">

                <div class="sm:w-1/2">
                    <x-input wire:model.debounce="search"
                             type="search"
                             placeholder="{{ $this->searchPlaceholder }}"
                             container-class="w-full"
                    />
                </div>

                <div class="mt-3 sm:mt-0 sm:ml-6 sm:flex sm:items-center sm:space-x-1">
                    <label for="sort" class="text-blue-gray-500 mr-2">
                        {{ __('front.repositories.sort_label') }}
                    </label>

                    <div>
                        <x-select name="sort" wire:model="sort" name="sort">
                            <option value="-downloads">{{ __('front.repositories.sort_downloads') }}</option>
                            <option value="name">{{ __('front.repositories.sort_name') }}</option>
                            <option value="-stars">{{ __('front.repositories.sort_stars') }}</option>
                            <option value="-repository_created_at">{{ __('front.repositories.sort_date') }}</option>
                        </x-select>
                    </div>
                </div>

            </div>
        </div>
    @endif

    <div>
        <ul class="divide-y divide-y-blue-gray-200">
            @forelse ($repositories as $repository)
                <li class="py-4">
                    @if ($repository->new || $repository->highlighted)
                        <div class="mb-1 flex items-center space-x-2">
                            @if ($repository->new)
                                <div>
                                    <x-badge class="bg-warning-200 text-warning-600">
                                        <span class="uppercase font-bold">{{ __('front.repositories.new') }}</span>
                                    </x-badge>
                                </div>
                            @endif

                            @if ($repository->highlighted)
                                <div>
                                    <x-badge variant="green">
                                        <span class="uppercase font-bold">{{ __('front.repositories.highlighted') }}</span>
                                    </x-badge>
                                </div>
                            @endif
                        </div>
                    @endif

                    <div>
                        <a href="{{ $repository->url }}"
                           target="_blank"
                           rel="nofollow noreferrer noopener"
                           class="font-bold link-underline link-black inline-block max-w-full truncate"
                        >
                            {{ $repository->name }}
                        </a>
                    </div>

                    <div class="text-xs text-blue-gray-500 flex space-x-2 flex-wrap">
                        @if ($repository->language)
                            <span class="font-bold">
                                {{ $repository->language }}
                            </span>
                            <span class="char-separator">•</span>
                        @endif

                        @unless (is_null($repository->downloads))
                            <span class="flex items-center space-x-1">
                                <span>{{ $repository->downloads_for_humans }}</span>
                                <x-heroicon-s-download class="h-4 w-4 text-blue-gray-500" />
                            </span>
                            <span class="char-separator">•</span>
                        @endunless

                        <span class="flex items-center space-x-1">
                            <span>{{ $repository->stars_for_humans }}</span>
                            <x-heroicon-s-star class="h-4 w-4 text-blue-gray-500" />
                        </span>

                        @if ($repository->hasIssues())
                            <span>
                                <x-badge class="bg-blue-gray-200 hover:bg-blue-gray-300">
                                    <a href="{{ $repository->issues_url }}"
                                       target="_blank"
                                       rel="nofollow noreferrer nooopener"
                                       class="text-blue-gray-600"
                                    >
                                        {{ trans_choice('front.repositories.issues_count', $repository->issues->count(), ['count' => $repository->issues->count()])  }}
                                    </a>
                                </x-badge>
                            </span>
                        @endif
                    </div>

                    <div class="mt-2">
                        <p class="text-sm text-blue-gray-600">{{ $repository->description }}</p>

                        <div class="text-xs mt-2 text-blue-gray-600">
                            @foreach ($repository->topics as $topic)
                                <span>{{ $topic }}</span>

                                @unless ($loop->last)
                                    <span class="char-separator">•</span>
                                @endunless
                            @endforeach
                        </div>
                    </div>

                    <div class="mt-2 flex flex-col -space-y-0.5">
                        @if ($repository->blogpost_url)
                            <div>
                                <a href="{{ $repository->blogpost_url }}"
                                   class="link-underline link-gray text-xs inline-flex items-center space-x-1"
                                   @if (isExternalLink($repository->blogpost_url))
                                       target="_blank"
                                       rel="nofollow noreferrer noopener"
                                   @endif
                                >
                                    <span>{{ __('front.repositories.blog_post_link') }}</span>

                                    @if (isExternalLink($repository->blogpost_url))
                                        <x-heroicon-o-external-link class="h-3 w-3 text-blue-gray-600" />
                                    @endif
                                </a>
                            </div>
                        @endif

                        @if ($repository->documentation_url)
                            <div>
                                <a href="{{ $repository->documentation_url }}"
                                   class="link-underline link-gray text-xs inline-flex items-center space-x-1"
                                   @if (isExternalLink($repository->documentation_url))
                                       target="_blank"
                                       rel="nofollow noreferrer noopener"
                                   @endif
                                >
                                    <span>{{ __('front.repositories.docs_link') }}</span>

                                    @if (isExternalLink($repository->documentation_url))
                                        <x-heroicon-o-external-link class="h-3 w-3 text-blue-gray-600" />
                                    @endif
                                </a>
                            </div>
                        @endif
                    </div>
                </li>
            @empty
                <li>
                    <p class="text-lg text-blue-gray-500">
                        It appears there isn't a {{ Str::singular($this->type) }} I've created for that.
                        <br>Try checking back later.
                    </p>
                </li>
            @endforelse
        </ul>

{{--        @unless(count($repositories))--}}
{{--            <p class="mt-12 text-lg text-gray-500">--}}
{{--                It appears there isn't a package I've created for that.--}}
{{--                <br>Try checking back later.--}}
{{--            </p>--}}
{{--        @endunless--}}
    </div>
</div>
