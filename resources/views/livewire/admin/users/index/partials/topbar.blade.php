<div>
    <x-topbar
        :hideable-columns="$hideableColumns"
        :hidden-columns="$hidden"
        search-placeholder="{{ __('Search in Name, Email') }}"
    >
        <div class="topbar-section">
            <x-dropdown trigger-text="{{ __('base::messages.bulk_actions') }}" right>
                {{-- import --}}
                @canany([\App\Enums\PermissionEnum::USERS_CREATE->value, \App\Enums\PermissionEnum::USERS_EDIT->value])
                    <x-dropdown-item wire:click="$emitTo('csv-importer', 'toggle')">
                        <x-css-software-upload />
                        <span>{{ __('base::messages.import_button') }}</span>
                    </x-dropdown-item>
                @endcanany

                {{-- export --}}
                <x-dropdown-item wire:click="exportSelected">
                    <x-css-software-download />
                    <span>{{ __('base::messages.export_button') }}</span>
                </x-dropdown-item>

                {{-- delete --}}
                @can(\App\Enums\PermissionEnum::USERS_DELETE->value)
                    <x-dropdown-divider />
                    <x-dropdown-item wire:click="$toggle('showBulkDelete')">
                        <x-css-trash />
                        <span>{{ __('base::messages.delete_button') }}</span>
                    </x-dropdown-item>
                @endcan
            </x-dropdown>
        </div>

        @include('livewire.admin.users.index.partials.filters')
    </x-topbar>

    {{-- filter breadcrumbs --}}
    @include('layouts.partials.filter-breadcrumbs')
</div>
