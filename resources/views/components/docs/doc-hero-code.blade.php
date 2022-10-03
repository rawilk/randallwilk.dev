@if ($hasHeroCode())
    <div class="absolute inset-0 rounded-2xl bg-gradient-to-tr from-sky-300 via-sky-300/70 to-blue-300 opacity-10 blur-lg"></div>
    <div class="absolute inset-0 rounded-2xl bg-gradient-to-tr from-sky-300 via-sky-300/70 to-blue-300 opacity-10"></div>

    <div class="relative rounded-2xl bg-[#0A101F]/80 ring-1 ring-white/10 backdrop-blur">
        <div class="absolute -top-px left-20 right-11 h-px bg-gradient-to-r from-sky-300/0 via-sky-300/70 to-sky-300/0"></div>

        <div class="absolute -bottom-px left-11 right-20 h-px bg-gradient-to-r from-blue-400/0 via-blue-400 to-blue-400/0"></div>

        <div class="pl-4 pt-4" id="doc-hero-code">
            <svg aria-hidden="true" viewBox="0 0 42 10" fill="none" class="h-2.5 w-auto stroke-slate-500/30">
                <circle cx="5" cy="5" r="4.5" />
                <circle cx="21" cy="5" r="4.5" />
                <circle cx="37" cy="5" r="4.5" />
            </svg>

            <div class="mt-4 flex space-x-2 text-xs">
                @foreach ($tabs() as $tab)
                    <div @class([
                        'flex h-6 rounded-full',
                        'bg-gradient-to-r from-sky-400/30 via-sky-400 to-sky-400/30 p-px font-medium text-sky-300' => $loop->first,
                        'text-slate-500' => ! $loop->first,
                    ])>
                        <div @class([
                            'flex items-center rounded-full px-2.5',
                            'bg-slate-800' => $loop->first,
                        ])>
                            {{ $tab }}
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6 flex items-start px-1 text-sm">
                <div aria-hidden="true"
                     class="select-none border-r border-slate-300/5 pr-4 font-mono text-slate-600 overflow-hidden"
                >
                    <pre class="flex overflow-x-auto pb-6 line-numbers p-0 !pl-10 !pb-2 mt-0"><code class="pl-2 pr-4 language-{{ $codeLanguage() }}">{{ $codeSnippet() }}</code></pre>
                </div>
            </div>
        </div>
    </div>
@endif
