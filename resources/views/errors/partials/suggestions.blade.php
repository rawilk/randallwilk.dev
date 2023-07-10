<section id="resources" class="section">
    <div class="wrap markup markup-lists counters">
        <p class="text-lg !mb-2">{{ __('errors.suggestions.links_title') }}</p>

        <ul class="links-underline links-gray">
            <li>
                <x-link :href="route('home')">{{ __('errors.suggestions.home') }}</x-link>
            </li>
            <li>
                <x-link :href="route('open-source.packages')">{{ __('errors.suggestions.open_source') }}</x-link>
            </li>
            <li>
                <x-link :href="route('contact')">{{ __('errors.suggestions.contact') }}</x-link>
            </li>
            <li>
                <x-link :href="route('docs')">{{ __('errors.suggestions.docs') }}</x-link>
            </li>
            <li>
                <x-link :href="route('legal.privacy')">{{ __('errors.suggestions.privacy') }}</x-link>
            </li>
        </ul>
    </div>
</section>
