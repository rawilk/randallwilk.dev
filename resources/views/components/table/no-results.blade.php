@props([
    'colspan' => 1,
])

<x-tr wire:key="no-results-row">
    <x-td colspan="{{ $colspan }}">
        <div class="flex justify-center items-center space-x-2">
            <x-heroicon-o-database class="h-9 w-9 text-blue-gray-400" />

            <span class="font-medium py-8 text-blue-gray-400 text-xl">
                {{ $slot }}
            </span>
        </div>
    </x-td>
</x-tr>
