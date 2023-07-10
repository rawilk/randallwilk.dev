<x-slot:filters>
    {{-- type --}}
    <x-form-group :label="__('Type')" name="filters.type">
        <x-select name="filters.type" wire:model.defer="filters.type">
            <option value="">{{ __('labels.option_any') }}</option>
            @foreach (\App\Enums\RepositoryTypeEnum::cases() as $case)
                <option value="{{ $case->value }}">{{ $case->label() }}</option>
            @endforeach
            <option value="missing">{{ __('repos.labels.missing_type') }}</option>
        </x-select>
    </x-form-group>

    {{-- visible --}}
    <x-form-group :label="__('Visible')" name="filters.visible">
        <x-select wire:model.defer="filters.visible" name="filters.visible">
            <option value="">{{ __('labels.option_any') }}</option>
            <option value="0">{{ __('labels.option_no') }}</option>
            <option value="1">{{ __('labels.option_yes') }}</option>
        </x-select>
    </x-form-group>

    {{-- new --}}
    <x-form-group :label="__('Marked New')" name="filters.new">
        <x-select wire:model.defer="filters.new" name="filters.new">
            <option value="">{{ __('labels.option_any') }}</option>
            <option value="0">{{ __('labels.option_no') }}</option>
            <option value="1">{{ __('labels.option_yes') }}</option>
        </x-select>
    </x-form-group>

    {{-- featured --}}
    <x-form-group :label="__('Marked as Featured')" name="filters.highlighted">
        <x-select wire:model.defer="filters.highlighted" name="filters.highlighted">
            <option value="">{{ __('labels.option_any') }}</option>
            <option value="0">{{ __('labels.option_no') }}</option>
            <option value="1">{{ __('labels.option_yes') }}</option>
        </x-select>
    </x-form-group>

    {{-- has docs --}}
    <x-form-group :label="__('Has Documentation')" name="filters.docs">
        <x-select wire:model.defer="filters.docs" name="filters.docs">
            <option value="">{{ __('labels.option_any') }}</option>
            <option value="0">{{ __('labels.option_no') }}</option>
            <option value="1">{{ __('labels.option_yes') }}</option>
        </x-select>
    </x-form-group>

    {{-- has blog post --}}
    <x-form-group :label="__('Has Blog Post')" name="filters.blogpost">
        <x-select wire:model.defer="filters.blogpost" name="filters.blogpost">
            <option value="">{{ __('labels.option_any') }}</option>
            <option value="0">{{ __('labels.option_no') }}</option>
            <option value="1">{{ __('labels.option_yes') }}</option>
        </x-select>
    </x-form-group>
</x-slot:filters>
