<div class="bg-white shadow overflow-hidden lg:rounded-lg">
    <div class="px-4 py-5 sm:px-6 flex items-center justify-between">
        <h3 class="text-lg leading-6 font-medium text-blue-gray-900 flex-1">
            {{ __('Repository Information') }}
        </h3>

        <div>
            <x-dropdown trigger-text="{{ __('Actions') }}" with-background right fixed-position>
                <x-dropdown-item wire:click="edit">
                    <x-heroicon-s-pencil />

                    <span>{{ __('Edit') }}</span>
                </x-dropdown-item>

                <x-dropdown-item wire:click="confirmDelete">
                    <x-heroicon-o-trash />

                    <span>{{ __('Delete') }}</span>
                </x-dropdown-item>

                <hr />

                <x-dropdown-item wire:click="updateInfo" wire:loading.attr="disabled" wire:target="updateInfo">
                    <x-heroicon-s-refresh wire:loading.class="animate-spin" wire:target="updateInfo" />

                    <span>{{ __('Sync Repository Info') }}</span>
                </x-dropdown-item>

                @if ($repository->hasDocs())
                    <x-dropdown-item wire:click="syncDocs" wire:loading.attr="disabled" wire:target="syncDocs">
                        <x-heroicon-s-refresh wire:loading.class="animate-spin" wire:target="syncDocs" />

                        <span>{{ __('Sync Docs') }}</span>
                    </x-dropdown-item>
                @endif
            </x-dropdown>
        </div>
    </div>

    <div class="border-t border-blue-gray-200">
        <x-info-list wire:loading.remove wire:target="updateInfo">
            <x-info-list-item label="{{ __('Name') }}">
                <a href="{{ $repository->url }}" target="_blank" rel="nofollow noopener" class="app-link app-link--dark inline-flex items-center space-x-1">
                    <span>{{ $repository->full_name }}</span>

                    <x-heroicon-o-external-link class="w-4 h-4" />
                </a>
            </x-info-list-item>

            <x-info-list-item label="{{ __('Description') }}">
                {{ $repository->description }}
            </x-info-list-item>

            <x-info-list-item label="{{ __('Type') }}">{{ \App\Models\Repository::TYPES[$repository->type] ?? 'Unknown Type' }}</x-info-list-item>

            <x-info-list-item label="{{ __('Language') }}">{{ $repository->language }}</x-info-list-item>

            <x-info-list-item label="{{ __('Visibility') }}">
                <x-badge :variant="$repository->visible ? 'green' : 'red'" large>
                    {{ $repository->visible ? __('Visible') : __('Hidden') }}
                </x-badge>
            </x-info-list-item>

            <x-info-list-item label="{{ __('Documentation URL') }}">
                @if ($repository->documentation_url)
                    <a href="{{ $repository->documentation_url }}" target="_blank" rel="nofollow noopener" class="app-link app-link--dark inline-flex items-center space-x-1">
                        <span>{{ $repository->documentation_url }}</span>

                        <x-heroicon-o-external-link class="w-4 h-4" />
                    </a>
                @endif
            </x-info-list-item>

            <x-info-list-item label="{{ __('Blog Post URL') }}">
                @if ($repository->blogpost_url)
                    <a href="{{ $repository->blogpost_url }}" target="_blank" rel="nofollow noopener" class="app-link app-link--dark inline-flex items-center space-x-1">
                        <span>{{ $repository->blogpost_url }}</span>

                        <x-heroicon-o-external-link class="w-4 h-4" />
                    </a>
                @endif
            </x-info-list-item>

            <x-info-list-item label="{{ __('Marked as New') }}">
                <span class="sr-only">{{ $repository->new ? __('Yes') : __('No') }}</span>
                <x-dynamic-component :component="$repository->new ? 'heroicon-o-check-circle' : 'heroicon-o-x-circle'" :class="'w-5 h-5 ' . ($repository->new ? 'text-success-500' : 'text-danger-500')" />
            </x-info-list-item>

            <x-info-list-item label="{{ __('Marked as Featured') }}">
                <span class="sr-only">{{ $repository->highlighted ? __('Yes') : __('No') }}</span>
                <x-dynamic-component :component="$repository->highlighted ? 'heroicon-o-check-circle' : 'heroicon-o-x-circle'" :class="'w-5 h-5 ' . ($repository->highlighted ? 'text-success-500' : 'text-danger-500')" />
            </x-info-list-item>

            <x-info-list-item label="{{ __('Repository Created At') }}">
                {{ $repository->repository_created_at_for_humans }}
            </x-info-list-item>

            <x-info-list-item label="{{ __('Stars') }}">
                {{ number_format($repository->stars) }}
            </x-info-list-item>

            @if ($repository->type === 'package')
                <x-info-list-item label="{{ __('Downloads') }}">
                    {{ number_format($repository->downloads) }}
                </x-info-list-item>
            @endif

            <x-info-list-item label="{{ __('Open Issues') }}">
                <a href="{{ $repository->issues_url }}" target="_blank" rel="nofollow noopener" class="app-link app-link--dark inline-flex items-center space-x-1">
                    <span>{{ $repository->issues->count() }}</span>

                    <x-heroicon-o-external-link class="w-4 h-4" />
                </a>
            </x-info-list-item>

            <x-info-list-item label="{{ __('Topics') }}">
                <div class="flex flex-wrap items-center space-x-2">
                    @foreach ($repository->topics as $topic)
                        <x-badge variant="blue">{{ $topic }}</x-badge>
                    @endforeach
                </div>
            </x-info-list-item>
        </x-info-list>

        <div class="hidden py-10 flex items-center space-x-2 justify-center" wire:loading.class.remove="hidden" wire:target="updateInfo">
            <x-heroicon-s-refresh class="h-6 w-6 text-blue-gray-500 animate-spin" />
            <p class="text-lg text-blue-gray-600 font-medium">{{ __('Syncing repository information with Github...') }}</p>
        </div>
    </div>
</div>
