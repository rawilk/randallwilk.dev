@php
    /** @var \App\Models\GitHub\Repository $repository */
@endphp
<div @class([
    'lg:rounded-lg | h-full flex flex-col justify-between shadow divide-y divide-slate-200',
    'bg-white' => $repository->visible,
    'bg-red-50' => ! $repository->visible,
])>
    <div class="w-full flex items-center justify-between p-6 pt-8 space-x-6 relative">
        <div class="absolute top-0 left-0 px-3 py-1 text-xs text-slate-600 font-semibold | lg:rounded-tl-lg | {{ $repository->type_background_color }}">
            {{ $repository->type?->label() ?? __('repos.labels.type_not_set') }}
        </div>

        <div class="flex-1 truncate">
            <div class="flex items-center space-x-3">
                <h3 class="text-slate-900 text-sm font-medium truncate">
                    <x-link dark :href="$repository->show_url">
                        {{ $repository->name }}
                    </x-link>
                </h3>

                <x-badge :variant="$repository->visible ? 'green' : 'red'">
                    {{ $repository->visible ? __('repos.labels.visible') : __('repos.labels.hidden') }}
                </x-badge>
            </div>

            @if ($repository->scoped_name)
                <p class="mt-1 text-slate-500 text-sm truncate italic">
                    {{ $repository->scoped_name }}
                </p>
            @endif

            <p class="mt-1 text-slate-500 text-sm truncate">{{ $repository->description }}</p>

            <div class="mt-5 grid md:grid-cols-2 gap-4 text-xs">
                {{-- documentation --}}
                <x-admin.repo-stat
                    icon="heroicon-o-document-text"
                    :action-url="$repository->documentation_url"
                    :missing-text="__('repos.labels.missing_docs')"
                >
                    {{ __('repos.labels.see_docs') }}
                </x-admin.repo-stat>

                {{-- blog post --}}
                <x-admin.repo-stat
                    icon="heroicon-s-rss"
                    :action-url="$repository->blogpost_url"
                    :missing-text="__('repos.labels.missing_blogpost')"
                >
                    {{ __('repos.labels.see_blogpost') }}
                </x-admin.repo-stat>

                {{-- new --}}
                <x-admin.repo-stat
                    :condition="$repository->new"
                    icon="heroicon-s-exclamation-triangle"
                    off-icon="heroicon-o-exclamation-triangle"
                    :missing-text="__('repos.labels.not_new')"
                >
                    {{ __('repos.labels.is_new') }}
                </x-admin.repo-stat>

                {{-- featured --}}
                <x-admin.repo-stat
                    :condition="$repository->highlighted"
                    icon="heroicon-s-star"
                    off-icon="heroicon-o-star"
                    :missing-text="__('repos.labels.not_featured')"
                >
                    {{ __('repos.labels.is_featured') }}
                </x-admin.repo-stat>
            </div>
        </div>

        <div wire:key="repo{{ $repository->getKey() }}State{{ $repository->visible }}">
            <x-switch-toggle
                short
                :value="$repository->visible"
                color="green"
                wire:click.prevent.stop="toggleVisible('{{ $repository->getKey() }}')"
            />
        </div>
    </div>

    {{-- actions --}}
    <div>
        <div class="-mt-px flex divide-x divide-slate-200">
            {{-- delete --}}
            <div class="w-0 flex-1 flex">
                <button
                    wire:click="confirmDelete('{{ $repository->getKey() }}')"
                    @class([
                        'relative -mr-px w-0 flex-1 inline-flex items-center justify-center py-4 text-sm text-slate-700 font-medium border border-transparent rounded-bl-lg',
                        'hover:text-slate-500 transition-colors focus:outline-blue-gray',
                        'hover:bg-slate-100' => $repository->visible,
                        'hover:bg-red-100' => ! $repository->visible,
                    ])
                >
                    <x-css-trash class="h-5 w-5 text-red-500" />
                    <span class="ml-2">{{ __('base::messages.delete_button') }}</span>
                </button>
            </div>

            {{-- edit --}}
            <div class="-ml-px w-0 flex-1 flex">
                <button
                    wire:click="edit('{{ $repository->getKey() }}')"
                    @class([
                        'relative -mr-px w-0 flex-1 inline-flex items-center justify-center py-4 text-sm text-slate-700 font-medium border border-transparent rounded-bl-lg',
                        'hover:text-slate-500 transition-colors focus:outline-blue-gray',
                        'hover:bg-slate-100' => $repository->visible,
                        'hover:bg-red-100' => ! $repository->visible,
                    ])
                >
                    <x-heroicon-s-pencil class="h-4 w-4 text-slate-500" />
                    <span class="ml-2">{{ __('base::messages.edit_button') }}</span>
                </button>
            </div>
        </div>
    </div>
</div>
