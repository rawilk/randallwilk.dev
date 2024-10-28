@use(App\Http\Controllers\Docs\DocsController)

<x-front.oss-link-card
    :title="$repository->slug"
    :href="action([DocsController::class, 'repository'], $repository->slug)"
    :badge="$repository->isArchived() ? 'Archived' : null"
    :badge-color="Filament\Support\Colors\Color::Amber"
>
    <div class="h-full flex flex-col">
        <p class="mb-12">
            {{ $repository->aliases->last()?->slogan }}
        </p>

        <div class="flex flex-wrap items-center gap-2 mt-auto">
            @foreach ($repository->aliases as $alias)
                <span>
                    <a
                        href="{{ action([DocsController::class, 'repository'], [$repository->slug, $alias->slug]) }}"
                        @class([
                            'relative z-20',
                            'inline-flex items-center justify-center rounded-full font-bold w-6 h-6',
                            'bg-brand text-white text-[11px]' => $loop->first,
                            'bg-transparent text-black text-[14px]' => ! $loop->first,
                        ])
                    >
                        {{ $alias->slug }}
                    </a>
                </span>
            @endforeach
        </div>
    </div>
</x-front.oss-link-card>
