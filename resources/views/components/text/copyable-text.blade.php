@props([
    'text',
    'tooltip' => __('actions.copyable_text.tooltip'),
    'mono' => false,
    'clamp' => false,
    'bordered' => false,
])

<span
    {{
        $attributes
            ->class([
                'pb-0.5 border-b border-dotted border-gray-600 dark:border-gray-200 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600 focus:bg-gray-100 dark:focus:bg-gray-600 focus:outline-none' => ! $bordered,
                'grid gap-x-2 w-full py-3 px-3 rounded-md border border-slate-200 bg-slate-100 text-gray-500 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200 cursor-pointer text-xs w-full' => $bordered,
                'font-mono' => $mono,
                'break-all line-clamp-1' => $clamp && ! $bordered,
            ])
            ->merge([
                'tabindex' => '0',
            ])
    }}
    x-data="copyableText({
        text: @js($text),
        tooltip: @js($tooltip),
        copiedTooltip: @js(__('actions.copyable_text.copied_tooltip'))
    })"
    x-bind="events"
    x-tooltip="getTooltipConfig()"
>
    <span
        style="grid-area: 1 / 1;"
        @class([
            'break-all line-clamp-1 max-w-[calc(100%-1.5rem)]' => $clamp && $bordered,
        ])
    >
        {{ $text }}
    </span>

    @if ($bordered)
        <span class="block h-4 w-4" style="grid-area: 1 / 1; place-self: center end;">
            <x-filament::icon
                icon="heroicon-o-clipboard"
                class="h-full w-full"
            />
        </span>
    @endif
</span>


