<x-page
    :title="__('front.open_source.packages.title')"
    :description="__('front.open_source.packages.description')"
    :needs-livewire-scripts="false"
>
    @push('styles')
        <style>html { scroll-behavior: smooth; }</style>
    @endpush

    @include('front.pages.open-source.partials.banner-packages')

    <div class="section section-group pt-0">
        @include('front.pages.open-source.partials.packages-intro')

        @livewire(App\Livewire\Front\Repositories::class)
    </div>

    <x-slot:call-to-action>
        <x-layout.front.support-cta />
    </x-slot:call-to-action>
</x-page>
