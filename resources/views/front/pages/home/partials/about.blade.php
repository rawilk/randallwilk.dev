<section id="about" class="py-20 bg-gray-50">
    <div class="wrap">
        <h2 class="title-2xl">{{ __('front.home.about.tech_background_title') }}</h2>
    </div>

    <x-front.content-area>
        {!! Str::markdown(__('front.home.about.tech_background')) !!}
    </x-front.content-area>
</section>
