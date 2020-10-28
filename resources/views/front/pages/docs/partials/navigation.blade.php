<nav class="h-full pr-4 py-6">
    <div class="flex items-center pb-4 border-b-2 border-gray-300">
        <div class="text-xs font-normal leading-normal select pl-0">
            <select name="alias"
                    class="form-select"
                    onchange="location = '/docs/{{ $repository->slug }}/' + this.options[this.selectedIndex].value"
            >
                @foreach ($repository->aliases as $aliasOption)
                    <option value="{{ $aliasOption->slug }}"
                            {{ $page->alias === $aliasOption->slug ? 'selected' : '' }}
                    >
                        {{ $aliasOption->slug }} @if ($aliasOption->isMasterBranch())({{ $aliasOption->branch }})@endif
                    </option>
                @endforeach
            </select>
        </div>

        <a class="ml-auto flex items-center text-xs link-gray link-underline"
           href="{{ $alias->githubUrl }}/blob/{{ $alias->isMasterBranch() ? 'master' : $alias->slug }}/docs/{{ $page->slug }}.md"
           target="_blank"
        >
            Edit

            <span class="ml-1 w-4 h-4">
                {{ renderSvg('github') }}
            </span>
        </a>
    </div>

    <div class="sticky top-0 pt-4 space-y-8">
        <input type="search"
               id="algolia-search"
               placeholder="Search..."
               class="text-xs form-input w-full h-8 py-0 px-2"
        >

        <ol class="text-xs grid gap-2 links-black">
            @foreach ($navigation as $key => $section)
                @if ($key !== '_root')
                    <h2 class="title-sm text-sm">{{ $section['_index']['title'] }}</h2>
                @endif

                <ul class="mb-6 space-y-1 links-black @if ($key !== '_root') pl-3 @endif">
                    @foreach ($section['pages'] as $navItem)
                        <li class="leading-snug">
                            <a href="{{ $navItem->url }}"
                               class="@if ($page->slug === $navItem->slug) font-bold @endif"
                            >
                                {{ $navItem->title }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            @endforeach
        </ol>
    </div>
</nav>
