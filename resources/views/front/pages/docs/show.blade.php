@php
    /** @var \App\Docs\Repository $latestVersion */
    $latestVersion = $repository->aliases->first();

    /** @var \App\Docs\DocumentationPage $page */
    /** @var \App\Docs\Alias $alias */
@endphp

<x-doc-page
    :title="App\Helpers\formatPageTitle($page->title, $repository->slug)"
    :repository="$repository"
    :alias="$alias"
    :github-url="$alias->branchUrl()"
    :page="$page"
    :latest-version="$latestVersion"
    :navigation="$navigation"
    :description="$repository->slug"
    :table-of-contents="$tableOfContents"
    :canonical="url('/docs/' . $repository->slug . '/' . $latestVersion->slug . '/' . $page->slug)"
    :no-index="$page->alias !== $latestVersion->slug"
>
    @push('head')
        <meta name="docsearch:version" content="{{ $alias->slug }}" />
        <meta name="docsearch:project" content="{{ $repository->slug }}" />
    @endpush

    @if ($showBigTitle)
        <x-slot:hero>
            <x-docs.title-area
                :slug="$repository->slug"
                :slogan="$alias->slogan"
                :version="$alias->slug"
                :start-url="action([App\Http\Controllers\Docs\DocsController::class, 'show'], [$repository->slug, $alias->slug, 'installation'])"
                :branch-url="$alias->branchUrl()"
            />
        </x-slot:hero>
    @endif

    <article id="docs-content" class="relative">
        <x-docs.header
            :repository-name="$showBigTitle ? null : $repository->slug"
            :section-title="$sectionTitle"
            :title="$page->title"
        />

        {{-- mobile table of contents --}}
        <x-layout.docs.mobile-table-of-contents
            :table-of-contents="$tableOfContents"
        />

        {{-- page content --}}
        <x-docs.prose>
            {{-- archive notice --}}
            @if ($repository->isArchived())
                <blockquote>
                    <p>
                        {note}
                        <span class="font-bold">{{ $repository->slug }}</span>
                        has been archived and is no longer maintained. Use caution when installing in your apps.
                    </p>
                </blockquote>
            @endif

            {{-- outdated version notice --}}
            @if ($latestVersion->slug !== $page->alias)
                <blockquote>
                    <p>
                        {note}
                        You're browsing the documentation for an old version of <span
                            class="font-bold">{{ $repository->slug }}</span>.
                        Consider upgrading your project to
                        <a href="{{ action([App\Http\Controllers\Docs\DocsController::class, 'repository'], [$repository->slug, $latestVersion->slug]) }}">{{ $latestVersion->slug }}</a>.

                        Check your version with the following command:
                        <br><br>
                        `composer show rawilk/{{ $repository->slug }}`
                    </p>
                </blockquote>
            @endif

            {!! $page->contents !!}

            <div class="pt-4">
                <a
                    href="{{ $page->editUrl() }}"
                    class="text-sm"
                    target="_blank"
                    rel="noopener"
                >
                    Edit on GitHub
                </a>
            </div>
        </x-docs.prose>
    </article>

    {{-- repo nav --}}
    <x-docs.footer-nav
        :next="$alias->nextPage()"
        :previous="$alias->previousPage()"
    />
</x-doc-page>
