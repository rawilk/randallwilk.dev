<div>
    <div class="mt-4 lg:mt-0 mb-2">
        <x-button.link wire:click="toggleShowFilters" class="text-xs">
            {{ __('labels.show_advanced_search') }}
        </x-button.link>
    </div>

    <x-elements.advanced-search slide-out wire:model.defer="showFilters">
        @if ($showFilters)
            <div class="grid grid-cols-2 gap-4 w-full">

                {{-- is admin --}}
                <x-form-group label="{{ __('users.labels.is_admin') }}" name="filters_is_admin">
                    <x-select wire:model="filters.is-admin" name="filters_is_admin">
                        <option value="">{{ __('labels.select_option') }}</option>
                        <option value="0">{{ __('labels.no') }}</option>
                        <option value="1">{{ __('labels.yes') }}</option>
                    </x-select>
                </x-form-group>

                <div></div>

                {{-- created at min --}}
                <x-form-group label="{{ __('users.labels.filter_created_at_min') }}" name="filters_created_min">
                    <x-date-picker wire:model.lazy="filters.created-min" name="filters_created_min" clearable />
                </x-form-group>

                {{-- created at max --}}
                <x-form-group label="{{ __('users.labels.filter_created_at_max') }}" name="filters_created_max">
                    <x-date-picker wire:model.lazy="filters.created-max" name="filters_created_max" clearable />
                </x-form-group>

                {{-- updated at min --}}
                <x-form-group label="{{ __('users.labels.filter_updated_at_min') }}" name="filters_updated_min">
                    <x-date-picker wire:model.lazy="filters.updated-min" name="filters_updated_min" clearable />
                </x-form-group>

                {{-- updated at max --}}
                <x-form-group label="{{ __('users.labels.filter_updated_at_max') }}" name="filters_updated_max">
                    <x-date-picker wire:model.lazy="filters.updated-max" name="filters_updated_max" clearable />
                </x-form-group>

                {{-- min id --}}
                <x-form-group label="{{ __('users.labels.filter_id_min') }}">
                    <x-input wire:model.lazy="filters.min-id" type="number" min="0" id="min_id" />
                </x-form-group>

                {{-- max id --}}
                <x-form-group label="{{ __('users.labels.filter_id_max') }}">
                    <x-input wire:model.lazy="filters.max-id" type="number" min="0" id="max_id" />
                </x-form-group>

            </div>
        @endif
    </x-elements.advanced-search>

    <x-removes-flatpickrs />
</div>
