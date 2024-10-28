@props([
    'slug' => '',
    'slogan' => '',
    'version' => '',
    'startUrl' => null,
    'branchUrl' => null,
])

<div
    {{
        $attributes
            ->class([
                'overflow-hidden bg-slate-900 dark:-mb-32 dark:pb-32 dark:pt-[4.5rem] dark:lg:mt-[-4.1rem] dark:lg:pt-[4.75rem]',
            ])
    }}
>
    <div class="py-16 sm:px-2 lg:relative lg:py-20 lg:px-0">
        <div class="mx-auto grid max-w-2xl grid-cols-1 items-center gap-y-16 gap-x-8 px-4 lg:max-w-8xl lg:grid-cols-2 lg:px-8 xl:gap-x-16 xl:px-12">
            <div class="relative z-10 md:text-center lg:text-left">
                <img
                    src="{{ asset('images/docs/blur-cyan.png') }}"
                    class="absolute bottom-full right-full -mr-72 -mb-56 opacity-50"
                    width="530"
                    height="530"
                    decoding="async"
                />

                <div class="relative">
                    <p class="inline bg-gradient-to-r from-indigo-200 via-sky-400 to-indigo-200 bg-clip-text font-display text-5xl tracking-tight text-transparent text-gradient">
                        {{ $slug }}
                    </p>
                    <p class="mt-3 text-2xl tracking-tight text-slate-400">
                        {{ $slogan }}
                    </p>

                    {{-- links --}}
                    <div class="mt-8 flex gap-4 md:justify-center lg:justify-start">
                        @if ($startUrl)
                            <a
                                href="{{ $startUrl }}"
                                class="rounded-full bg-sky-300 py-2 px-4 text-sm font-semibold text-slate-900 hover:bg-sky-200 focus:outline-none focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-sky-300/50 active:bg-sky-500"
                            >
                                Get started
                            </a>
                        @endif

                        @if ($branchUrl)
                            <a
                                href="{{ $branchUrl }}"
                                class="rounded-full bg-slate-800 py-2 px-4 text-sm font-medium text-white hover:bg-slate-700 focus:outline-none focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white/50 active:text-slate-400"
                                rel="nofollow noreferrer"
                            >
                                View on GitHub
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <div class="relative lg:static xl:pl-10">
                {{-- hero bg --}}
                <div class="absolute inset-x-[-50vw] -top-32 -bottom-48 [mask-image:linear-gradient(transparent,white,white)] dark:[mask-image:linear-gradient(transparent,white,transparent)] lg:left-[calc(50%+14rem)] lg:right-0 lg:-top-32 lg:-bottom-32 lg:[mask-image:none] lg:dark:[mask-image:linear-gradient(white,white,transparent)]">
                    <x-svg-docs.hero
                        class="absolute top-1/2 left-1/2 -translate-y-1/2 -translate-x-1/2 lg:left-0 lg:translate-x-0 lg:translate-y-[-60%]"
                    />
                </div>

                <div class="relative">
                    <img
                        src="{{ asset('images/docs/blur-cyan.png') }}"
                        class="absolute -top-64 -right-64"
                        width="530"
                        height="530"
                        decoding="async"
                    />
                    <img
                        src="{{ asset('images/docs/blur-indigo.png') }}"
                        class="absolute -bottom-40 -right-44"
                        width="567"
                        height="567"
                        decoding="async"
                    />

                    <x-docs.doc-hero-code
                        :repository="$slug"
                        :version="$version"
                    />
                </div>
            </div>
        </div>
    </div>
</div>
