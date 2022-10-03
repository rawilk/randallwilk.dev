<div class="hidden xl:sticky xl:top-[4.5rem] xl:-mr-6 xl:block xl:h-[calc(100vh-4.5rem)] xl:flex-none xl:overflow-y-auto xl:py-16 xl:pr-6">
    <nav class="w-56" aria-labelledby="table-of-contents-title">
        @if (isset($tableOfContents) && count($tableOfContents) > 0)
            <h2 id="table-of-contents-title"
                class="font-display text-sm font-medium text-slate-900 dark:text-white"
            >
                {{ __('front.docs.show.table_of_contents_title') }}
            </h2>

            <ol role="list" class="mt-4 space-y-3 text-sm" x-data x-init="$store.visibleSection.registerHeadings({{ Js::from($tableOfContents) }})">
                @foreach ($tableOfContents as $heading)
                    <li>
                        <h3>
                            <a href="#{{ $heading['id'] }}"
                               x-bind:class="{
                                   'text-sky-500': $store.visibleSection.current === '{{ $heading['id'] }}',
                                   'font-normal text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-300': $store.visibleSection.current !== '{{ $heading['id'] }}',
                               }"
                            >
                                {{ $heading['text'] }}
                            </a>
                        </h3>
                        @if ($heading['children'])
                            <ol role="list"
                                class="mt-2 space-y-3 pl-5 text-slate-500 dark:text-slate-400"
                            >
                                @foreach ($heading['children'] as $subHeading)
                                    <li>
                                        <a href="#{{ $subHeading['id'] }}"
                                           x-bind:class="{
                                               'text-sky-500': $store.visibleSection.current === '{{ $subHeading['id'] }}',
                                               'font-normal text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-300': $store.visibleSection.current !== '{{ $subHeading['id'] }}',
                                           }"
                                        >
                                            {{ $subHeading['text'] }}
                                        </a>
                                    </li>
                                @endforeach
                            </ol>
                        @endif
                    </li>
                @endforeach
            </ol>
        @endif
    </nav>
</div>
