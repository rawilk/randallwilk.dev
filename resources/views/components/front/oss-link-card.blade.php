@props([
    'tag' => 'div',
    'href' => null,
    'title' => null,
    'badge' => null,
    'badgeColor' => Filament\Support\Colors\Color::Blue,
])

<div
    {{
        $attributes
            ->class([
                'relative flex flex-col group rounded-[20px] p-7 pt-8 text-[14px] leading-normal',
                'border border-gray-300 rounded-lg h-full bg-gray-50/50 hover:bg-gray-100 hover:border-gray-400',
                'text-black',
            ])
            ->except(['href', 'target'])
    }}
>
    <h3 class="text-[18px] mb-3 leading-tight static">
        @if ($href)
            <a
                href="{{ $href }}"
                class="static"
                {{ $attributes->only(['target']) }}
            >
                <span class="absolute inset-0 z-10 rounded-lg"></span>
                <span class="relative z-10">{{ $title }}</span>
            </a>
        @else
            {{ $title }}
        @endif
    </h3>

    <div class="h-full">
        {{ $slot }}
    </div>

    @if ($badge)
        <x-filament::badge
            class="absolute top-1.5 right-3 text-xs"
            :color="$badgeColor"
        >
            {{ $badge }}
        </x-filament::badge>
    @endif
</div>
