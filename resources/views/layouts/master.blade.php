@extends('layouts.base')

@section('content')
<header class="relative flex justify-between items-center h-24 py-4 px-4 bg-white border-b border-gray-300" role="banner">
    <div class="flex items-center w-full mx-auto">
        <div class="flex items-center">
            <a href="{{ route('home') }}" aria-label="{{ config('app.name') }} home" title="{{ config('app.name') }} home" class="items-center inline-flex">
                <img src="{{ asset('images/logo_color.png') }}" alt="Randall Wilk logo"
                     class="w-1/2 sm:w-64 h-16"
                >
            </a>
        </div>

        <div class="hidden lg:flex items-center justify-end flex-1 text-sm text-right md:pl-10 sm:text-base">
            <a href="{{ route('projects') }}" class="ml-3 sm:ml-6 {{ optional(request()->route())->named('projects') ? 'active' : '' }}">Projects</a>
            <a href="{{ route('contact') }}" class="ml-3 sm:ml-6 {{ optional(request()->route())->named('contact') ? 'active' : '' }}">Contact</a>
        </div>

        <div
            x-data="{ open: false }"
            x-show="open"
            @@set-nav-open.window="open = $event.detail"
            class="fixed inset-0 z-20"
            style="background: rgba(0, 0, 0, 0.5); display: none;"
        >
            <div x-show.transition.opacity="open" class="fixed left-0 top-0 p-6">
                @include('includes.menu-toggle')
            </div>

            <div x-show.transition.translate="open"
                 @click.away="$dispatch('set-nav-open', false)"
                 class="bg-white bottom-0 fixed right-0 top-0 z-10 p-4 w-4/6 overflow-y-auto"
            >
                <div class="flex flex-col pt-4">
                    <div class="mb-2">
                        <a href="{{ route('home') }}" aria-label="{{ config('app.name') }} home" title="{{ config('app.name') }} home">
                            <img src="{{ asset('images/logo_color.png') }}" alt="Randall Wilk logo"
                                 class="max-w-full"
                            >
                        </a>
                    </div>

                    <a href="{{ route('projects') }}" class="mb-4 {{ optional(request()->route())->named('projects') ? 'active' : '' }}">Projects</a>
                    <a href="{{ route('contact') }}" class="mb-4 {{ optional(request()->route())->named('contact') ? 'active' : '' }}">Contact</a>
                </div>
            </div>
        </div>
    </div>

    @include('includes.menu-toggle')
</header>

<main role="main" class="flex-auto w-full">
    @yield('content')
</main>
@overwrite
