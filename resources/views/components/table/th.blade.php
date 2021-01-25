@props([
    'scope' => 'col',
    'role' => 'columnheader',
    'colIndex' => false,
    'sortable' => null,
    'direction' => null,
    'multiColumn' => null,
    'hidden' => false,
    'align' => 'left',
    'nowrap' => false,
])

@unless($hidden)
<th tabindex="0"
    @if ($scope) scope="{{ $scope }}" @endif
    @if ($role) role="{{ $role }}" @endif
    @if ($colIndex) aria-colindex="{{ $colIndex }}" @endif
    {{ $attributes->merge(['class' => 'relative overflow-hidden border-blue-gray-200 bg-blue-gray-50 text-left text-blue-gray-500 text-xs leading-4 font-medium uppercase focus:outline-none tracking-wider px-6 py-3'])->only('class') }}
>
    @unless($sortable)
        <span class="text-{{ $align }} w-full">{{ $slot }}</span>
    @else
        <button {{ $attributes->except('class') }}
                @if ($direction)
                    aria-sort="{{ $direction === 'asc' ? 'ascending' : 'descending' }}"

                    @if ($multiColumn && $direction === 'desc')
                        aria-label="{{ __('Click to clear sorting') }}"
                        title="{{ __('Click to clear sorting') }}"
                    @else
                        aria-label="{{ __('Click to sort :direction', ['direction' => $direction === 'asc' ? 'Descending' : 'Ascending']) }}"
                        title="{{ __('Click to sort :direction', ['direction' => $direction === 'asc' ? 'Descending' : 'Ascending']) }}"
                    @endif
                @else
                    aria-sort="none"
                    aria-label="{{ __('Click to sort :direction', ['direction' => 'Ascending']) }}"
                    title="{{ __('Click to sort :direction', ['direction' => 'Ascending']) }}"
                @endif
                class="flex items-center space-x-1 text-{{ $align }} text-xs leading-4 font-medium text-cool-gray-500 uppercase tracking-wider group focus:outline-none focus:underline @if ($nowrap) whitespace-no-wrap @endif"
        >
            <span>{{ $slot }}</span>

            <span class="relative flex items-center">
                @if ($multiColumn)
                    @if ($direction === 'asc')
                        <x-heroicon-s-chevron-up class="w-4 h-4 group-hover:opacity-0" />
                        <x-heroicon-s-chevron-down class="w-4 h-4 opacity-0 group-hover:opacity-100 absolute" />
                    @elseif ($direction === 'desc')
                        <div class="opacity-0 group-hover:opacity-100 absolute">
                            <x-heroicon-s-selector class="w-4 h-4" />
                        </div>

                        <x-heroicon-s-chevron-down class="w-4 h-4 group-hover:opacity-0" />
                    @else
                        <x-heroicon-s-chevron-up class="w-4 h-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300" />
                    @endif

                @else
                    @if ($direction === 'asc')
                        <x-heroicon-s-chevron-up class="w-4 h-4" />
                    @elseif ($direction === 'desc')
                        <x-heroicon-s-chevron-down class="w-4 h-4" />
                    @else
                        <x-heroicon-s-chevron-up class="w-4 h-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300" />
                    @endif
                @endif
            </span>
        </button>
    @endunless
</th>
@endunless
