@props([
    'role' => 'cell',
    'colIndex' => false,
    'hidden' => false,
])

@unless($hidden)
<td @if ($role) role="{{ $role }}" @endif
    @if ($colIndex) aria-colindex="{{ $colIndex }}" @endif
    {{ $attributes->merge(['class' => 'px-6 py-4 whitespace-nowrap text-sm leading-5']) }}
>
    {{ $slot }}
</td>
@endunless
