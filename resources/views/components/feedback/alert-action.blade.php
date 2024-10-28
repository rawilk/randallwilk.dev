@props([
    'href' => false,
    'loadingIndicator' => true,
])

@php
    $classes = \Illuminate\Support\Arr::toCssClasses([
        'rounded-md',
        'bg-custom-50 dark:bg-transparent',
        'px-2 py-1.5 text-sm font-medium',
        'text-custom-800 dark:text-custom-200',
        'hover:bg-custom-100 dark:hover:bg-custom-600',
        'focus:outline-none focus:ring-2 focus:ring-custom-600 focus:ring-offset-2 focus:ring-offset-custom-50 dark:focus:ring-custom-500 dark:focus:ring-offset-custom-700',
    ]);
@endphp

@if ($href)
    @php
        $shouldWireNavigate = rescue(
            callback: fn () => filament()->getCurrentPanel()->hasSpaMode(),
            rescue: fn () => false,
            report: false,
        );
    @endphp

    <a {{ $attributes->class($classes)->merge(['href' => $href]) }}
       @if ($shouldWireNavigate)
           wire:navigate
        @endif
    >
        {{ $slot }}
    </a>
@else
    @php
        $wireTarget = $loadingIndicator ? $attributes->whereStartsWith(['wire:target', 'wire:click'])->filter(fn ($value): bool => filled($value))->first() : null;
        $hasLoadingIndicator = filled($wireTarget);

        if ($hasLoadingIndicator) {
            $loadingIndicatorTarget = html_entity_decode($wireTarget, ENT_QUOTES);
        }
    @endphp

    <button
        {{
            $attributes
                ->merge([
                    'type' => 'button',
                    'wire:loading.attr' => 'disabled',
                    'wire:target' => ($hasLoadingIndicator && $loadingIndicatorTarget) ? $loadingIndicatorTarget : null,
                ])
                ->class($classes)
        }}
    >
        @if ($hasLoadingIndicator)
            <x-filament::loading-indicator
                :attributes="
                    \Filament\Support\prepare_inherited_attributes(
                        new \Illuminate\View\ComponentAttributeBag([
                            'wire:loading.delay.' . config('filament.livewire_loading_delay', 'default') => '',
                            'wire:target' => $loadingIndicatorTarget,
                        ])
                    )->class(['fi-btn-icon transition duration-75 h-5 w-5 mr-1.5'])
                "
            />
        @endif

        <span>{{ $slot }}</span>
    </button>
@endif
