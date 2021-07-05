@props([
    'href' => false,
])

@if ($href)
    <a role="menuItem"
       href="{{ $href }}"
       tabindex="-1"
       {{ $attributes->class('dropdown-item space-x-2') }}
    >
        {{ $slot }}
    </a>
@else
    <button type="button"
            role="menuitem"
            tabindex="-1"
            {{ $attributes->class('w-full dropdown-item space-x-2') }}
    >
        {{ $slot }}
    </button>
@endif
