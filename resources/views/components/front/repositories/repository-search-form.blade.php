@props([
    'haystacks' => [],
    'queryFromUrl' => null,
])

<div
    x-data="{
        query: @js($queryFromUrl ?? ''),
        haystacks: @js($haystacks),
        init() {
            this.$watch('query', value => {
                const url = new URL(window.location.href);
                const query = value.trim();

                url.searchParams.delete('search');

                if (query === '') {
                    url.searchParams.delete('q');
                } else {
                    url.searchParams.set('q', query);
                }

                window.history.replaceState({}, '', url);
            });
        },
        get noMatches() {
            return this.query !== '' && (! this.haystacks.some(h => h.includes(this.query.toLowerCase())));
        },
    }"
    x-on:keydown.escape.window="query = ''"
    wire:ignore.self
>
    {{ $slot }}
</div>
