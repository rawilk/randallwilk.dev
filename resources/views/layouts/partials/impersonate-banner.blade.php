@if (isImpersonating())
    <div class="relative bg-blue-gray-500">
        <div class="max-w-screen-xl mx-auto py-3 px-3 sm:px-6 lg:px-8">
            <div class="pr-16 sm:text-center sm:px-16">
                <p class="font-medium text-white">
                    <span class="md:hidden">
                        {{ __('users.impersonate.notice', ['name' => auth()->user()->name->full]) }}
                    </span>

                    <span class="hidden md:inline">
                        {{ __('users.impersonate.notice', ['name' => auth()->user()->name->full]) }}
                    </span>

                    <span class="block sm:ml-2 sm:inline-block">
                        <a href="{!! route('impersonate.leave') !!}" class="text-white font-bold underline focus:outline-white">
                            {{ __('users.impersonate.leave') }} &rarr;
                        </a>
                    </span>
                </p>
            </div>
        </div>
    </div>
@endif
