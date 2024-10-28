@props([
    'icon',
    'heading',
    'placeholder' => '',
    'username' => null,
])

<li {{ $attributes->class('flex items-center justify-between gap-x-4') }}>
    <div class="flex-1 flex gap-x-4">
        <div class="shrink-0">
            <x-filament::icon
                :icon="$icon"
                class="h-8 w-8 text-gray-600 dark:text-gray-200"
            />
        </div>

        <div class="flex-1">
            <div class="font-semibold text-base -mt-0.5">
                {{ $heading }}
            </div>

            <div class="text-sm">
                @if (filled($username))
                    <div class="flex items-center gap-x-1">
                        <span class="order-1">
                            {{
                                str(__('users/profile.connected_accounts.connected_account', [
                                    'username' => e($username),
                                ]))
                                    ->inlineMarkdown()
                                    ->toHtmlString()
                            }}
                        </span>

                        <x-filament::icon
                            icon="heroicon-m-check-circle"
                            class="h-5 w-5 text-success-500 dark:text-success-600 order-0"
                        />
                    </div>
                @else
                    {{ $placeholder }}
                @endif
            </div>
        </div>
    </div>

    <div class="shrink-0 pl-2">
        {{ $slot }}
    </div>
</li>
