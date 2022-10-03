@props([
    'title' => false,
])

<li>
    <section>
        @if ($title)
            <h3 class="font-semibold text-base text-slate-900">{{ $title }}</h3>
        @endif
        <ul class="mt-2 5 text-sm space-y-1 5">
            {{ $slot }}
        </ul>
    </section>
</li>
