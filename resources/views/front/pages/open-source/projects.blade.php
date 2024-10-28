<x-page
    :title="__('front.open_source.projects.title')"
    :description="__('front.open_source.projects.description')"
    :needs-livewire-scripts="false"
>
    @push('styles')
        <style>html { scroll-behavior: smooth; }</style>
    @endpush

    @include('front.pages.open-source.partials.banner-projects')

    <div class="section section-group pt-0">
        @include('front.pages.open-source.partials.projects-intro')

        @livewire(App\Livewire\Front\Repositories::class, [
            'type' => 'projects',
        ])
    </div>

    <x-slot:call-to-action>
        <x-layout.front.support-cta />
    </x-slot:call-to-action>
</x-page>
