<div class="lg:rounded-lg h-full flex flex-col justify-between shadow divide-y divide-blue-gray-200 {{ $repository->visible ? 'bg-white' : 'bg-danger-50' }}">
    <div class="w-full flex items-center justify-between p-6 pt-8 space-x-6 relative">
        <div class="absolute top-0 left-0 px-3 py-1 text-xs text-blue-gray-600 font-semibold lg:rounded-tl-lg {{ $repository->type_background_color }}">
            {{ \App\Models\Repository::TYPES[$repository->type] ?? __('repositories.labels.type_not_set') }}
        </div>

        <div class="flex-1 truncate">
            <div class="flex items-center space-x-3">
                <h3 class="text-blue-gray-900 text-sm font-medium truncate">
                    <a href="{{ $repository->show_url }}" class="app-link app-link--dark">
                        {{ $repository->name }}
                    </a>
                </h3>

                <x-badge :variant="$repository->visible ? 'green' : 'red'">
                    {{ $repository->visible ? __('repositories.labels.visible') : __('repositories.labels.hidden') }}
                </x-badge>
            </div>

            <p class="mt-1 text-blue-gray-500 text-sm truncate">{{ $repository->description }}</p>

            <div class="mt-3 grid sm:grid-cols-2 gap-4 text-xs">
                {{-- documentation --}}
                <div class="flex space-x-2 items-center">
                    <x-heroicon-o-document-text class="h-4 w-4 text-blue-gray-500" />
                    @if ($repository->documentation_url)
                        <a href="{{ $repository->documentation_url }}" class="app-link app-link--dark flex items-center" target="_blank" rel="nofollow noopener">
                            <span>{{ __('repositories.labels.has_documentation') }}</span>
                            <x-css-external class="h-4 w-4" />
                        </a>
                    @else
                        <span class="text-blue-gray-600 font-medium">{{ __('repositories.labels.missing_documentation') }}</span>
                    @endif
                </div>

                {{-- blog post --}}
                <div class="flex space-x-2 items-center">
                    <x-heroicon-s-rss class="h-4 w-4 text-blue-gray-500" />
                    @if ($repository->blogpost_url)
                        <a href="{{ $repository->blogpost_url }}" class="app-link app-link--dark flex items-center" target="_blank" rel="nofollow noopener">
                            <span>{{ __('repositories.labels.has_blogpost') }}</span>
                            <x-css-external class="h-4 w-4" />
                        </a>
                    @else
                        <span class="text-blue-gray-600 font-medium">{{ __('repositories.labels.missing_blogpost') }}</span>
                    @endif
                </div>

                {{-- new --}}
                <div class="flex space-x-2 items-center">
                    <x-dynamic-component :component="$repository->new ? 'heroicon-s-exclamation' : 'heroicon-o-exclamation'" class="h-4 w-4 text-blue-gray-500" />
                    <span class="text-blue-gray-600 font-medium">{{ $repository->new ? __('repositories.labels.is_new') : __('repositories.labels.not_new') }}</span>
                </div>

                <div class="flex space-x-2 items-center">
                    <x-dynamic-component :component="$repository->highlighted ? 'heroicon-s-star' : 'heroicon-o-star'" class="h-4 w-4 text-blue-gray-500" />
                    <span class="text-blue-gray-600 font-medium">{{ $repository->highlighted ? __('repositories.labels.featured') : __('repositories.labels.not_featured') }}</span>
                </div>
            </div>
        </div>

        <div>
            <x-switch-toggle short :value="$repository->visible" class="switch-toggle--green" wire:click="toggleVisible({{ $repository->id }})" />
        </div>
    </div>

    <div>
        <div class="-mt-px flex divide-x divide-blue-gray-200">
            <div class="w-0 flex-1 flex">
                <button wire:click="confirmDelete({{ $repository->id }})"
                        class="relative -mr-px w-0 flex-1 inline-flex items-center justify-center py-4 text-sm text-blue-gray-700 font-medium border border-transparent rounded-bl-lg
                        hover:text-blue-gray-500 transition-colors focus:outline-blue-gray {{ $repository->visible ? 'hover:bg-blue-gray-100' : 'hover:bg-danger-100' }}"
                >
                    <x-heroicon-o-trash class="h-5 w-5 text-danger-400" />

                    <span class="ml-3">{{ __('labels.delete_button') }}</span>
                </button>
            </div>
            <div class="-ml-px w-0 flex-1 flex">
                <button wire:click="edit({{ $repository->id }})"
                        type="button"
                        class="relative w-0 flex-1 inline-flex items-center justify-center py-4 text-sm text-blue-gray-700 font-medium border border-transparent rounded-br-lg
                        hover:text-blue-gray-500 transition-colors focus:outline-blue-gray {{ $repository->visible ? 'hover:bg-blue-gray-100' : 'hover:bg-danger-100' }}"
                >
                    <x-heroicon-s-pencil class="h-5 w-5 text-blue-gray-400" />

                    <span class="ml-3">{{ __('labels.edit_button') }}</span>
                </button>
            </div>
        </div>
    </div>
</div>
