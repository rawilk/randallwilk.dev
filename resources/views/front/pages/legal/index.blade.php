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

    <section class="section pt-0">
        <div class="wrap sm:grid sm:grid-cols-6">
            <div class="sm:col-span-3 border-l-4 pl-4 border-blue-gray-400">
                <h2 class="title-sm">Policies & disclaimers</h2>

                <ul class="links-underline links-black">
                    <li class="mt-4">
                        <a href="{!! route('legal.disclaimer') !!}">Disclaimer</a>
                    </li>
                    <li class="mt-2">
                        <a href="{!! route('legal.privacy') !!}">Privacy policy</a>
                    </li>
                </ul>
            </div>
        </div>
    </section>
</x-page>
