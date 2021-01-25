<div>
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

    <div class="mt-6 grid grid-cols-2 gap-y-4 gap-x-8">
        <a href="{!! route('profile.show') !!}"
           class="-m-3 p-3 flex items-center rounded-md text-base font-medium text-gray-900 hover:bg-gray-100 focus:outline-blue-gray"
        >
            {{ __('users.profile.account_info.page_title') }}
        </a>

        @if (auth()->user()->is_admin)
            <a href="{!! route('admin.dashboard') !!}"
               class="-m-3 p-3 flex items-center rounded-md text-base font-medium text-gray-900 hover:bg-gray-100 focus:outline-blue-gray"
            >
                {{ __('front.menus.footer.dashboard') }}
            </a>
        @endif

        <a class="-m-3 p-3 flex items-center rounded-md text-base font-medium text-gray-900 hover:bg-gray-100 focus:outline-blue-gray"
           href="#"
           x-on:click.prevent="document.getElementById('logout-form').submit()"
        >
            {{ __('front.menus.service.logout') }}
        </a>
    </div>
</div>
