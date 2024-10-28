<div
    {{
        $attributes
            ->merge([
                'x-data' => '',
            ], escape: false)
            ->class(['flex lg:hidden'])
    }}
>
    <button
        type="button"
        class="relative"
        aria-label="Open navigation"
        x-on:click="$store.mobileNav.open()"
    >
        <x-heroicon-o-bars-3 class="h-6 w-6 text-slate-500" />
    </button>

    <template x-teleport="body">
        <div
            {{--class="fixed inset-0 z-[100] flex items-start overflow-y-auto bg-slate-900/50 pr-10 lg:hidden"--}}
            class="fixed inset-0 z-[100] flex items-start overflow-y-auto pr-10 lg:hidden"
            role="dialog"
            aria-modal="true"
            aria-label="Navigation"
            x-cloak
            x-show="$store.mobileNav.isOpen"
            x-on:keydown.esc.window="$store.mobileNav.close()"
        >
            {{-- backdrop --}}
            <div
                class="bg-slate-800/40 pr-10 backdrop-blur-lg fixed inset-0 z-0 pointer-events-none"
                aria-hidden="true"
                x-show="$store.mobileNav.isOpen"
            >
            </div>

            <div class="min-h-full w-full max-w-sm bg-white px-4 pt-5 pb-12 dark:bg-slate-900 sm:px-6 relative shadow-lg dark:shadow-black/50">
                <div class="flex items-center gap-5">
                    {{-- close button --}}
                    <button
                        type="button"
                        aria-label="Close navigation"
                        x-on:click="$store.mobileNav.close()"
                    >
                        <x-heroicon-s-x-mark class="h-6 w-6 text-slate-500 hover:text-slate-600 dark:hover:text-white" />
                    </button>

                    {{-- logos --}}
                    <div class="flex gap-3 items-center">
                        <a
                            href="{{ route('home') }}"
                            aria-label="Homepage"
                            class="transition duration-500 ease-out motion-reduce:transition-none hover:-translate-y-1"
                        >
                            <x-logo
                                type="mark-dual"
                                class="h-10 w-10 text-slate-600 dark:text-white transition motion-reduce:transition-none block"
                            />
                        </a>

                        <a
                            href="{{ route('docs') }}"
                            aria-label="Docs homepage"
                            class="uppercase transition duration-500 ease-out motion-reduce:transition-none hover:translate-x-1"
                        >
                            <div
                                class="font-black text-brand dark:text-white text-xl"
                            >
                                Docs
                            </div>
                        </a>
                    </div>
                </div>

                {{-- nav --}}
                <div
                    class="mt-5 px-1"
                    x-on:click.away="$store.mobileNav.close()"
                >
                    {{ $slot }}
                </div>
            </div>
        </div>
    </template>
</div>
