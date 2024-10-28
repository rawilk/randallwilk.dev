@props([
    'align' => 'left',
    'balanceText' => false,
    'prettyText' => true,
])

<div
    {{
        $attributes->class([
            'fi-modal-description text-sm text-gray-500 dark:text-gray-400 space-y-3',
            match ($align) {
                default => 'text-left',
                'center' => 'text-center',
                'right' => 'text-right',
            },
            'text-balance' => $balanceText,
            'text-pretty' => $prettyText,
        ])
    }}
>
    {{ $slot }}
</div>
