<x-page
    :title="__('front.open_source.support.title')"
    :description="__('front.open_source.support.description')"
>
    @include('front.pages.open-source.partials.banner-support')

    <div class="section section-group pt-0">
        <section id="resources" class="section">
            <x-front.content-area>
                <p>
                    The easiest way to support me financially is by sponsoring my open source work via
                    <a
                        href="https://github.com/sponsors/rawilk"
                        target="_blank"
                        rel="noreferrer noopener"
                    >GitHub Sponsors</a>.
                    I will always appreciate any sponsorship I receive, no matter how much it is. Every sponsorship
                    I receive helps me to be able to dedicate more time to my open source efforts.
                </p>

                <p>
                    I will also always appreciate help resolving
                    <a
                        href="https://github.com/issues?q=is%3Aopen+is%3Aissue+user%3Arawilk+is%3Apublic"
                        target="_blank"
                    >open issues</a>
                    on my packages and PR's are always welcome.
                </p>
            </x-front.content-area>
        </section>
    </div>
</x-page>
