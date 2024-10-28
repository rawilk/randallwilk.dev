<div
    {{
        $attributes
            ->class($classes())
            ->style($styles())
            ->merge(['role' => 'alert'])
    }}
    @if ($dismiss)
        x-data="{
            dismiss() {
                @if ($removeParentOnDismiss)
                    $root.parentElement.remove();
                @else
                    $root.remove();
                @endif

                @if ($dismissCallback)
                    {{ $dismissCallback}}
                @endif
            },
        }"
    @endif
>
    <div class="flex">
        <div @class(['flex', 'flex-1' => $dismiss])>
            @if ($icon)
                <div {{ $componentSlot($icon)->attributes->class('shrink-0') }}>
                    @if (is_string($icon))
                        <x-filament::icon
                            :icon="$icon"
                            class="h-5 w-5 text-custom-400 dark:text-custom-500"
                        />
                    @else
                        {{ $icon }}
                    @endif
                </div>
            @endif

            <div @class(['flex-1', 'ml-3' => $icon])>
                @if ($title)
                    <h3 {{ $componentSlot($title)->attributes->class('text-sm font-medium text-custom-800 dark:text-custom-200') }}>
                        {{ $title }}
                    </h3>
                @endif

                <div
                    @class([
                        'text-sm text-custom-700 dark:text-white dark:font-semibold',
                        'links',
                        'mt-2' => $title,
                    ])
                >
                    {{ $slot }}
                </div>

                @if ($actions)
                    <div {{ $componentSlot($actions)->attributes->class('mt-4') }}>
                        <div class="-mx-2 -my-1.5 flex">
                            {{ $actions }}
                        </div>
                    </div>
                @endif
            </div>
        </div>

        @if ($dismiss)
            <div class="ml-auto pl-3">
                <div
                    @class([
                        '-mx-1.5 -my-2.5' => ! $compact,
                        '-mx-2 -my-1' => $compact,
                    ])
                >
                    <button
                        type="button"
                        x-on:click="dismiss"
                        @class([
                            'inline-flex rounded-md',
                            'bg-custom-50 dark:bg-custom-500/10 text-custom-500 hover:bg-custom-100 dark:hover:bg-custom-700/10',
                            'focus:outline-none focus:ring-2 focus:ring-custom-600 focus:ring-offset-2 focus:ring-offset-custom-50 dark:focus:ring-offset-custom-700',
                            'p-1.5' => ! $compact,
                            'p-1' => $compact,
                        ])
                    >
                        <span class="sr-only">{{ __('Dismiss') }}</span>

                        <x-filament::icon
                            icon="heroicon-m-x-mark"
                            @class([
                                'h-5 w-5' => ! $compact,
                                'h-4 w-4' => $compact,
                            ])
                        />
                    </button>
                </div>
            </div>
        @endif
    </div>
</div>
