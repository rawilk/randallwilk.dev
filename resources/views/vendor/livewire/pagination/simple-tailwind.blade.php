<div>
    <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-blue-gray-200 sm:px-6">
        <div class="hidden sm:block">
            <p class="text-sm leading-5 text-blue-gray-700">
                {!! __('pagination.showing', [
                    'from' => $paginator->firstItem(),
                    'to' => $paginator->lastItem(),
                    'total' => $paginator->total(),
                ]) !!}
            </p>
        </div>

        @if ($paginator->hasPages())
            <nav role="navigation" aria-label="{{ __('pagination.label') }}" class="flex justify-between space-x-2">
                <span>
                    {{-- Previous Page Link --}}
                    @if ($paginator->onFirstPage())
                        <span class="button button--white button--md button--disabled" aria-disabled="true">
                            {!! __('pagination.previous') !!}
                        </span>
                    @else
                        <x-button wire:click="previousPage"
                                  wire:target="previousPage"
                                  rel="previous"
                                  variant="white"
                        >
                            {!! __('pagination.previous') !!}
                        </x-button>
                    @endif
                </span>

                <span>
                    {{-- Next Page Link --}}
                    @if ($paginator->hasMorePages())
                        <x-button wire:click="nextPage"
                                  wire:target="nextPage"
                                  rel="next"
                                  variant="white"
                        >
                            {!! __('pagination.next') !!}
                        </x-button>
                    @else
                        <span class="button button--white button--md button--disabled" aria-disabled="true">
                            {!! __('pagination.next') !!}
                        </span>
                    @endif
                </span>
            </nav>
        @endif
    </div>
</div>
