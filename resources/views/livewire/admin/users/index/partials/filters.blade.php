<x-slot:filters>
    {{-- role --}}
    <x-form-group :label="__('Role')" name="filters.roles">
        <x-custom-select
            wire:model.defer="filters.roles"
            name="filters.roles"
            multiple
            optional
            :placeholder="__('Select Roles...')"
            :options="$roles"
        />
    </x-form-group>

    <x-form-group :label="__('Last Updated Min')">
        <x-date-picker wire:model.defer="filters.updated-min" id="updated_min" clearable />
    </x-form-group>

    <x-form-group :label="__('Last Updated Max')">
        <x-date-picker wire:model.defer="filters.updated-max" id="updated_max" clearable />
    </x-form-group>

    <x-form-group :label="__('Created Min')">
        <x-date-picker wire:model.defer="filters.created-min" id="created_min" clearable />
    </x-form-group>

    <x-form-group :label="__('Created Max')">
        <x-date-picker wire:model.defer="filters.created-max" id="created_max" clearable />
    </x-form-group>

    <x-form-group :label="__('Minimum ID')">
        <x-input wire:model.defer="filters.min-id" type="number" min="0" id="min_id" />
    </x-form-group>

    <x-form-group :label="__('Maximum ID')">
        <x-input wire:model.defer="filters.max-id" type="number" min="0" id="max_id" />
    </x-form-group>
</x-slot:filters>
