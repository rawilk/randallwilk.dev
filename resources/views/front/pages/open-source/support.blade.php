<x-page
    title="{{ __('front.open_source.support.title') }}"
    description="{{ __('front.open_source.support.description') }}"
>
    @include('front.pages.open-source.partials.banner-support')

    <div class="section section-group pt-0">
        <section id="resources" class="section">
            <x-front.content-area>
                {!! Str::markdown(__('front.open_source.support.resources', [
                    'sponsor_url' => 'https://github.com/sponsors/rawilk',
                    'open_issues_url' => 'https://github.com/issues?q=is%3Aopen+is%3Aissue+user%3Arawilk+is%3Apublic',
                ])) !!}
            </x-front.content-area>
        </section>
    </div>
</x-page>
