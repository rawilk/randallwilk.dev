@props([
    'searchPlaceholder' => __('labels.search_placeholder'),
    'searchModel' => 'filters.search',
    'showPerPage' => true,
    'perPageModel' => 'perPage',
    'showColumnFilter' => true,
])

<div class="space-y-4 lg:space-y-0 lg:flex lg:justify-between">
    <div class="w-full lg:w-1/3">
        <div class="w-full">
            <x-input wire:model.debounce.500ms="{{ $searchModel }}" type="search" placeholder="{{ $searchPlaceholder }}">
                <x-slot name="leadingIcon">
                    <x-heroicon-s-search />
                </x-slot>
            </x-input>
        </div>
    </div>

    <div class="space-x-2 flex items-center">
        @if ($showPerPage)
            <x-form-group label="{{ __('Per Page') }}" inline>
                <x-select wire:model="{{ $perPageModel }}" name="perPage">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                </x-select>
            </x-form-group>
        @endif

        {{ $slot }}

        @if ($showColumnFilter)
            @include('admin.partials.column-select')
        @endif
    </div>
</div>
