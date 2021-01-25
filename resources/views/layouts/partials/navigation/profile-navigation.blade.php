<div>
    <x-dropdown right>
        <x-slot name="trigger">
            <button type="button"
                    class="bg-white rounded-full flex text-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500"
                    id="user-menu"
                    aria-haspopup="true"
            >
                <span class="sr-only">{{ __('Open user menu') }}</span>
                <img class="h-8 w-8 rounded-full" src="{{ Auth::user()->avatar_url }}" alt="{{ Auth::user()->name->full }}">
            </button>
        </x-slot>

        <div class="w-screen max-w-md rounded-lg shadow-lg ring-1 ring-black ring-opacity-5 overflow-hidden">
            {{-- header --}}
            <div class="px-5 py-5 bg-blue-gray-100">
                <div class="flex justify-between">
                    <div>
                        <div class="text-base text-blue-gray-600 font-medium">
                            {{ Auth::user()->name->full }}
                        </div>
                        <div class="text-sm text-blue-gray-500 font-medium">
                            {{ Auth::user()->email }}
                        </div>
                    </div>

                    <div class="flex-shrink-0">
                        <img class="w-10 h-10 rounded-full border border-blue-gray-300" src="{{ Auth::user()->avatar_url }}" alt="{{ Auth::user()->name->full }}">
                    </div>
                </div>
            </div>

            {{-- profile links --}}
            <div class="relative grid gap-6 bg-white px-5 py-6 sm:gap-8 sm:p-8">
                {{-- profile --}}
                <a href="{{ route('profile.show') }}" class="-m-3 p-3 flex items-start rounded-lg hover:bg-blue-gray-100 transition-colors focus:outline-blue-gray">
                    <div class="flex-shrink-0 flex items-center justify-center h-10 w-10 rounded-md bg-primary-500 text-white sm:h-12 sm:w-12">
                        <x-heroicon-o-cog class="h-6 w-6" />
                    </div>

                    <div class="ml-4">
                        <p class="text-base font-medium text-blue-gray-900">
                            {{ __('users.profile.account_info.page_title') }}
                        </p>
                        <p class="mt-1 text-sm text-blue-gray-500">
                            {{ __('front.menus.service.my_account_sub_title') }}
                        </p>
                    </div>
                </a>

                {{-- dashboard --}}
                @if (auth()->user()->is_admin)
                    <a href="{{ route('admin.dashboard') }}" class="-m-3 p-3 flex items-start rounded-lg hover:bg-blue-gray-100 transition-colors focus:outline-blue-gray">
                        <div class="flex-shrink-0 flex items-center justify-center h-10 w-10 rounded-md bg-primary-500 text-white sm:h-12 sm:w-12">
                            <x-css-clapper-board class="h-6 w-6" />
                        </div>

                        <div class="ml-4">
                            <p class="text-base font-medium text-blue-gray-900">
                               Dashboard
                            </p>
                            <p class="mt-1 text-sm text-blue-gray-500">
                                Go to the admin panel dashboard.
                            </p>
                        </div>
                    </a>
                @endif
            </div>

            {{-- footer --}}
            <div class="px-5 py-5 bg-blue-gray-50 space-y-6 sm:flex sm:space-y-0 sm:space-x-10 sm:px-8">
                <div class="flow-root">
                    <button type="submit"
                            form="logout-form"
                            class="-m-3 p-3 flex items-center rounded-md text-base font-medium text-blue-gray-900 hover:bg-blue-gray-200 transition-colors focus:outline-blue-gray"
                    >
                        <x-heroicon-s-logout class="flex-shrink-0 h-6 w-6 text-blue-gray-400" />
                        <span class="ml-3">{{ __('front.menus.service.logout') }}</span>
                    </button>
                </div>
            </div>
        </div>
    </x-dropdown>
</div>
