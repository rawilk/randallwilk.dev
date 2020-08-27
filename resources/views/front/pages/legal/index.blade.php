<x-page title="Legal">
    <x-slot name="description">
        General conditions, policies & disclaimers.
    </x-slot>

    <section id="banner" class="banner" role="banner">
        <div class="wrap">
            <h1 class="banner-slogan">Legal stuff</h1>
            <p class="banner-intro">Please don't sue me</p>
        </div>
    </section>

    <div class="section section-group pt-0">
        <section>
            <div class="wrap wrap-6 items-start">
                <div class="sm:col-span-3 line-l">
                    <h2 class="title-sm">Policies & disclaimers</h2>

                    <ul class="links-underline links-blue">
                        <li class="mt-4">
                            <a href="{!! route('legal.disclaimer') !!}">Disclaimer</a>
                        </li>
                        <li class="mt-4">
                            <a href="{!! route('legal.privacy') !!}">Privacy policy</a>
                        </li>
                    </ul>
                </div>
            </div>
        </section>
    </div>
</x-page>
