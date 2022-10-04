@props([
    'url' => '#',
])

<li>
    <a href="{{ $url }}">{{ $slot }}</a>
</li>
