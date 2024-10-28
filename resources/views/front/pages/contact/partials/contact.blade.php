<section id="email" class="section">
    <x-front.content-area>
        {{
            str(__('front.contact.contact_details', ['email' => config('randallwilk.contact.email')]))
                ->markdown()
                ->toHtmlString()
        }}

        <p>{{ __('front.contact.social_media_intro') }}</p>

        <ul>
            @foreach (config('randallwilk.contact.social') as $name => $socialContact)
                <li class="text-base">
                    <span>{{ $name }}:</span>
                    <a href="{{ $socialContact['url'] }}" rel="nofollow noreferrer">{{ $socialContact['url'] }}</a>
                </li>
            @endforeach
        </ul>
    </x-front.content-area>
</section>
