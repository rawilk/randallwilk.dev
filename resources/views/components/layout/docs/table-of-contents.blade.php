@props([
    'tableOfContents' => [],
])

@php
    $hasToc = count($tableOfContents) > 0;
@endphp

<div
    {{
        $attributes
            ->class([
                'hidden xl:sticky xl:top-1 xl:-mr-6 xl:block xl:h-[calc(100vh-101px)] xl:flex-none xl:overflow-y-auto xl:py-16 xl:pr-6 navigation-custom-scrollbar',
            ])
    }}
>
    <nav class="w-56" @if ($hasToc) aria-labelledby="table-of-contents-title" @endif>
        @if ($hasToc)
            <h2
                id="table-of-contents-title"
                class="font-display text-sm font-medium text-slate-900 dark:text-white"
            >
                On this page
            </h2>

            <ol
                role="list"
                class="mt-4 space-y-3 text-sm"
                x-data
                x-init="$store.toc.registerHeadings(@js($tableOfContents))"
                x-on:scroll.window.passive="$store.toc.updateCurrentSection()"
            >
                @foreach ($tableOfContents as $heading)
                    @php
                        $jsId = Js::from($heading['id']);
                    @endphp

                    <li>
                        <h3>
                            <a
                                href="#{{ $heading['id'] }}"
                                class="inline-block transition duration-300 ease-out"
                                x-bind:class="{
                                    'text-sky-500 font-bold translate-x-1': $store.toc.current === {{ $jsId }},
                                    'font-normal text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-300 hover:translate-x-1': $store.toc.current !== {{ $jsId }},
                                }"
                            >
                                {{ $heading['text'] }}
                            </a>
                        </h3>

                        @if ($heading['children'])
                            <ol
                                role="list"
                                class="mt-2 space-y-3 pl-5 text-slate-500 dark:text-slate-400"
                            >
                                @foreach ($heading['children'] as $subHeading)
                                    @php
                                        $subJsId = Js::from($subHeading['id']);
                                    @endphp

                                    <li>
                                        <a
                                            href="#{{ $subHeading['id'] }}"
                                            class="inline-block transition duration-300 ease-out"
                                            x-bind:class="{
                                                'text-sky-500 translate-x-1': $store.toc.current === {{ $subJsId }},
                                                'font-normal text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-300 hover:translate-x-1': $store.toc.current !== {{ $subJsId }},
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
