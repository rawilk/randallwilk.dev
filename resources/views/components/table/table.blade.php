<div class="align-middle min-w-full overflow-x-auto shadow overflow-hidden lg:rounded-lg relative">
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
               class="text-blue-gray-500 {{ $border ? 'bg-white divide-blue-gray-200': '' }}"
               @if ($tbodyRef) x-ref="{{ $tbodyRef }}" @endif
        >
            {{ $slot }}
        </tbody>
    </table>
</div>
