<section id="outro">
    <x-front.content-area>
        <h3>{{ __('front.uses.outro_title') }}</h3>
        {!! Str::markdown(__('front.uses.archive.sep-2022.outro', ['url' => route('contact')])) !!}
    </x-front.content-area>
</section>
