@props([
    'navigation',
    'page',
    'repository',
    'type',
])

<nav {{ $attributes->class('text-base lg:text-sm') }}>
    <ul
        role="list"
        class="mb-9 space-y-8"
        @if ($type === 'mobile')
            x-data
            x-on:livewire:navigating.window="$store?.mobileNav?.close()"
        @endif
    >
        @foreach ($navigation as $key => $section)
            <li>
                <h2 class="font-display font-medium text-slate-900 dark:text-white">
                    {{ $key === '_root' ? 'Introduction' : $section['_index']['title'] }}
                </h2>

                <ul
                    role="list"
                    class="mt-2 space-y-2 border-l-2 border-slate-100 dark:border-slate-800 lg:mt-4 lg:space-y-4 lg:border-slate-200"
                >
                    @foreach ($section['pages'] as $navItem)
                        <li class="relative">
                            <a
                                href="{{ $navItem->url }}"
                                @class([
                                    'block w-full pl-3.5 before:pointer-events-none before:absolute before:-left-1 before:top-1/2 before:h-1.5 before:w-1.5 before:-translate-y-1/2 before:rounded-full',
                                    'font-semibold text-sky-500 before:bg-sky-500' => $navItem->slug === $page->slug,
                                    'text-slate-500 before:hidden before:bg-slate-300 hover:text-slate-600 hover:before:block dark:text-slate-400 dark:before:bg-slate-700 dark:hover:text-slate-300' => $navItem->slug !== $page->slug,
                                ])
                                wire:navigate
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
