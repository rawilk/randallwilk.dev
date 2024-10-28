@props([
    'repository',
    'page',
])

<div {{ $attributes }}>
    <x-filament::dropdown
        placement="bottom-center"
        width="max-w-[9rem]"
        teleport
    >
        <x-slot:trigger>
            <p class="sr-only">Version</p>
            <button
                type="button"
                aria-label="Open version options"
                class="flex h-9 px-3 items-center justify-center text-sm whitespace-nowrap rounded-full ring-1 ring-black/15 dark:ring-inset dark:bg-slate-700 dark:ring-white/15"
            >
                <span class="hidden sm:block">Version&nbsp;</span>
                <span id="current-version" class="font-bold">{{ $page->versionSelectAlias() }}</span>
            </button>
        </x-slot:trigger>

        <x-filament::dropdown.list aria-label="Version options" class="py-1.5">
            @foreach ($repository->aliases as $alias)
                <a
                    href="{{ action([App\Http\Controllers\Docs\DocsController::class, 'repository'], [$repository->slug, $alias->slug]) }}"
                    @class([
                        'flex justify-center items-center rounded-[0.5rem] py-1 px-3 text-sm w-full hover:bg-gray-100 dark:hover:bg-white/5 transition duration-200',
                        'text-sky-500 font-semibold' => $alias->slug === $page->alias,
                        'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white' => $alias->slug !== $page->alias,
                    ])
                >
                    <span>Version&nbsp;</span>
                    <span>{{ $alias->versionSelectAlias() }}</span>
                </a>
            @endforeach
        </x-filament::dropdown.list>
    </x-filament::dropdown>
</div>
