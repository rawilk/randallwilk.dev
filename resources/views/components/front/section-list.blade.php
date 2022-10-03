@props([
    'heading' => '',
])

<section
    aria-labelledby="section-{{ $attributes->get('id') }}"
    class="md:border-l md:border-slate-100 md:pl-6"
>
    <div class="grid grid-cols-1 items-baseline gap-y-8 md:grid-cols-4">
        <h2 id="section-{{ $attributes->get('id') }}">{{ $heading }}</h2>

        <div class="md:col-span-3">
            <ul role="list" class="space-y-16">
                {{ $slot }}
            </ul>
        </div>
    </div>
</section>
