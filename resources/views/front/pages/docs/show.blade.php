@php /** @var \App\Docs\Repository $repository */ @endphp

<x-page title="{!! formatPageTitle($page->title, $repository->slug) !!}"
        description="{{ $repository->slug }}"
        :show-header="false"
>
    @push('head')
        <meta name="docsearch:version" content="{{ $alias->slug }}">
        <meta name="docsearch:project" content="{{ $repository->slug }}">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/docsearch.js@2/dist/cdn/docsearch.min.css">
    @endpush

    <div class="relative" id="docsScreen">
        <div class="relative flex items-start">
            {{-- nav --}}
            @include('front.pages.docs.partials.navigation')

            {{-- content --}}
            <section class="flex-1 pl-8 sm:pl-0 pr-2 sm:pr-0 lg:max-w-full docs-constrained">
                <div class="max-w-screen-lg px-4 sm:px-16 lg:px-24 pb-10 sm:pb-16">

                    {{-- header --}}
                    @include('front.pages.docs.partials.header')

                    {{-- doc content --}}
                    <section class="mt-8 md:mt-16">
                        <section class="docs-main max-w-prose">

                            @if ($repository->isArchived())
                                <blockquote>
                                    <p>
                                        {note}
                                        <strong>{{ $repository->slug }}</strong> has been archived and is no longer maintained.
                                        Use caution when installing in your apps.
                                    </p>
                                </blockquote>
                            @endif

                            @unless ($alias->isMainBranch())
                                <blockquote>
                                    <p>
                                        {note}
                                        You're browsing the documentation for an old version of <strong>{{ $repository->slug }}</strong>.
                                        Consider upgrading your project to
                                        <a href="/docs/{{ $repository->slug }}/{{ $repository->aliases->first()?->slug }}">{{ $repository->aliases->first()?->slug ?? 'the latest version' }}</a>.
                                    </p>
                                </blockquote>
                            @endunless

                            @if ($showBigTitle)
                                <h1 class="banner-slogan">{{ $repository->slug }}</h1>
                                <div class="banner-intro flex items-center justify-start mb-8">
                                    {{ $alias->slogan }}
                                </div>
                            @else
                                <h1 class="mb-8">{{ $page->title }}</h1>
                            @endif

                            <div class="{{ str_replace('/', '-', $page->slug) }}">
                                {!! $page->contents !!}
                            </div>

                        </section>
                    </section>

                    {{-- doc navigation --}}
                    <div class="pt-8 pb-6 mt-12 border-t-2 border-blue-gray-300">
                        <x-doc-footer-links :previous="$alias->previousPage()" :next="$alias->nextPage()" />
                    </div>

                    {{-- edit on github --}}
                    <div class="pt-4 pb-6 mt-4 border-t-2 border-blue-gray-300">
                        <p class="text-sm">
                            Caught a mistake?
                            <a href="{{ $alias->githubUrl }}/blob/{{ $alias->isMainBranch() ? $alias->mainBranchName : $alias->slug }}/docs/{{ $page->slug }}.md"
                               class="link-black link-underline inline-flex items-center space-x-1"
                               target="_blank"
                               rel="nofollow noopener noreferrer"
                            >
                                <span>Suggest an edit on Github</span>
                                <x-heroicon-o-external-link class="w-3 h-3" />
                            </a>
                        </p>
                    </div>
                </div>
            </section>
        </div>
    </div>
</x-page>
