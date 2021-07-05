@props([
    'searchPlaceholder' => __('Search...'),
    'searchModel' => 'filters.search',
    'showPerPage' => true,
    'perPageModel' => 'perPage',
    'showColumns' => true,
    'hideableColumns' => [],
    'hiddenColumns' => [],
])

<div class="space-y-4 lg:space-y-0 lg:flex lg:justify-between">
    <div class="w-full lg:w-1/3">
        <div class="w-full">
            <x-input wire:model.debounce.500ms="{{ $searchModel }}" type="search" placeholder="{{ $searchPlaceholder }}">
                <x-slot name="leadingIcon">
                    <x-css-search />
                </x-slot>
            </x-input>
        </div>
    </div>

    <div class="space-x-2 flex items-center">
        @if ($showPerPage)
            <x-form-group label="{{ __('Per page') }}" :margin-bottom="false" inline>
                <x-select wire:model="{{ $perPageModel }}" name="perPage">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                </x-select>
            </x-form-group>
        @endif

        {{ $slot }}

        @if ($showColumns)
            <x-column-select :columns="$hideableColumns" :hidden="$hiddenColumns" />
        @endif
    </div>
</div>
