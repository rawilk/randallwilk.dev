<div class="flex items-center justify-between">
    {{-- mobile simple nav --}}
    <div class="flex-1 flex justify-between sm:hidden">
        <span>
            @if ($paginator->onFirstPage())
                <span class="button button--white button--md button--disabled" aria-disabled="true">
                    {!! __('pagination.previous') !!}
                </span>
            @else
                <x-button wire:click="previousPage"
                          wire:target="previousPage"
                          variant="white"
                          rel="previous"
                >
                    {!! __('pagination.previous') !!}
                </x-button>
            @endif
        </span>

        <span>
            @if ($paginator->hasMorePages())
                <x-button wire:click="nextPage"
                          wire:target="nextPage"
                          variant="white"
                          rel="next"
                >
                    {!! __('pagination.next') !!}
                </x-button>
            @else
                <span class="button button--white button--md button--disabled" aria-disabled="true">
                    {!! __('pagination.next') !!}
                </span>
            @endif
        </span>
    </div>

    {{-- desktop nav --}}
    <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
        <div>
            @if ($paginator->total() > 0)
                <p class="text-sm text-blue-gray-700">
                    {!! __('pagination.showing', [
                        'from' => $paginator->firstItem(),
                        'to' => $paginator->lastItem(),
                        'total' => $paginator->total(),
                    ]) !!}
                </p>
            @endif
        </div>

        <div>
            @if ($paginator->hasPages())
                <nav role="navigation" aria-label="{{ __('pagination.label') }}" class="relative z-0 inline-flex shadow-sm -space-x-px">
                    {{-- Previous Page Link --}}
                    @if ($paginator->onFirstPage())
                        <span class="button button--white button--disabled button--md button--icon rounded-r-none"
                              aria-disabled="true"
                              aria-label="{{ __('pagination.previous') }}"
                        >
                            <x-heroicon-s-chevron-left class="opacity-75" />
                        </span>
                    @else
                        <x-button wire:click="previousPage" variant="white" rel="prev" icon aria-label="{{ __('pagination.previous') }}" class="rounded-r-none">
                            <x-heroicon-s-chevron-left />
                        </x-button>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($elements as $element)
                        {{-- "Three Dots" Separator --}}
                        @if (is_string($element))
                            <span aria-disabled="true">
                                <span class="button button--white button--md rounded-none bg-blue-gray-100 button--disabled">{{ $element }}</span>
                            </span>
                        @endif

                        {{-- Array of links --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                <span wire:key="paginator-page-{{ $page }}">
                                    @if ($page === $paginator->currentPage())
                                        <span aria-current="page">
                                            <span class="button button--white button--md button--disabled rounded-none bg-blue-gray-100 hover:bg-blue-gray-100">
                                                {{ $page }}
                                            </span>
                                        </span>
                                    @else
                                        <x-button wire:click="gotoPage({{ $page }})"
                                                  class="rounded-none"
                                                  variant="white"
                                                  aria-label="{{ __('Go to page :page', ['page' => $page]) }}"
                                        >
                                            {{ $page }}
                                        </x-button>
                                    @endif
                                </span>
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($paginator->hasMorePages())
                        <x-button wire:click="nextPage" variant="white" rel="next" icon aria-label="{{ __('pagination.next') }}" class="rounded-l-none">
                            <x-heroicon-s-chevron-right />
                        </x-button>
                    @else
                        <span class="button button--white button--disabled button--md button--icon rounded-l-none"
                              aria-disabled="true"
                              aria-label="{{ __('pagination.next') }}"
                        >
                            <x-heroicon-s-chevron-right class="opacity-75" />
                        </span>
                    @endif
                </nav>
            @endif
        </div>
    </div>
</div>
