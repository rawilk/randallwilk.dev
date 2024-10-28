@props([
    'tableOfContents' => [],
])

@if (count($tableOfContents) > 0)
    <div {{ $attributes->class('xl:hidden') }}>
        <div
            class="border rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-800 py-3.5 px-4"
            x-data="{
                isOpen: true,
                toggle() { this.isOpen = ! this.isOpen },
            }"
        >
            <div
                role="button"
                aria-label="Toggle page navigation links"
                x-on:click="toggle"
                x-on:keydown.enter.stop="toggle"
                x-on:keydown.space.prevent.stop="toggle"
                class="flex items-center gap-x-2 group text-slate-600 hover:text-slate-900 dark:text-slate-200 dark:hover:text-white"
                tabindex="0"
            >
                <h3
                    class="font-display group-hover:underline"
                    id="mobile-table-of-contents-title"
                >
                    On this page
                </h3>

                <x-heroicon-m-chevron-down
                    class="h-4 w-4"
                    x-bind:class="{
                        'rotate-180': isOpen,
                    }"
                />
            </div>

            <nav
                aria-labelledby="mobile-table-of-contents-title"
                x-cloak
                x-show="isOpen"
                x-collapse
            >
                <ol
                    role="list"
                    class="mt-4 space-y-3 text-sm"
                >
                    @foreach ($tableOfContents as $heading)
                        <li>
                            <h3>
                                <a
                                    href="#{{ $heading['id'] }}"
                                    class="inline-block transition duration-300 ease-out font-normal text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-300 hover:translate-x-1"
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
                                        <li>
                                            <a
                                                href="#{{ $subHeading['id'] }}"
                                                class="inline-block transition duration-300 ease-out font-normal text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-300 hover:translate-x-1"
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
            </nav>
        </div>
    </div>
@endif
