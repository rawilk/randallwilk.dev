<div>
    <x-dropdown :pad-menu="false" right width-class="w-screen max-w-md">
        <x-slot:trigger>
            <button
                type="button"
                class="bg-white rounded-full flex text-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                id="user-menu"
            >
                <span class="sr-only">{{ __('Open user menu') }}</span>
                <img class="h-8 w-8 rounded-full" src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name->full }}">
            </button>
        </x-slot:trigger>

        <div class="rounded-lg overflow-hidden" role="none">
            {{-- header --}}
            <div class="px-5 py-5 bg-slate-100" role="none">
                <div class="flex justify-between">
                    <div>
                        <div class="text-base text-slate-600 font-medium">{{ auth()->user()->name->full }}</div>
                        <div class="text-sm text-slate-500 font-medium">{{ auth()->user()->email }}</div>
                    </div>

                    <div class="flex-shrink-0">
                        <img class="w-10 h-10 rounded-full border border-slate-300" src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name->full }}">
                    </div>
                </div>
            </div>

            {{-- profile links --}}
            <div class="relative grid gap-6 bg-white px-5 py-6 sm:gap-8 sm:p-8" role="none">
                <a href="{!! route('profile.show') !!}" class="-m-3 p-3 flex items-start rounded-lg hover:bg-slate-100 transition-colors focus:outline-slate" role="menuitem">
                    <div class="flex-shrink-0 flex items-center justify-center h-10 w-10 rounded-md bg-blue-500 text-white sm:h-12 sm:w-12">
                        <x-heroicon-o-cog class="h-6 w-6" />
                    </div>

                    <div class="ml-4">
                        <p class="text-base font-medium text-slate-900">
                            {{ __('users.profile.page_title') }}
                        </p>
                        <p class="mt-1 text-sm text-slate-500">
                            {{ __('Manage your account settings.') }}
                        </p>
                    </div>
                </a>

                @can('viewHorizon')
                    <a href="{!! route('horizon.index') !!}"
                       class="-m-3 p-3 flex items-start rounded-lg hover:bg-slate-100 transition-colors focus:outline-slate"
                       role="menuitem"
                    >
                        <div class="flex-shrink-0 flex items-center justify-center h-10 w-10 rounded-md bg-blue-500 text-white sm:h-12 sm:w-12">
                            <x-css-database class="h-6 w-6" />
                        </div>

                        <div class="ml-4">
                            <p class="text-base font-medium text-blue-gray-900">
                                {{ __('Horizon') }}
                            </p>
                            <p class="mt-1 text-sm text-blue-gray-500">
                                {{ __('View the Laravel Horizon dashboard.') }}
                            </p>
                        </div>
                    </a>
                @endcan
            </div>

            {{-- footer --}}
            <div class="px-5 py-5 bg-slate-50 space-y-6 sm:flex sm:space-y-0 sm:space-x-10 sm:px-8" role="none">
                <div class="flow-root" role="none">
                    <button
                        type="submit"
                        form="logout-form"
                        class="-m-3 p-3 flex items-center rounded-md text-base font-medium text-slate-900 hover:bg-slate-200 transition-colors focus:outline-slate"
                        role="menuitem"
                    >
                        <x-heroicon-s-arrow-left-on-rectangle class="flex-shrink-0 h-6 w-6 text-slate-400" />
                        <span class="ml-3">{{ __('Sign Out') }}</span>
                    </button>
                </div>
            </div>
        </div>
    </x-dropdown>
</div>
