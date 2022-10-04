<section id="email" class="section">
    <x-front.content-area>
        {!! Str::markdown(__('front.contact.contact_details', ['email' => config('site.contact.email')])) !!}

        <p>{{ __('front.contact.social_media_intro') }}</p>

        <ul>
            @foreach (config('site.contact.social') as $name => $url)
                <li class="text-base">
                    <span>{!! $name !!}:</span>
                    <a href="{{ $url }}" rel="nofollow noreferrer">{{ $url }}</a>
                </li>
            @endforeach
        </ul>
    </x-front.content-area>
</section>
