<nav class="bg-gray-100 border-r border-gray-200 pt-5 pb-4 flex flex-col flex-grow overflow-y-auto">
    <div class="flex-shrink-0 px-4 flex items-center">
        <x-logo class="w-auto h-8 text-slate-600" type="dual" />
    </div>
    <div class="flex-grow mt-5 flex flex-col">
        <div class="flex-1 space-y-1">
            {!! Menu::adminMenu() !!}
        </div>
    </div>

    <div class="flex-shrink-0 block w-full">
        <a href="{!! route('home') !!}" class="{{ config('site.main_menu.item_base_class') }} {{ config('site.main_menu.item_inactive_class') }} w-full">
            <x-heroicon-m-arrow-left class="{{ config('site.main_menu.icon_base_class') }} {{ config('site.main_menu.icon_inactive_class') }}" />
            <span>{{ __('Back to site') }}</span>
        </a>

        <button type="submit" form="logout-form" class="{{ config('site.main_menu.item_base_class') }} {{ config('site.main_menu.item_inactive_class') }} w-full">
            <x-heroicon-s-arrow-left-on-rectangle class="{{ config('site.main_menu.icon_base_class') }} {{ config('site.main_menu.icon_inactive_class') }}" />

            {{ __('Sign Out') }}
        </button>
    </div>
</nav>
