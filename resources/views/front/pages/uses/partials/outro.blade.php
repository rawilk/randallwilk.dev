<section id="outro">
    <x-front.content-area>
        <h3>{{ __('front.uses.outro_title') }}</h3>
        {!! Str::markdown(__('front.uses.outro', ['url' => route('contact'), 'prev_url' => route('archive.uses.sep-2022')])) !!}
    </x-front.content-area>
</section>
