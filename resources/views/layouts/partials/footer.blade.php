<footer class="relative">
    <div class="bg-gray-700 flex-none pt-64 pb-8 print:pb-2" role="navigation">
        <div class="wrap links links-white leading-loose md:leading-normal print:hidden">
            <div class="grid grid-cols-2 items-start text-sm md:flex md:justify-between">
                @include('layouts.partials.menu')

                <div class="grid md:grid-flow-col md:items-center md:ml-12 md:gap-12">
                    @include('layouts.partials.service')
                </div>
            </div>

            <hr class="my-8 h-2px text-white opacity-25 rounded print:text-white" style="page-break-after: avoid;">

            <div class="grid gap-4 sm:grid-cols-2 sm:gap-8 md:flex flex-row-reverse justify-between">
                <address class="grid gap-4 sm:gap-0 md:grid-flow-col md:gap-8 md:text-right">
                    <div>
                        <a href="mailto:{!! config('site.contact.email') !!}">{!! config('site.contact.email') !!}</a>
                    </div>
                </address>

                <ul class="hidden md:grid md:grid-flow-col md:gap-6 print:hidden">
                    @foreach (config('site.contact.social') as $name => $url)
                        <li>
                            <a href="{!! $url !!}" target="_blank" rel="nofollow noreferrer noopener">
                                {!! $name !!}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <div class="wrap">
            <ul class="grid md:grid-flow-col justify-start links links-white text-xs py-4 opacity-50 md:justify-end md:gap-6 print:hidden">
                <li>
                    <a href="{!! route('legal.privacy') !!}">Privacy</a>
                </li>
                <li>
                    <a href="{!! route('legal.disclaimer') !!}">Disclaimer</a>
                </li>
            </ul>
        </div>
    </div>

    <x-shape-divider
        shape="tilt"
        :flip="true"
        :invert="false"
        position="top"
        height="240px"
        width="100%"
        class="footer-divider print:hidden"
    />
</footer>
