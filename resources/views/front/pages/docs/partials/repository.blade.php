@use(App\Http\Controllers\Docs\DocsController)
@use(Illuminate\Support\Number)
@use(Illuminate\Support\Str)
@use(Filament\Support\Icons\Heroicon)

@php
    $slogan = $repository->aliases->last()?->slogan;
    $stars =  $repository->stars;
    $starsLabel = $stars === null ? null : Str::lower(Number::abbreviate($stars, maxPrecision: 1));
    $initiallyVisible ??= true;
@endphp

<a
    href="{{ action([DocsController::class, 'repository'], $repository->slug) }}"
    @class([
        'group min-h-20 grid-cols-[minmax(0,1fr)_3rem_auto] items-center gap-4 border-b border-gray-300 px-3 py-3 transition hover:bg-gray-600/5',
        'grid' => $initiallyVisible,
        'hidden' => ! $initiallyVisible,
    ])
    x-bind:class="{
        'grid': (! query) || haystacks[{{ $repositoryIndex }}].includes(query.toLowerCase()),
        'hidden': query && (! haystacks[{{ $repositoryIndex }}].includes(query.toLowerCase())),
    }"
>
    <div class="flex-1 min-w-0">
        <div class="flex min-w-0 items-center gap-2 leading-normal">
            <h3 class="min-w-0 truncate text-sm font-semibold text-black">{{ $repository->slug }}</h3>

            @if ($repository->isArchived())
                <x-filament::badge
                    :color="Filament\Support\Colors\Color::Amber"
                    class="shrink-0"
                >
                    Archived
                </x-filament::badge>
            @endif
        </div>

        @if ($slogan)
            <p class="text-sm font-light text-gray-600 truncate">{{ $slogan }}</p>
        @endif
    </div>

    <span class="inline-flex w-12 shrink-0 items-center justify-end gap-1 text-xs font-semibold uppercase text-black">
        @if ($starsLabel)
            <x-filament::icon
                :icon="Heroicon::Star"
                class="size-3.5"
            />

            {{ $starsLabel }}
        @endif
    </span>

    <x-filament::icon
        :icon="Filament\Support\Icons\Heroicon::ChevronRight"
        class="pointer-events-none size-5 text-black fill-current shrink-0"
    />
</a>
