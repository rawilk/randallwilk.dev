<div>
    <x-card flush>
        <x-slot:header>
            <div class="flex items-center justify-between">
                <h3 class="flex-1">
                    {{ __('Repository Information') }}
                </h3>

                <div>
                    <x-dropdown trigger-text="{{ __('Actions') }}" right>
                        <x-dropdown-item wire:click="edit">
                            <x-heroicon-s-pencil />
                            <span>{{ __('base::messages.edit_button') }}</span>
                        </x-dropdown-item>

                        <x-dropdown-item wire:click="confirmDelete">
                            <x-css-trash />
                            <span>{{ __('base::messages.delete_button') }}</span>
                        </x-dropdown-item>

                        <x-dropdown-divider />

                        <x-dropdown-item wire:click="syncInfo" wire:loading.attr="disabled" wire:target="syncInfo">
                            <x-heroicon-s-arrow-path />
                            <span>{{ __('repos.labels.sync_repo_button') }}</span>
                        </x-dropdown-item>

                        @if ($repository->hasDocs())
                            <x-dropdown-item wire:click="syncDocs" wire:loading.attr="disabled" wire:target="syncDocs">
                                <x-heroicon-s-arrow-path />
                                <span>{{ __('repos.labels.sync_docs_button') }}</span>
                            </x-dropdown-item>
                        @endif
                    </x-dropdown>
                </div>
            </div>
        </x-slot:header>

        @include('livewire.admin.repositories.show.partials.details')
    </x-card>

    {{-- confirm delete --}}
    @can('delete', $repository)
        @include('livewire.admin.repositories.partials.confirm-delete')
    @endcan

    {{-- edit form --}}
    @can('edit', $repository)
        @include('livewire.admin.repositories.partials.form')
    @endcan
</div>
