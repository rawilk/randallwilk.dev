@props([
    'colspan' => 1,
    'selectPage' => false,
    'selectAll' => false,
    'count' => 0,
    'itemName' => 'rows',
    'total' => 0,
])

@if ($selectPage)
<x-tr wire:key="row-select-message">
    <x-td class="bg-blue-gray-200" colspan="{{ $colspan }}">

        @unless ($selectAll)
            <div class="space-x-1">
                <span>
                    {!! __('labels.tables.page_selected_info', ['count' => $count, 'item_name' => $itemName]) !!}
                </span>

                <x-button.link wire:click="selectAll">
                    {!! __('labels.tables.select_all_button', ['total' => $total, 'item_name' => $itemName]) !!}
                </x-button.link>
            </div>
        @else
            <span class="block space-x-1">
                <span>{!! __('labels.tables.all_selected_info', ['total' => $total, 'item_name' => $itemName]) !!}</span>

                <x-button.link wire:click="clearSelection">
                    {!! __('labels.tables.clear_selection_button') !!}
                </x-button.link>
            </span>
        @endif

    </x-td>
</x-tr>
@endif
