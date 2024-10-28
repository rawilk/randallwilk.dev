<x-page
    title="Documentation"
    description="Documentation for my open source packages."
>
    <x-front.page-banner>
        Docs

        <x-slot:content>
            <p class="banner-intro">
                Documentation for my open source packages.
            </p>
        </x-slot:content>
    </x-front.page-banner>

    <div class="wrap space-y-14 divide-y divide-gray-300 pt-10 pb-20">
        @foreach ($repositories->groupBy('category') as $category => $repositories)
            <section class="pt-14 first:pt-0">
                <h2 class="uppercase font-bold text-2xl mb-10">
                    {{ $category }}
                </h2>

                <div class="grid gap-10 md:grid-cols-2 items-stretch">
                    @each('front.pages.docs.partials.repository', $repositories, 'repository')
                </div>
            </section>
        @endforeach
    </div>

    <x-slot:call-to-action>
        <x-layout.front.support-cta />
    </x-slot:call-to-action>
</x-page>
