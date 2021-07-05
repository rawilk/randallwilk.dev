<div class="align-middle min-w-full overflow-x-auto overflow-hidden relative @if ($rounded) shadow sm:rounded-lg @endif">
    <table class="{{ $tableClass($attributes->get('class')) }}"
           {{ $attributes->except('class') }}
           {{-- $tbodyRef useful for sortable tables --}}
           @unless ($tbodyRef) id="{{ $tableId() }}" @endunless
    >
        @if ($head)
            <thead role="rowgroup">
                {{ $head }}
            </thead>
        @endif

        <tbody role="rowgroup"
               class="{{ $border ? 'bg-white divide-y divide-blue-gray-200' : '' }}"
               @if ($tbodyRef) x-ref="{{ $tbodyRef }}" @endif
        >
            {{ $slot }}
        </tbody>
    </table>
</div>
