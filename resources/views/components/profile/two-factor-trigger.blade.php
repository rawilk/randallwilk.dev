@props([
    'action' => '',
    'variant' => '',
    'confirmEnabled' => true,
    'confirmableId' => null,
])

@if ($confirmEnabled)
    <x-confirms-password wire:then="{{ $action }}" :confirmable-id="$confirmableId">
        <x-button variant="{{ $variant }}" wire:target="{{ $action }}" {{ $attributes }}>
            {{ $slot }}
        </x-button>
    </x-confirms-password>
@else
    <x-button variant="{{ $variant }}" wire:click="{{ $action }}" wire:target="{{ $action }}" {{ $attributes }}>
        {{ $slot }}
    </x-button>
@endif
