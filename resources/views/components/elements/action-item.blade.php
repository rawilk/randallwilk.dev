@props([
    'icon' => false,
    'iconClass' => 'bg-primary-50 text-primary-700 ring-white',
    'href' => '#',
    'description' => false,
    'extra' => false,
    'before' => false,
])

<div {{ $attributes->merge(['class' => 'action-item group']) }}
     x-data
     x-on:click="$refs.link.focus(); $refs.link.click()"
>
    @if ($before)
        <div>{{ $before }}</div>
    @endif

    @if ($icon)
        <div class="rounded-lg inline-flex p-3 {{ $iconClass }}">
            <x-dynamic-component :component="$icon" class="h-6 w-6" />
        </div>
    @endif

    <div @if ($icon || $before) class="mt-8" @endif>
        <h3 class="text-lg font-medium">
            <a href="{{ $href }}" class="focus:outline-none" x-ref="link">
                {{-- extended touch target to entire panel --}}
                <span class="absolute inset-0" aria-hidden="true"></span>
                {{ $slot }}
            </a>
        </h3>

        @if ($description)
            <p class="mt-2 text-sm text-blue-gray-500">{{ $description }}</p>
        @endif

        @if ($extra)
            <div>{{ $extra }}</div>
        @endif
    </div>

    {{-- arrow up right --}}
    <span class="pointer-events-none absolute top-6 right-6 text-blue-gray-300 group-hover:text-blue-gray-400" aria-hidden="true">
        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
            <path d="M20 4h1a1 1 0 00-1-1v1zm-1 12a1 1 0 102 0h-2zM8 3a1 1 0 000 2V3zM3.293 19.293a1 1 0 101.414 1.414l-1.414-1.414zM19 4v12h2V4h-2zm1-1H8v2h12V3zm-.707.293l-16 16 1.414 1.414 16-16-1.414-1.414z" />
        </svg>
    </span>
</div>
