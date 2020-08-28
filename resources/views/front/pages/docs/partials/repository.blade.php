<div class="flex flex-col rounded-lg shadow-md overflow-hidden">
    <div class="flex-1 bg-white p-6 flex flex-col justify-between">
        <div class="flex-1">
            <a href="{{ action([\App\Http\Controllers\DocsController::class, 'repository'], $repository->slug) }}">
                <h2 class="text-xl leading-7 link-black link-underline">{{ $repository->slug }}</h2>
            </a>
            <p class="mt-3 text-base leading-6 text-gray-500">{{ $repository->aliases->last()->slogan }}</p>
        </div>

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
    </div>
</div>
