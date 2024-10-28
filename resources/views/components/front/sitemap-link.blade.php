@props([
    'url' => '#',
    'spa' => true,
])

<li>
    <a href="{{ $url }}" @if ($spa) wire:navigate @endif>{{ $slot }}</a>
</li>
