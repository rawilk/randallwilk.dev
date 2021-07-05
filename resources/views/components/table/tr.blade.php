@props([
    'role' => 'row',
    'tabIndex' => false,
    'rowIndex' => false,
    'striped' => true,
    'selected' => false,
    'wireLoads' => false, // Automatically add a wire:loading directive if true
])

@php
    $trClasses = collect([
        $striped && ! $selected ? 'odd:bg-white even:bg-blue-gray-50' : null,
        $selected ? 'bg-orange-100' : null,
    ])->filter()->implode(' ');
@endphp

<tr @if ($role) role="{{ $role }}" @endif
    @if ($tabIndex !== false) tabindex="{{ $tabIndex }}" @endif
    @if ($rowIndex) aria-rowindex="{{ $rowIndex }}" @endif
    @if ($wireLoads) wire:loading.class.delay="opacity-50" @endif
    {{ $attributes->merge(['class' => $trClasses]) }}
>
    {{ $slot }}
</tr>
