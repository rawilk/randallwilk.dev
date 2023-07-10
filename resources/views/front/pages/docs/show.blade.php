@php
    /** @var \App\Docs\Repository $latestVersion */
    $latestVersion = $repository->aliases->first();
@endphp

<x-doc-page
    title="{{ pageTitle($page->title, $repository->slug) }}"
    description="{{ $repository->slug }}"
    canonical="{{ url('/docs/' . $repository->slug . '/' . $latestVersion->slug . '/' . $page->slug) }}"
    :no-index="$page->alias !== $latestVersion->slug"
    github-url="{{ $alias->branchUrl() }}"
    :navigation="$navigation"
    :repository="$repository"
    :page="$page"
    :table-of-contents="$tableOfContents"
    :latest-version="$latestVersion"
    :alias="$alias"
>
    @push('head')
        <meta name="docsearch:version" content="{{ $alias->slug }}">
        <meta name="docsearch:project" content="{{ $repository->slug }}">
    @endpush

    <article id="docs-content">
        @if ($showBigTitle)
            <x-slot:hero>
                @include('front.pages.docs.partials.title-area')
            </x-slot:hero>
        @endif

        {{-- page title --}}
        @include('front.pages.docs.partials.header')

        {{-- mobile table of contents --}}
        @includeWhen(isset($tableOfContents) && count($tableOfContents) > 0, 'layouts.docs.partials.mobile-table-of-contents')

        {{-- page content --}}
        <div @class([
            'prose prose-slate max-w-none dark:prose-invert dark:text-slate-400',
            // headings
            'prose-headings:scroll-mt-28 prose-headings:font-display prose-headings:font-normal lg:prose-headings:scroll-mt-[8.5rem]',
            // lead
            'prose-lead:text-slate-500 dark:prose-lead:text-slate-400',
            // links
            'prose-a:font-semibold dark:prose-a:text-sky-400',
            // link underline
            'prose-a:no-underline prose-a:shadow-[inset_0_-2px_0_0_var(--tw-prose-background,#fff),inset_0_calc(-1*(var(--tw-prose-underline-size,4px)+2px))_0_0_var(--tw-prose-underline,theme(colors.sky.300))] hover:prose-a:[--tw-prose-underline-size:6px] dark:[--tw-prose-background:theme(colors.slate.900)] dark:prose-a:shadow-[inset_0_calc(-1*var(--tw-prose-underline-size,2px))_0_0_var(--tw-prose-underline,theme(colors.sky.800))] dark:hover:prose-a:[--tw-prose-underline-size:6px]',
            // pre
            'prose-pre:rounded-xl prose-pre:bg-fenced prose-pre:shadow-lg dark:prose-pre:bg-slate-800/60 dark:prose-pre:shadow-none dark:prose-pre:ring-1 dark:prose-pre:ring-slate-300/10',
            // code
            'prose-code:before:hidden prose-code:after:hidden',
            // hr
            'dark:prose-hr:border-slate-800',
        ])>
            {{-- archive notice --}}
            @if ($repository->isArchived())
                <blockquote>
                    <p>
                        {note}
                        {!! Str::inlineMarkdown(__('front.docs.alerts.repo_archived', ['name' => $repository->slug])) !!}
                    </p>
                </blockquote>
            @endif

            {{-- outdated version notice --}}
            @if ($latestVersion->slug !== $page->alias)
                <blockquote>
                    <p>
                        {note}
                        {!! Str::inlineMarkdown(__('front.docs.alerts.outdated_version', [
                            'repository' => $repository->slug,
                            'alias' => $latestVersion->slug,
                            'url' => action([\App\Http\Controllers\Docs\DocsController::class, 'repository'], [$repository->slug, $latestVersion->slug]),
                        ])) !!}
                    </p>
                </blockquote>
            @endif

            {!! $page->contents !!}
        </div>
    </article>

    {{-- prev/next buttons --}}
    <x-docs.footer-nav :next="$alias->nextPage()" :previous="$alias->previousPage()" />

    {{-- edit on GitHub --}}
    <div class="mt-8 border-t border-slate-200 pt-6 dark:border-slate-800">
        <div class="font-display text-sm text-slate-900 dark:text-white">
            <span>{{ __('front.docs.show.edit_on_github') }}</span>
            <x-link :href="$alias->pageGitHubUrl($page)"
                    class="underline font-semibold text-slate-500 hover:text-slate-600 dark:text-slate-400 dark:hover:text-slate-300"
            >
                <span>{{ __('front.docs.show.edit_on_github_link') }}</span>
            </x-link>
        </div>
    </div>
</x-doc-page>
