<section
    {{
        $attributes
            ->merge([
                'id' => 'resources',
            ])
            ->class([
                'section'
            ])
    }}
>
    <div class="wrap markup markup-lists counters">
        <p class="text-xl !mb-3">
            A few suggestions
        </p>

        <ul class="[&_a]:text-blue-600 hover:[&_a]:text-blue-500 hover:[&_a]:underline text-lg">
            <li>
                <a href="{{ route('home') }}">Home page</a>
            </li>

            <li>
                <a href="{{ route('open-source.packages') }}">Open source packages</a>
            </li>

            <li>
                <a href="{{ route('docs') }}">Docs</a>
            </li>

            <li>
                <a href="{{ route('contact') }}">Contact</a>
            </li>

            <li>
                <a href="{{ route('legal.privacy') }}">Privacy policy</a>
            </li>
        </ul>
    </div>
</section>
