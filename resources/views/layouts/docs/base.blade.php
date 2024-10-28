@props([
    'title' => '',
    'canonical' => null,
    'ogTitle' => null,
    'ogDescription' => null,
    'ogImage' => null,
    'description' => '',
    'needsLivewireScripts' => true,
    'repository' => null,
    'alias' => null,
    'githubUrl' => null,
    'navigation' => null,
    'page' => null,
    'latestVersion' => null,
    'tableOfContents' => null,
    'hero' => null,
    'noIndex' => false,
])

<x-layout.html
    :title="$title"
    :html-attributes="
        new Illuminate\View\ComponentAttributeBag([
            'class' => 'antialiased [font-feature-settings:\'ss01\']',
            'id' => 'docsScreen',
        ])
    "
    class="bg-white dark:bg-slate-900"
    x-data=""
    x-cloak
>
    <x-slot:head-start>
        <x-layout.partials.analytics :id="config('services.google.analytics.id')" />
        <x-layout.partials.meta
            :canonical="$canonical"
            :title="$title"
            :description="$description"
            :og-description="$ogDescription"
            :og-title="$ogTitle"
            :og-image="$ogImage"
            :no-index="$noIndex"
        />
        <link rel="preconnect" href="https://{{ config('services.algolia.app_id') }}-dsn.algolia.net" crossorigin />
    </x-slot:head-start>

    <x-slot:head>
        @stack('head')

        @include('layouts.docs.partials.assets')

        @if ($needsLivewireScripts)
            @livewireStyles
        @endif

        <script>
            const theme = localStorage.getItem('theme') ?? 'light';

            if (
                theme === 'dark' ||
                (theme === 'system' &&
                    window.matchMedia('(prefers-color-scheme: dark)')
                        .matches)
            ) {
                document.documentElement.classList.add('dark');
            }
        </script>
    </x-slot:head>

    <x-docs.banner />

    <x-layout.docs.header
        :repository="$repository"
        :alias="$alias"
        :page="$page"
        :github-url="$githubUrl"
    >
        <x-slot:mobile-nav>
            <x-docs.navigation
                :navigation="$navigation"
                :page="$page"
                :repository="$repository"
                type="mobile"
                class="mt-5 px-1"
            />
        </x-slot:mobile-nav>
    </x-layout.docs.header>

    {{ $hero }}

    <main class="relative mx-auto flex max-w-screen-2xl justify-center sm:px-2 lg:px-8 xl:px-12">
        {{-- nav --}}
        @include('layouts.docs.partials.navigation')

        {{-- content --}}
        <div class="min-w-0 max-w-2xl flex-auto px-4 py-16 lg:max-w-none lg:pr-0 lg:pl-8 xl:px-16">
            {{ $slot }}
        </div>

        {{-- table of contents --}}
        <x-layout.docs.table-of-contents
            :table-of-contents="$tableOfContents"
        />
    </main>

    <x-layout.front.footer />

    @filamentScripts(withCore: true)

    @if ($needsLivewireScripts)
        @livewireScripts
    @endif
</x-layout.html>
