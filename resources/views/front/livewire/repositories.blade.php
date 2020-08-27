<div>
    @if ($this->filterable)
        <div class="hidden print:hidden wrap sm:flex justify-start mb-8">

        </div>

        <div class="wrap print:hidden">
            <div class="sm:flex sm:items-baseline sm:justify-between mb-8">
                <input wire:model="search"
                       type="search"
                       class="form-input px-4 w-full sm:w-auto"
                       placeholder="Search packages..."
                >

                <div class="mt-3 sm:mt-0 sm:ml-6">
                    <label for="sort" class="text-gray-500 mr-2">
                        Sort
                    </label>

                    <div class="select">
                        <select class="form-select" wire:model="sort" name="sort">
                            <option value="-downloads">by downloads</option>
                            <option value="name">by name</option>
                            <option value="-stars">by popularity</option>
                            <option value="-repository_created_at">by date</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="wrap">
        <div>
            @foreach ($repositories as $repository)
                <div class="cells" style="grid-template-columns: 3fr 3fr 1fr;">
                    <div class="cell-l">
                        <div>
                            <a href="{{ $repository->url }}"
                               class="font-display-bold link-underline link-black"
                               target="_blank"
                               rel="nofollow noreferrer noopener"
                            >
                                {{ $repository->name }}
                            </a>
                        </div>

                        <div class="text-xs mt-2 text-gray-500">
                            @if ($repository->language)
                                <span class="font-bold">
                                    {{ $repository->language }}
                                    <span class="char-separator">•</span>
                                </span>
                            @endif

                            @if ($repository->downloads !== null)
                                <span>
                                    {{ number_format($repository->downloads, 0) }}
                                    <span class="icon fill-current text-gray-500">
                                        <x-heroicon-s-download />
                                    </span>
                                    <span class="char-separator">•</span>
                                </span>
                            @endif

                            {{ number_format($repository->stars, 0) }}
                            <span class="icon fill-current text-gray-500" style="transform: translateY(-2px);">
                                <x-heroicon-s-star />
                            </span>

                            @if ($repository->hasIssues())
                                <a href="{{ $repository->issues_url }}" target="_blank" rel="nofollow noreferrer noopener"
                                   class="rounded-full px-2 py-1 ml-0 sm:ml-2 whitespace-no-wrap bg-gray-200 text-gray-600"
                                >
                                    {{ $repository->issues->count() }} open {{ Str::plural('issue', $repository->issues->count()) }}
                                </a>
                            @endif

                            @if ($repository->is_new)
                                <span class="rounded-full px-2 py-1 ml-2 bg-gold-lightest text-gold-darkest">
                                    new
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="cell">
                        {{ $repository->description }}

                        <div class="text-xs mt-2 text-gray">
                            @foreach ($repository->topics as $topic)
                                <span>
                                    {{ $topic }}

                                    @unless ($loop->last)
                                        <span class="char-separator">•</span>
                                    @endunless
                                </span>
                            @endforeach
                        </div>
                    </div>

                    <div class="cell-r mt-4 flex flex-col justify-center md:mt-0 md:grid-text-right">
                        @if ($repository->blogpost_url)
                            <a href="{{ $repository->blogpost_url }}"
                               target="_blank"
                               rel="nofollow noreferrer noopener"
                               class="link-underline link-gray text-xs"
                            >
                                Introduction
                            </a>
                        @endif

                        @if ($repository->documentation_url)
                            <a href="{{ $repository->documentation_url }}"
                               target="_blank"
                               rel="nofollow noreferrer noopener"
                               class="link-underline link-gray text-xs"
                            >
                                Documentation
                            </a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        @unless(count($repositories))
            <p class="mt-12 text-lg text-gray-500">
                It appears there isn't a package I've created for that.
                <br>Try checking back later.
            </p>
        @endunless
    </div>
</div>
