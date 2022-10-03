<footer class="shadow-inner-light bg-slate-800 | print:shadow-none print:bg-transparent" aria-labelledby="footerHeading">
    <h2 id="footerHeading" class="sr-only">{{ __('front.footer.aria_label') }}</h2>

    <div class="wrap">
        <div class="mx-auto py-12">
            <div class="xl:grid xl:grid-cols-3 xl:gap-8">
                {{-- logo/summary --}}
                <div class="space-y-8 xl:col-span-1">
                    <div class="logo mb-6 h-10 w-40 | md:w-56 md:h-auto">
                        <x-logo class="h-auto max-w-full text-slate-200" />
                    </div>

                    <p class="text-base text-slate-400">
                        {{ __('front.footer.summary') }}
                    </p>

                    <div>
                        <div class="mt-5">
                            <a href="https://www.digitalocean.com/?refcode=0f79a60f0243&utm_campaign=Referral_Invite&utm_medium=Referral_Program&utm_source=badge"><img src="https://web-platforms.sfo2.digitaloceanspaces.com/WWW/Badge%202.svg" alt="DigitalOcean Referral Badge" /></a>
                        </div>

                        <div class="mt-5">
                            <iframe src="https://github.com/sponsors/rawilk/button" title="Sponsor rawilk" height="35" width="116" style="border: 0;"></iframe>
                        </div>
                    </div>
                </div>

                <div class="mt-12 grid grid-cols-2 gap-8 xl:col-span-2 xl:mt-0">
                    <div class="md:grid md:grid-cols-2 md:gap-8">
                        {{-- main menu --}}
                        <div>
                            <h3 class="text-sm font-semibold text-slate-400 tracking-wider uppercase">{{ __('front.menus.footer.main_title') }}</h3>
                            {!! Menu::footerMain() !!}
                        </div>

                        {{-- legal --}}
                        <div class="mt-12 md:mt-0">
                            <h3 class="text-sm font-semibold text-slate-400 tracking-wider uppercase">{{ __('front.menus.footer.legal_title') }}</h3>
                            {!! Menu::footerLegal() !!}
                        </div>
                    </div>
                    <div class="md:grid md:grid-cols-2 md:gap-8">
                        {{-- other pages --}}
                        <div class="md:col-span-2">
                            <h3 class="text-sm font-semibold text-slate-400 tracking-wider uppercase">{{ __('front.menus.footer.other_pages_title') }}</h3>
                            {!! Menu::footerOther() !!}
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-8 border-t border-gray-700 pt-8 md:flex md:items-center md:justify-between">
                <div class="flex space-x-6 md:order-2">
                    @foreach (config('site.contact.social', []) as $name => $url)
                        <a href="{{ $url }}"
                           class="text-slate-400 hover:text-slate-300"
                           target="_blank"
                           rel="nofollow noreferrer noopener"
                        >
                            <span class="sr-only">{!! $name !!}</span>
                            @includeIf('layouts.partials.social-icons.' . strtolower($name))
                        </a>
                    @endforeach
                </div>

                {{-- copyright --}}
                <p class="mt-8 text-base text-gray-400 md:order-1 md:mt-0">
                    {!! Str::inlineMarkdown(__('front.menus.footer.copyright', ['year' => now()->year])) !!}
                </p>
            </div>
        </div>
    </div>
</footer>
