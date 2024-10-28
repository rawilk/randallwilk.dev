@push('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/typed.js/2.0.12/typed.min.js" integrity="sha512-3J8teBiHrSyaaRBajZyIEtpDsXdPq1gsznKWIVb5CnorQuFhjWGhWe54z8YNnHHr7MZuExb9m5kvf964HiT1Sw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
@endpush

<section id="intro">
    <div class="wrap">
        <div class="flex items-center justify-center | md:max-w-[600px] md:mx-auto | xl:max-w-[900px] | h-[calc(100vh-80px)] sm:h-[calc(100vh-88px)] xl:h-[calc(100vh-99px)]">
            <div class="md:grid md:grid-cols-3 md:gap-6">
                <div class="md:col-span-1 text-center">
                    <img src="{{ asset('images/randall.jpeg') }}"
                         alt="Randall Wilk"
                         class="w-40 mx-auto md:mx-0 sm:w-48 rounded-full border-4 border-brand"
                    >
                </div>

                <div class="mt-8 text-center | md:text-left md:mt-0 md:col-span-2">
                    <p class="text-2xl mb-1 font-bold text-slate-900">I'm Randall Wilk</p>
                    <div x-data="{
                            options: {
                                stringsElement: '#typed-strings',
                                typeSpeed: 60,
                                backSpeed: 20,
                                backDelay: 1200,
                                showCursor: false,
                            },
                        }"
                         x-init="new Typed($refs.typed, options)"
                    >
                        <div id="typed-strings" style="display: none;">
                            <p>a Front-End Developer</p>
                            <p>a Server Manager</p>
                            <p>an Open-Source Contributor</p>
                            <p>a Laravel Developer</p>
                        </div>
                        <p class="text-2xl !mb-4 font-bold text-brand" x-ref="typed"></p>
                    </div>

                    <p class="text-lg text-slate-600">{{ __('front.home.intro.summary') }}</p>
                </div>
            </div>

            <div class="absolute bottom-10 group flex flex-col text-center justify-center"
                 role="button"
                 x-data
                 x-on:click="document.documentElement.scroll({ behavior: 'smooth', top: document.getElementById('about').offsetTop })"
            >
                <div class="relative mx-auto w-[30px] h-[50px] border-2 border-slate-400 rounded-[50px] bg-white group-hover:bg-gray-50 group-hover:border-slate-800/80 transition-all | before:absolute before:top-1 before:left-1/2 before:-ml-[3.5px] before:w-[7px] before:h-[7px] before:rounded-full before:bg-slate-400 group-hover:before:bg-slate-800/80 before:animate-scroller">
                </div>

                <div class="mx-auto mt-2 text-xs text-slate-400 group-hover:text-slate-800/80 transition-colors">
                    {{ __('scroll down') }}
                </div>
            </div>
        </div>
    </div>
</section>
