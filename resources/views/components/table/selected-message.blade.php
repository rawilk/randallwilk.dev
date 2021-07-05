@props([
    'colspan' => 1,
    'selectPage' => false,
    'selectAll' => false,
    'count' => 0,
    'itemName' => 'rows',
    'total' => 0,
])

@if ($selectPage)
<x-tr wire:key="row-message">
    <x-td class="bg-blue-gray-200" colspan="{{ $colspan }}">

        @unless($selectAll)
            <div class="space-x-1">
                <span>
                    {!! __('All <strong>:count</strong> :item_name on this page are selected.', ['count' => $count, 'item_name' => $itemName]) !!}
                </span>

                <x-button.link wire:click="selectAll">
                    {!! __('Select all <strong>:total</strong> :item_name', ['total' => $total, 'item_name' => $itemName]) !!}
                </x-button.link>
            </div>
        @else
            <span class="block space-x-1">
                <span>{!! __('All <strong>:total</strong> :item_name are selected.', ['total' => $total, 'item_name' => $itemName]) !!}</span>

                <x-button.link wire:click="clearSelection">
                    {!! __('Clear selection') !!}
                </x-button.link>
            </span>
        @endif

    </x-td>
</x-tr>
@endif
