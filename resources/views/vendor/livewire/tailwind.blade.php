{{-- results total --}}
<div class="text-center">
    @if ($paginator->total() > 0)
        <p class="text-sm text-slate-700 dark:text-slate-300">
            {!! __('pagination.showing', [
                'from' => number_format($paginator->firstItem()),
                'to' => number_format($paginator->lastItem()),
                'total' => number_format($paginator->total()),
            ]) !!}
        </p>
    @endif
</div>

{{-- nav --}}
@if ($paginator->hasPages())
    <div class="mt-4 flex justify-center"
         x-data="{
            visitPage(page) {
                page = Number(page);
                const max = Number({{ $paginator->lastPage() }});

                if (page < 1) page = 1
                if (page > max) page = max

                @this.gotoPage(page);
            }
         }"
    >
        <nav role="navigation"
             aria-label="{{ __('Pagination Navigation') }}"
             class="relative z-0 inline-flex"
        >
            @unless ($paginator->onFirstPage())
                {{-- first page link --}}
                <x-blade::button.icon
                    wire:click="gotoPage(1)"
                    size="sm"
                    variant="text"
                    color="slate"
                    aria-label="{{ __('pagination.first') }}"
                >
                    <x-heroicon-s-chevron-double-left />
                </x-blade::button.icon>

                {{-- previous page link --}}
                <x-blade::button.icon
                    wire:click="previousPage"
                    size="sm"
                    variant="text"
                    color="slate"
                    aria-label="{{ __('pagination.previous') }}"
                    rel="prev"
                >
                    <x-heroicon-s-chevron-left />
                </x-blade::button.icon>
            @endunless

            {{-- page select --}}
            <div>
                <x-input
                    type="number"
                    min="1"
                    max="{{ $paginator->lastPage() }}"
                    step="1"
                    value="{{ $paginator->currentPage() }}"
                    x-on:keydown.enter.prevent="visitPage($el.value)"
                    x-on:blur="visitPage($el.value)"
                    x-on:change.debounce="visitPage($el.value)"
                    class="mx-2"
                />
            </div>

            @if ($paginator->hasMorePages())
                {{-- next page link --}}
                <x-blade::button.icon
                    wire:click="nextPage"
                    size="sm"
                    variant="text"
                    color="slate"
                    aria-label="{{ __('pagination.next') }}"
                    rel="next"
                >
                    <x-heroicon-s-chevron-right />
                </x-blade::button.icon>

                {{-- last page link --}}
                <x-blade::button.icon
                    wire:click="gotoPage({{ $paginator->lastPage() }})"
                    size="sm"
                    variant="text"
                    color="slate"
                    aria-label="{{ __('pagination.last') }}"
                    rel="next"
                >
                    <x-heroicon-s-chevron-double-right />
                </x-blade::button.icon>
            @endif
        </nav>
    </div>
@endif
