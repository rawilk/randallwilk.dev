<button
    type="button"
    x-bind:aria-expanded="JSON.stringify(open)"
    x-on:click="open = ! open"
    @class([
        'group inline-flex items-center rounded-md bg-transparent text-base font-medium hover:text-slate-900 focus:outline-none focus:ring-2 focus:ring-brand focus:ring-offset-2',
        'text-slate-500' => ! $active,
        'text-brand' => $active,
    ])>
    <span>{{ $label }}</span>

    <x-heroicon-m-chevron-down
        @class([
            'ml-2 h-5 w-5 group-hover:text-slate-500',
            'text-slate-400' => ! $active,
            'text-slate-600' => $active,
        ])
    />
</button>
