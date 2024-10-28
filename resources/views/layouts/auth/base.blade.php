<x-filament-panels::layout.base :livewire="$livewire">
    <main class="fi-auth-layout min-h-screen bg-white dark:bg-gray-800 flex">
        <div class="flex-1 flex flex-col justify-center py-4 md:flex-none md:w-1/2 xl:w-1/3">
            {{-- theme switcher --}}
            <div class="mb-auto text-right px-4 sm:px-6">
                <x-auth.theme-switcher />
            </div>

            {{-- main content --}}
            <div class="px-4 sm:px-15 lg:px-20">
                <div class="mx-auto w-full lg:max-w-lg">
                    <div class="markdown">
                        {{ $slot }}
                    </div>
                </div>
            </div>

            {{-- copyright --}}
            <div class="shrink-0 mt-auto text-xs flex justify-between space-x-4 px-4 sm:px-6">
                <div class="text-gray-500 dark:text-gray-200">
                    {{ str(__('front.menus.footer.copyright', ['year' => now()->year]))->markdown()->toHtmlString() }}
                </div>

                <div>
                    <a
                        href="{{ route('legal.privacy') }}"
                        class="text-gray-500 hover:text-gray-600 hover:underline dark:text-gray-200 dark:hover:text-gray-100"
                    >
                        {{ __('front.menus.footer.legal_privacy') }}
                    </a>

                    <span class="text-gray-600 dark:text-gray-500 inline-block mx-1" aria-hidden="true">&bull;</span>

                    <a
                        href="{{ route('legal.terms') }}"
                        class="text-gray-500 hover:text-gray-600 hover:underline dark:text-gray-200 dark:hover:text-gray-100"
                    >
                        {{ __('front.menus.footer.legal_terms') }}
                    </a>
                </div>
            </div>
        </div>

        <div class="hidden md:block relative w-0 flex-1">
            <img
                class="absolute inset-0 h-full w-full object-cover"
                src="{{ config('randallwilk.auth_bg_image') }}"
                alt="login cover image"
            >
        </div>
    </main>
</x-filament-panels::layout.base>
