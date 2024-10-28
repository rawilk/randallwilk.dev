<p class="text-lg">
    <span>
        <x-front.content-area :wrap="false">
            <x-link class="link link-gray" href="mailto:{{ config('site.contact.email') }}" :app-link="false" hide-external-indicator>
                {{ config('site.contact.email') }}
            </x-link>
            <br>
            <x-link class="link link-gray" href="https://twitter.com/intent/tweet?text=Dear+@wilkrandall+..." target="_blank" :app-link="false" hide-external-indicator>
                @wilkrandall
            </x-link>
        </x-front.content-area>
    </span>
</p>
