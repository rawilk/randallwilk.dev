<div class="mr-5 flex lg:hidden"
     x-data="{ open: false }"
>
    <button
        type="button"
        class="relative"
        aria-label="{{ __('labels.open_mobile_menu_button') }}"
        x-on:click="open = true"
    >
        <x-heroicon-s-bars-3 class="h-6 w-6 text-slate-500" />
    </button>

    <template x-teleport="body">
        <div class="fixed inset-0 z-50 flex items-start overflow-y-auto bg-slate-900/50 pr-10 lg:hidden"
             role="dialog"
             aria-modal="true"
             aria-label="{{ __('labels.mobile_navigation') }}"
             x-show="open"
             x-on:click.away="open = false"
             x-on:keydown.esc.window="open = false"
        >
            {{-- backdrop --}}
            <div x-show="open"
                 class="fixed inset-0 transform transition-opacity"
                 x-on:click="open = false"
                 aria-hidden="true"
            >
                <div class="absolute inset-0 backdrop-blur"></div>
            </div>

            <div class="min-h-full w-full max-w-xs bg-white px-4 pt-5 pb-12 dark:bg-slate-900 sm:px-6">
                <div class="flex items-center">
                    <button
                        type="button"
                        aria-label="{{ __('labels.close_mobile_menu_button') }}"
                        x-on:click="open = false"
                    >
                        <x-heroicon-s-x-mark class="h-6 w-6 text-slate-500" />
                    </button>
                    <a href="{!! url('/') !!}"
                       class="ml-5"
                       aria-label="Home page"
                    >
                        <x-logo
                            type="mark-dual"
                            class="h-10 w-10 lg:hidden text-slate-600 dark:text-white"
                        />
                    </a>
                </div>

                <x-docs.navigation
                    :navigation="$navigation"
                    :page="$page"
                    :repository="$repository"
                    :latest-version="$latestVersion"
                    type="mobile"
                    class="mt-5 px-1"
                />
            </div>
        </div>
    </template>
</div>
