@props([
    'role' => 'menuitem',
    'href' => false,
    'spaceX' => '2',
    'type' => 'button',
])

@if ($href)
    <a role="{{ $role }}"
       href="{{ $href }}"
       {{ $attributes->merge(['class' => 'dropdown-item space-x-' . $spaceX]) }}
    >
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}"
            role="{{ $role }}"
            {{ $attributes->merge(['class' => 'w-full dropdown-item space-x-' . $spaceX]) }}
    >
        {{ $slot }}
    </button>
@endif
