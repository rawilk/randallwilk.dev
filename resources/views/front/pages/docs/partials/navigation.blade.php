<div x-data="{ open: false }"
     x-on:docs-nav-open.window="open = $event.detail"
     x-show="open"
     class="lg:hidden w-20"
>
    {{-- dummy element to prevent main content from moving when mobile menu is opened --}}
</div>

<aside x-data="{ navIsOpen: false }"
       x-on:click.away="navIsOpen = false"
       x-on:keydown.window.escape="navIsOpen = false"
       x-bind:class="{ 'w-64': navIsOpen, 'sticky': ! navIsOpen }"
       x-init="
           $watch('navIsOpen', open => {
               $dispatch('docs-nav-open', open);

               if (open) {
                   document.body.classList.add('overflow-hidden');
               } else {
                   document.body.classList.remove('overflow-hidden');
               }
           });
       "
       class="fixed top-0 bottom-0 left-0 z-20 min-h-screen h-screen overflow-auto w-16
       flex flex-col bg-gray-100 border-r border-gray-200
       transition-all duration-300
       lg:sticky lg:w-80 lg:flex-shrink-0 lg:flex lg:justify-end lg:items-end 2xl:max-w-lg 2xl:w-full
       "
>
    <div class="relative min-h-0 flex-1 flex flex-col xl:w-80">
        <div class="flex flex-col h-screen overflow-y-auto lg:h-auto lg:overflow-y-visible">
            {{-- logo --}}
            <a href="{!! route('home') !!}" class="flex items-center py-8 px-4 lg:px-8 xl:px-16">
                <img x-show="! navIsOpen" src="{!! asset('images/logomark.png') !!}" alt="{{ __('front.logo_alt') }}" class="h-6 flex-shrink-0 transition-all duration-300 lg:hidden">
                <img x-show="navIsOpen" x-cloak src="{!! asset('images/logo.png') !!}" alt="{{ __('front.logo_alt') }}" class="transition-all duration-300 lg:hidden">
                <img src="{!! asset('images/logo.png') !!}" alt="{{ __('front.logo_alt') }}" class="hidden ml-4 lg:block">
            </a>

            {{-- nav --}}
            <div class="overflow-y-auto overflow-x-hidden px-4 lg:overflow-hidden lg:px-8 xl:px-16">
                {{-- mobile nav --}}
                <nav x-cloak
                     x-show="navIsOpen"
                     class="mt-4 block lg:hidden"
                >
                    <div class="docs-sidebar">
                        @include('front.pages.docs.partials.doc-links')
                    </div>
                </nav>

                {{-- desktop nav --}}
                <nav id="indexed-nav" class="hidden lg:block lg:mt-4">
                    <div class="docs-sidebar">
                        @include('front.pages.docs.partials.doc-links')
                    </div>
                </nav>
            </div>

            {{-- mobile nav toggle --}}
            <div class="sticky bottom-0 flex-1 flex flex-col justify-end lg:hidden">
                <div class="py-4 px-4">
                    <button class="relative ml-1 w-6 h-6 text-blue-500 lg:hidden focus:outline-none focus:shadow-outline"
                            aria-label="Menu"
                            x-on:click.prevent="navIsOpen = ! navIsOpen"
                    >
                        <x-css-close class="absolute inset-0 w-6 h-6" x-show.transition.opacity="navIsOpen" x-cloak />
                        <x-css-menu-right-alt class="absolute inset-0 w-6 h-6" x-show.transition.opacity="! navIsOpen" />
                    </button>
                </div>
            </div>
        </div>

    </div>
</aside>

{{--<nav class="hidden h-full pr-4 py-6">--}}
{{--    <div class="flex items-center pb-4 border-b-2 border-gray-300">--}}
{{--        <div class="text-xs font-normal leading-normal select pl-0">--}}
{{--            <select name="alias"--}}
{{--                    class="form-select"--}}
{{--                    onchange="location = '/docs/{{ $repository->slug }}/' + this.options[this.selectedIndex].value"--}}
{{--            >--}}
{{--                @foreach ($repository->aliases as $aliasOption)--}}
{{--                    <option value="{{ $aliasOption->slug }}"--}}
{{--                            {{ $page->alias === $aliasOption->slug ? 'selected' : '' }}--}}
{{--                    >--}}
{{--                        {{ $aliasOption->slug }} @if ($aliasOption->isMasterBranch())({{ $aliasOption->branch }})@endif--}}
{{--                    </option>--}}
{{--                @endforeach--}}
{{--            </select>--}}
{{--        </div>--}}

{{--        <a class="ml-auto flex items-center text-xs link-gray link-underline"--}}
{{--           href="{{ $alias->githubUrl }}/blob/{{ $alias->isMasterBranch() ? 'master' : $alias->slug }}/docs/{{ $page->slug }}.md"--}}
{{--           target="_blank"--}}
{{--        >--}}
{{--            Edit--}}

{{--            <span class="ml-1 w-4 h-4">--}}
{{--                {{ renderSvg('github') }}--}}
{{--            </span>--}}
{{--        </a>--}}
{{--    </div>--}}

{{--    <div class="sticky top-0 pt-4 space-y-8">--}}
{{--        --}}{{--<input type="search"--}}
{{--        --}}{{--       id="algolia-search"--}}
{{--        --}}{{--       placeholder="Search..."--}}
{{--        --}}{{--       class="text-xs form-input w-full h-8 py-0 px-2"--}}
{{--        --}}{{-->--}}

{{--        <ol class="text-xs grid gap-2 links-black">--}}
{{--            @foreach ($navigation as $key => $section)--}}
{{--                @if ($key !== '_root')--}}
{{--                    <h2 class="title-sm text-sm">{{ $section['_index']['title'] }}</h2>--}}
{{--                @endif--}}

{{--                <ul class="mb-6 space-y-1 links-black @if ($key !== '_root') pl-3 @endif">--}}
{{--                    @foreach ($section['pages'] as $navItem)--}}
{{--                        <li class="leading-snug">--}}
{{--                            <a href="{{ $navItem->url }}"--}}
{{--                               class="@if ($page->slug === $navItem->slug) font-bold @endif"--}}
{{--                            >--}}
{{--                                {{ $navItem->title }}--}}
{{--                            </a>--}}
{{--                        </li>--}}
{{--                    @endforeach--}}
{{--                </ul>--}}
{{--            @endforeach--}}
{{--        </ol>--}}
{{--    </div>--}}
{{--</nav>--}}
