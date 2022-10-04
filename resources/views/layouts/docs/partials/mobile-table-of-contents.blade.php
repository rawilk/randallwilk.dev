<div class="xl:hidden">
    <x-dropdown
        class="w-full"
        width-class="w-full"
    >
        <x-slot:trigger>
            <div class="flex items-center group">
                <button
                    type="button"
                    class="text-slate-600 dark:text-slate-400 group-hover:bg-slate-200 dark:group-hover:bg-slate-600 rounded-md p-1.5 -ml-1.5"
                    aria-labelledby="mobile-toc-label"
                >
                    <x-heroicon-s-list-bullet class="w-6 h-6" />
                </button>
                <span class="ml-1 text-sm text-slate-600 dark:text-slate-400" id="mobile-toc-label">
                    {{ __('front.docs.show.mobile_table_of_contents_title') }}
                </span>
            </div>
        </x-slot:trigger>

        <x-slot:menu class="!p-0 bg-white dark:bg-slate-800 rounded-md"></x-slot:menu>

        <div class="p-3 bg-white text-sm dark:bg-slate-800 rounded-md max-h-[260px] overflow-auto">
            <nav>
                <ol role="list" class="space-y-2 text-sm">
                    @foreach ($tableOfContents as $heading)
                        <li>
                            <a href="#{{ $heading['id'] }}"
                               class="block rounded-[0.625rem] p-1 hover:bg-slate-100 dark:hover:bg-slate-900/40 w-full text-slate-700 dark:text-slate-400 hover:text-slate-900"
                            >
                                {{ $heading['text'] }}
                            </a>
                            @if ($heading['children'])
                                <ol role="list"
                                    class="mt-1 space-y-2 pl-5 text-slate-500 dark:text-slate-400"
                                >
                                    @foreach ($heading['children'] as $subHeading)
                                        <li>
                                            <a href="#{{ $subHeading['id'] }}"
                                               class="block rounded-[0.625rem] p-1 hover:bg-slate-100 dark:hover:bg-slate-900/40 w-full text-slate-700 dark:text-slate-400 hover:text-slate-900"
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
    </x-dropdown>
</div>
