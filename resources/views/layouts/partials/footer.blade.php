<footer class="bg-blue-gray-800" aria-labelledby="footerHeading">
    <h2 id="footerHeading" class="sr-only">{{ __('front.footer.aria_label') }}</h2>

    <div class="wrap py-12 lg:py-16 lg:px-8">
        <div class="flex flex-col-reverse space-y-6 space-y-reverse xl:space-y-0 xl:grid xl:grid-cols-3 xl:gap-8">
            <div class="grid grid-cols-2 gap-8 xl:col-span-2">
                <div class="md:grid md:grid-cols-2 md:gap-8">
                    {{-- main menu --}}
                    <div>
                        <h3 class="text-sm font-semibold text-blue-gray-400 tracking-wider uppercase">
                            {{ __('front.menus.footer.main_title') }}
                        </h3>

                        {!! Menu::footerMain() !!}
                    </div>

                    {{-- legal --}}
                    <div class="mt-12 md:mt-0">
                        <h3 class="text-sm font-semibold text-blue-gray-400 tracking-wider uppercase">
                            {{ __('front.menus.footer.legal_title') }}
                        </h3>

                        {!! Menu::footerLegal() !!}
                    </div>
                </div>
            </div>

            <div class="space-y-2 xl:col-span-1">
                <h3 class="text-sm font-semibold text-blue-gray-400 tracking-wider uppercase">
                    {{ __('front.menus.footer.contact_title') }}
                </h3>
                <address class="grid gap-4 sm:gap-0 md:grid-flow-col md:gap-8">
                    <div>
                        <a href="mailto:{!! config('site.contact.email') !!}"
                           class="text-blue-gray-300 hover:text-white text-base"
                        >
                            {!! config('site.contact.email') !!}
                        </a>
                    </div>
                </address>
            </div>
        </div>

        <div class="mt-8 border-t border-blue-gray-700 pt-8 md:flex md:items-center md:justify-between">
            {{-- social links --}}
            <div class="flex space-x-6 md:order-2">
                @foreach (config('site.contact.social') as $name => $url)
                    <a href="{{ $url }}"
                       class="text-blue-gray-400 hover:text-blue-gray-300"
                       target="_blank"
                       rel="nofollow noreferrer noopener"
                    >
                        <span class="sr-only">{!! $name !!}</span>
                        @includeIf('layouts.partials.social-icons.' . strtolower($name))
                    </a>
                @endforeach
            </div>

            {{-- copyright --}}
            <p class="mt-8 text-base text-blue-gray-400 md:mt-0 md:order-1">
                {!! __('front.menus.footer.copyright', ['year' => now()->year]) !!}
            </p>
        </div>
    </div>
</footer>
