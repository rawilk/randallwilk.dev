@php /** @var \App\Docs\Repository $repository */ @endphp

<x-page title="{{ $page->title }} | {{ $repository->slug }}"
        description="{{ $repository->slug }}"
>
    @include('front.pages.docs.partials.breadcrumbs')

    <section class="wrap grid pb-24 gap-8 md:grid-cols-3 items-stretch">
        <div class="z-10 print:hidden">
            @include('front.pages.docs.partials.navigation')
        </div>

        <div class="md:col-span-2">
            @if ($showBigTitle)
                <div class="mb-16">
                    <h1 class="banner-slogan">{{ $repository->slug }}</h1>
                    <div class="banner-intro flex items-center justify-start">
                        {{ $alias->slogan }}
                    </div>
                </div>

                <h2 class="title-xl mb-8">{{ $page->title }}</h2>
            @else
                <h1 class="title-xl mb-8">{{ $page->title }}</h1>
            @endif

            <div class="markup markup-titles markup-lists markup-code links-black links-underline {{ str_replace('/', '-', $page->slug) }}">
                {!! $page->contents !!}
            </div>
        </div>
    </section>
</x-page>
