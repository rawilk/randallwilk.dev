<header
    x-data="docsHeader"
    x-on:scroll.window.passive="onScroll"
    class="sticky top-0 z-50 flex flex-wrap items-center justify-between bg-white px-4 py-5 shadow-md shadow-slate-900/5 transition duration-500 dark:shadow-none sm:px-6 lg:px-8"
    x-bind:class="{ 'dark:bg-slate-900/5 dark:backdrop-blur dark:[@supports(backdrop-filter:blur(0))]:bg-slate-900/75': isScrolled, 'dark:bg-transparent': ! isScrolled }"
>
    {{-- mobile nav --}}
    @include('layouts.docs.partials.mobile-navigation')

    {{-- logo --}}
    <div class="relative flex flex-grow basis-0 items-center">
        <a href="{!! route('home') !!}"
           aria-label="Home"
        >
            <x-logo
                type="mark-dual"
                class="h-10 w-10 lg:hidden text-slate-600 dark:text-white"
            />
            <x-logo
                type="dual"
                class="hidden h-9 w-auto lg:block text-slate-600 dark:text-white"
            />
        </a>
    </div>

    {{-- search --}}
    <div class="-my-5 mr-6 sm:mr-8 md:mr-0">
        @include('layouts.docs.partials.search')
    </div>

    {{-- theme selector/GitHub link --}}
    <div class="relative flex basis-0 justify-end gap-6 sm:gap-8 md:flex-grow">
        @include('layouts.docs.partials.theme-selector')

        @isset($githubUrl)
            <a href="{{ $githubUrl }}"
               class="group"
               aria-label="{{ __('GitHub Repository') }}"
            >
                <x-svg-github-docs
                    class="h-6 w-6 fill-slate-400 group-hover:fill-slate-500 dark:group-hover:fill-slate-300"
                />
            </a>
        @endisset
    </div>
</header>
