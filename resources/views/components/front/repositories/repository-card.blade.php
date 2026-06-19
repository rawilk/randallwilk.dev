@props([
    'repository',
    'showDownloads' => false,
])

@php
    use Filament\Support\Icons\Heroicon;

    /** @var \App\Models\Repository $repository */
@endphp

<a
    href="{{ $repository->url }}"
    target="_blank"
    rel="noopener"
    {{
        $attributes
            ->class('group items-center gap-4 py-3 px-3 border-b border-gray-300 hover:bg-gray-600/5 transition break-inside-avoid')
    }}
>
    <div class="flex-1 min-w-0">
        <div class="leading-normal">
            <h3 class="text-sm font-semibold text-black truncate">{{ $repository->name }}</h3>

            @if ($repository->description)
                <p class="text-sm text-gray-500 truncate font-light">{{ $repository->description }}</p>
            @endif
        </div>
    </div>

    @if ($showDownloads)
        <span class="shrink-0 inline-flex items-center gap-1 text-xs font-semibold text-black uppercase">
            <x-filament::icon
                class="size-3"
                :icon="Heroicon::ArrowDownTray"
            />

            {{ Number::abbreviate($repository->downloads, maxPrecision: 1) }}
        </span>
    @endif

    <x-filament::icon
        :icon="Filament\Support\Icons\Heroicon::ChevronRight"
        class="pointer-events-none size-5 text-black fill-current shrink-0"
    />
</a>
