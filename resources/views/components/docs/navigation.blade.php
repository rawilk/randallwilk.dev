@props([
    'navigation',
    'page',
    'repository',
    'latestVersion',
    'type',
])

<nav {{ $attributes->class('text-base lg:text-sm') }}>
    {{-- version select --}}
    <div class="mb-5">
        <label class="sr-only" for="version-switcher-{{ $type }}">{{ __('front.docs.version_select_label') }}</label>
        <div x-data class="relative w-full">
            <x-select name="version-switcher"
                      id="version-switcher-{{ $type }}"
                      x-on:change="window.location = $el.value"
                      class="w-full rounded-md hover:cursor-pointer focus:!ring-1 focus:!ring-slate-300 focus:!border-slate-300 !text-sm !ring-1 !ring-slate-200 hover:!ring-slate-300 dark:!bg-slate-800/75 dark:!ring-inset dark:!ring-white/5 dark:hover:!bg-slate-700/40 dark:hover:!ring-slate-500 !text-slate-500 dark:!text-slate-400 dark:!border-slate-800"
            >
                @foreach ($repository->aliases as $aliasOption)
                    <option
                        value="{{ action([\App\Http\Controllers\Docs\DocsController::class, 'repository'], [$repository->slug, $aliasOption->slug]) }}"
                        @selected($page->alias === $aliasOption->slug)
                    >
                        {{ $aliasOption->slug }}
                        @if ($latestVersion->slug === $aliasOption->slug)
                            ({{ $aliasOption->branch }})
                        @endif
                    </option>
                @endforeach
            </x-select>
        </div>
    </div>

    <ul role="list" class="space-y-9">
        @foreach ($navigation as $key => $section)
            <li>
                <h2 class="font display font-medium text-slate-900 dark:text-white">
                    {{ $key === '_root' ? __('Introduction') : $section['_index']['title'] }}
                </h2>

                <ul role="list"
                    class="mt-2 space-y-2 border-l-2 border-slate-100 dark:border-slate-800 lg:mt-4 lg:space-y-4 lg:border-slate-200"
                >
                    @foreach ($section['pages'] as $navItem)
                        <li class="relative">
                            <a href="{{ $navItem->url }}"
                                @class([
                                    'block w-full pl-3.5 before:pointer-events-none before:absolute before:-left-1 before:top-1/2 before:h-1.5 before:w-1.5 before:-translate-y-1/2 before:rounded-full',
                                    'font-semibold text-sky-500 before:bg-sky-500' => $navItem->slug === $page->slug,
                                    'text-slate-500 before:hidden before:bg-slate-300 hover:text-slate-600 hover:before:block dark:text-slate-400 dark:before:bg-slate-700 dark:hover:text-slate-300' => $navItem->slug !== $page->slug,
                                ])
                            >
                                {{ $navItem->title }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </li>
        @endforeach
    </ul>
</nav>
