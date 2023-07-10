<x-page :title="__('front.legal.title')" :description="__('front.legal.description', ['url' => url('/')])">
    <x-front.page-banner>
        {{ __('front.legal.banner') }}

        <x-slot:content>
            <p class="banner-intro">{{ __('front.legal.banner_intro') }}</p>
        </x-slot:content>
    </x-front.page-banner>

    <div class="section section-group pt-0">
        <section>
            <x-front.content-area class="space-y-10 md:space-y-20" :large-text="false">
                {{-- privacy policy --}}
                <div id="privacy">
                    <x-heroicon-s-lock-closed class="h-8 w-8 text-slate-500" />
                    <h2 class="title-sm !text-lg !mt-4">{{ __('front.legal.privacy_title') }}</h2>
                    <p class="!mb-3">{{ __('front.legal.privacy_description') }}</p>
                    <x-front.link>
                        <div class="flex items-center">
                            <a href="{!! route('legal.privacy') !!}">
                                {{ __('front.legal.privacy_link') }}
                            </a>
                            <x-heroicon-s-chevron-right class="h-3 w-3 ml-2" />
                        </div>
                    </x-front.link>
                </div>

                {{-- terms --}}
                <div id="terms">
                    <x-heroicon-s-cloud class="h-8 w-8 text-slate-500" />
                    <h2 class="title-sm !text-lg !mt-4">{{ __('front.legal.terms_title') }}</h2>
                    <p class="!mb-3">{{ __('front.legal.terms_description') }}</p>
                    <x-front.link>
                        <div class="flex items-center">
                            <a href="{!! route('legal.terms') !!}">
                                {{ __('front.legal.terms_link') }}
                            </a>
                            <x-heroicon-s-chevron-right class="h-3 w-3 ml-2" />
                        </div>
                    </x-front.link>
                </div>

                {{-- disclaimer --}}
                <div id="disclaimer">
                    <x-heroicon-s-exclamation-triangle class="h-8 w-8 text-slate-500" />
                    <h2 class="title-sm !text-lg !mt-4">{{ __('front.legal.disclaimer_title') }}</h2>
                    <p class="!mb-3">{{ __('front.legal.disclaimer_legal_description') }}</p>
                    <x-front.link>
                        <div class="flex items-center">
                            <a href="{!! route('legal.disclaimer') !!}">
                                {{ __('front.legal.disclaimer_link') }}
                            </a>
                            <x-heroicon-s-chevron-right class="h-3 w-3 ml-2" />
                        </div>
                    </x-front.link>
                </div>
            </x-front.content-area>
        </section>
    </div>
</x-page>
