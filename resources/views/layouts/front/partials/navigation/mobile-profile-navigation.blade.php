<div>
    <div class="flex justify-between">
        <div>
            <div class="text-base text-slate-600 font-medium">{{ Auth::user()->name->full }}</div>
            <div class="text-sm text-slate-500 font-medium">{{ Auth::user()->email }}</div>
        </div>

        <div class="flex-shrink-0">
            <img
                src="{{ Auth::user()->avatar_url }}"
                alt="{{ Auth::user()->name->full }} avatar"
                class="h-10 w-10 rounded-full border border-slate-300"
            >
        </div>
    </div>

    <div class="mt-6 grid grid-cols-2 gap-y-8 gap-x-10">
        {!! Menu::mobileService()
            ->addItemClass('-m-3 p-3 flex items-center rounded-md text-base font-medium text-slate-900 hover:[&:not(.exact-active)]:bg-gray-100')
            ->setActiveClass('active bg-gray-100')
            ->setActiveClassOnLink()
        !!}
    </div>
</div>
