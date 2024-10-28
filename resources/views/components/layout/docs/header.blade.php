@props([
    'repository',
    'alias',
    'page',
    'githubUrl' => null,
    'mobileNav' => null,
])

<header
    {{
        $attributes
            ->merge([
                'id' => 'header',
                'x-data' => 'header({ threshold: 0 })',
                'x-on:scroll.window.passive' => 'handleScroll',
                'x-bind:class' => <<<'HTML'
                { 'dark:bg-slate-900/5 dark:backdrop-blur dark:[@supports(backdrop-filter:blur(0))]:bg-slate-900/75 dark:border-b-slate-700': scrolled, 'dark:bg-transparent dark:border-b-transparent': ! scrolled }
                HTML,
            ], escape: false)
            ->class([
                'sticky top-0 z-50 px-4 py-3 transition duration-500',
                'sm:px-6 lg:px-8',
                'bg-white border-b border-b-gray-300',
            ])
    }}
>
    <div class="w-full max-w-8xl mx-auto flex flex-wrap gap-5 items-center justify-between sm:flex-nowrap">
        {{-- logo/mobile-nav trigger --}}
        <div class="relative flex flex-grow basis-0 items-center gap-3">
            <x-layout.docs.mobile-navigation>
                {{ $mobileNav }}
            </x-layout.docs.mobile-navigation>

            <div class="flex gap-3 items-center">
                <a
                    href="{{ route('home') }}"
                    aria-label="Homepage"
                    class="transition duration-500 ease-out hidden logo-min:block motion-reduce:transition-none text-slate-600 dark:text-white"
                >
                    <x-logo
                        type="mark-dual"
                        class="h-10 w-10 lg:hidden"
                    />

                    <x-logo
                        type="dual"
                        class="hidden lg:block h-[30px] w-auto"
                    />
                </a>

                <a
                    href="{{ route('docs') }}"
                    aria-label="Docs homepage"
                    class="transition duration-500 ease-out motion-reduce:transition-none hover:translate-x-1 lg:ml-2"
                >
                    <div class="uppercase font-black text-xl text-brand dark:text-white">
                        Docs
                    </div>
                </a>
            </div>
        </div>

        {{-- utilities --}}
        <div class="relative flex flex-1 justify-end gap-3.5 items-center">
            <x-layout.docs.version-selector
                :repository="$repository"
                :page="$page"
                class="relative z-10"
            />

            <x-layout.docs.theme-selector
                class="relative z-10"
            />

            <x-layout.docs.search
                :repository="$repository"
                :alias="$alias"
            />

            @if (filled($githubUrl))
                <a
                    href="{{ $githubUrl }}"
                    target="_blank"
                    rel="noopener nofollow"
                    class="group/github-link block"
                    aria-label="GitHub"
                >
                    <x-svg-github-docs
                        class="h-7 w-7 transition duration-200 fill-slate-500 group-hover/github-link:fill-slate-600 dark:fill-gray-300 dark:group-hover/github-link:fill-white"
                    />
                </a>
            @endif
        </div>
    </div>
</header>
