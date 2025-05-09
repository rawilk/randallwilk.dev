<footer
    {{
        $attributes
            ->class([
                'shadow-inner-light bg-white text-gray-600',
                'print:shadow-none print:bg-transparent',
            ])
    }}
>
    <div class="wrap footer-wrap">
        <div class="mx-auto pt-12 pb-8">
            <div class="py-0 text-left">
                <div class="lg:flex lg:justify-between lg:gap-x-10">
                    {{-- logo/summary --}}
                    <div class="mb-2 flex-1 lg:mb-0">
                        <div class="flex flex-col justify-between lg:mr-auto lg:max-w-[500px] xl:w-3/4">
                            <div class="mb-4 flex justify-start lg:block logo h-10 w-40 md:w-56 md:h-auto mx-0">
                                <x-logo
                                    type="dual"
                                    class="h-auto max-w-full"
                                />
                            </div>

                            <p class="mx-0 mb-6 max-w-sm text-sm md:text-base font-normal leading-loose lg:mx-0 lg:max-w-full text-pretty">
                                {{ __('front.footer.summary') }}
                            </p>

                            <p class="hidden lg:block mb-8 max-w-sm footer-links text-sm font-normal lg:mx-0 lg:max-w-full text-pretty">
                                {{
                                    str(__('front.footer.stack_info', [
                                        'forge' => 'https://forge.laravel.com',
                                        'hetzner' => 'https://hetzner.cloud/?ref=y4rFJ4ODJ1SK',
                                     ]))
                                        ->inlineMarkdown()
                                        ->toHtmlString()
                                }}
                            </p>

                            <div class="flex w-auto mb-8">
                                <a
                                    href="https://github.com/sponsors/rawilk?o=esb"
                                    class="footer-sponsor-btn border border-black py-2.5 px-4 rounded-lg bg-black text-gray-200 text-sm hover:bg-gray-600"
                                >
                                    {{ __('front.footer.sponsor_button') }}
                                </a>
                            </div>

                        </div>
                    </div>

                    {{-- nav --}}
                    <div class="flex-1 gap-x-8 md:flex md:flex-1 md:justify-end lg:max-w-[500px] mobile:grid mobile:grid-cols-2">
                        {{-- main menu --}}
                        <div class="flex-1 mobile:mb-6">
                            <h5 class="mb-2 text-sm uppercase lg:mb-4 font-semibold">{{ __('front.menus.footer.main_title') }}</h5>
                            {!! Menu::footerMain() !!}
                        </div>

                        {{-- legal --}}
                        <div class="flex-1 mobile:mb-6">
                            <h5 class="mb-2 text-sm uppercase lg:mb-4 font-semibold">{{ __('front.menus.footer.legal_title') }}</h5>
                            {!! Menu::footerLegal() !!}
                        </div>

                        {{-- other links --}}
                        <div class="flex-1">
                            <h5 class="mb-2 text-sm uppercase lg:mb-4 font-semibold">{{ __('front.menus.footer.other_pages_title') }}</h5>
                            {!! Menu::footerOther() !!}
                        </div>
                    </div>
                </div>
            </div>

            <div class="footer-bottom mt-8 border-t border-gray-300 pt-8 md:flex md:items-center md:justify-between">
                <div class="flex space-x-4 md:order-2">
                    @foreach (config('randallwilk.contact.social', []) as $name => $socialContact)
                        <a
                            href="{{ $socialContact['url'] }}"
                            class="footer-social w-9 h-9 rounded-full bg-gray-200 text-gray-600 hover:bg-gray-300 hover:text-gray-800 grid place-content-center"
                            target="_blank"
                            rel="nofollow noreferrer noopener"
                        >
                            <span class="sr-only">{{ $name }}</span>

                            <x-filament::icon
                                :icon="$socialContact['icon']"
                                class="h-5 w-5"
                            />
                        </a>
                    @endforeach
                </div>

                {{-- copyright --}}
                <p class="footer-copyright mt-8 text-sm text-gray-600 md:order-1 md:mt-0">
                    {{
                        str(__('front.menus.footer.copyright', ['year' => now()->year]))
                            ->inlineMarkdown()
                            ->toHtmlString()
                    }}
                </p>
            </div>
        </div>
    </div>
</footer>
