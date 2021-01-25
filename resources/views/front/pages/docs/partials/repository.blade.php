<x-elements.action-item href="{{ action([\App\Http\Controllers\DocsController::class, 'repository'], $repository->slug) }}">
    @if ($repository->getIcon())
        <x-slot name="before">
            <div class="rounded-lg inline-flex p-3 ring-white {{ $repository->iconClass() }}">
                {{ renderSvg($repository->getIcon()) }}
            </div>
        </x-slot>
    @endif

    <div class="flex items-center space-x-2">
        <span>{{ $repository->slug }}</span>

        @if ($repository->isArchived())
            <x-badge class="mt-1" variant="orange">{{ __('front.repositories.archived') }}</x-badge>
        @endif
    </div>

    <x-slot name="description">
        {{ $repository->aliases->last()?->slogan }}
    </x-slot>

    <x-slot name="extra">
        <div class="mt-6 flex items-center">
            <div class="text-xs grid grid-flow-col gap-2 justify-start items-center">
                @foreach ($repository->aliases as $alias)
                    <span>
                        <a class="inline-flex items-center justify-center rounded-full w-6 h-6 bg-opacity-50 hover:bg-opacity-100 {{ $loop->first ? 'bg-blue-100 text-blue-300 font-bold' : 'bg-gray-200 text-gray-400' }}"
                           href="{{ action([\App\Http\Controllers\DocsController::class, 'repository'], [$repository->slug, $alias->slug]) }}"
                        >
                            {{ $alias->slug }}
                        </a>
                    </span>
                @endforeach
            </div>
        </div>
    </x-slot>
</x-elements.action-item>
