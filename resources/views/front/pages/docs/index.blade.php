<x-page title="Documentation"
        description="Documentation for my open source packages."
>
    <section id="banner" class="banner" role="banner">
        <div class="wrap">
            <h1 class="banner-slogan">Docs</h1>
            <p class="banner-intro">Documentation for my open source packages.</p>
        </div>
    </section>

    <section class="section pt-0">
        @foreach ($repositories->groupBy('category') as $category => $repositories)
            <div class="wrap">
                <h2 class="title line-after mb-8">{{ $category }}</h2>
            </div>

            <div class="wrap mb-24">
                <x-elements.action-item-list>
                    @each('front.pages.docs.partials.repository', $repositories, 'repository')
                </x-elements.action-item-list>
            </div>
        @endforeach
    </section>
</x-page>
