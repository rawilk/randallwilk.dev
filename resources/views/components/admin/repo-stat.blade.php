@props([
    'actionUrl' => false,
    'condition' => null,
    'missingText' => '',
    'icon' => '',
    'offIcon' => '',
])

<div class="flex space-x-2 items-center">
    @if ($condition ===  null)
        <x-dynamic-component :component="$icon" class="h-4 w-4 text-slate-500" />
        @if ($actionUrl)
            <x-link dark target="_blank" :href="$actionUrl">
                {{ $slot }}
            </x-link>
        @else
            <span class="text-slate-600 font-medium">{{ $missingText }}</span>
        @endif
    @else
        <x-dynamic-component :component="$condition ? $icon : $offIcon" class="h-4 w-4 text-slate-500" />
        <span class="text-slate-600 font-medium">
            @unless ($condition)
                {{ $missingText }}
            @else
                {{ $slot }}
            @endif
        </span>
    @endif
</div>
