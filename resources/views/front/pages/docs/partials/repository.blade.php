<x-front.section-list-link-item
    url="{{ action([\App\Http\Controllers\Docs\DocsController::class, 'repository'], $repository->slug) }}"
>
    <x-slot:title>
        {{ $repository->slug }}

        @if ($repository->isArchived())
            <x-badge variant="orange">{{ __('front.docs.repo_archived') }}</x-badge>
        @endif
    </x-slot:title>

    <p class="relative mt-2 text-sm z-20 text-slate-600">
        {{ $repository->aliases->last()?->slogan }}
    </p>

    <div class="relative mt-4 grid grid-flow-col gap-2 justify-start items-center text-xs">
        @foreach ($repository->aliases as $alias)
            <div class="z-20">
                <a href="{{ action([\App\Http\Controllers\Docs\DocsController::class, 'repository'], [$repository->slug, $alias->slug]) }}"
                    @class([
                        'inline-flex items-center justify-center rounded-full w-4 h-4 bg-opacity-50 hover:bg-opacity-100 p-3',
                        'bg-blue-100 text-blue-300 font-bold' => $loop->first,
                        'bg-gray-200 text-gray-400' => ! $loop->first,
                    ])
                >
                    {{ $alias->slug }}
                </a>
            </div>
        @endforeach
    </div>
</x-front.section-list-link-item>
