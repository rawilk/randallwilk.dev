<ul>
    @foreach ($navigation as $key => $section)
        <li>
            <h2>{{ $key === '_root' ? 'Getting Started' : $section['_index']['title'] }}</h2>

            <ul>
                @foreach ($section['pages'] as $navItem)
                    <li class="@if ($page->slug === $navItem->slug) active @endif">
                        <a href="{{ $navItem->url }}">
                            <span>{{ $navItem->title }}</span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </li>
    @endforeach
</ul>
