@props([
    'role' => 'row',
    'tabIndex' => false,
    'rowIndex' => false,
    'striped' => true,
    'selected' => false,
])

@php
    $trClasses = collect([
        $striped && ! $selected ? 'odd:bg-white even:bg-blue-gray-50' : null,
        $selected ? 'bg-warning-100': null,
    ])->filter()->implode(' ');
@endphp

<tr @if ($role) role="{{ $role }}" @endif
    @if ($tabIndex !== false) tabindex="{{ $tabIndex }}" @endif
    @if ($rowIndex) aria-rowindex="{{ $rowIndex }}" @endif
    {{ $attributes->merge(['class' => $trClasses]) }}
>
    {{ $slot }}
</tr>
