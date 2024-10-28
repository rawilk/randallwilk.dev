@php
    use Filament\Infolists\Components\TextEntry\TextEntrySize;

    $label = $getLabel();
    $id = $getId();
    $labelSrOnly = $isLabelHidden();
    $description = $getDescription();
    $action = $getAction();
    $content = $getContent();
@endphp

<div {{ $attributes->class(['fi-in-entry-wrp']) }}>
    @if ($label && $labelSrOnly)
        <dt class="sr-only">
            {{ $label }}
        </dt>
    @endif

    <div class="min-w-0 sm:flex sm:items-center sm:justify-between sm:gap-x-8">
        <div class="flex-1">
            @if ($label && (! $labelSrOnly))
                <x-filament-infolists::entry-wrapper.label>
                    {{ $label }}
                </x-filament-infolists::entry-wrapper.label>
            @endif

            @if ($description || $content)
                @php
                    $size = $getSize();
                    $contentBeforeDescription = $isContentBeforeDescription();
                @endphp

                <div @class([
                    'prose max-w-none dark:prose-invert [&>:first-child]:mt-0 [&>:last-child]:mb-0',
                    'pt-2' => ! $labelSrOnly,
                    match ($size) {
                        TextEntrySize::ExtraSmall, 'xs' => 'prose-xs',
                        TextEntrySize::Small, 'sm', null => 'prose-sm',
                        TextEntrySize::Medium, 'base', 'md' => 'prose-base',
                        TextEntrySize::Large, 'lg' => 'prose-lg',
                        default => $size,
                    }
                ])>
                    @if ($content && $contentBeforeDescription)
                        <div>{{ $content }}</div>
                    @endif

                    @if ($description)
                        <div
                            @class([
                                'mt-2' => $contentBeforeDescription && filled($content),
                                'mb-2' => (! $contentBeforeDescription) && filled($content),
                                match ($size) {
                                    TextEntrySize::ExtraSmall, 'xs' => 'text-xs',
                                    TextEntrySize::Small, 'sm' => 'text-sm leading-6',
                                    TextEntrySize::Medium, 'base', 'md' => 'text-base',
                                    TextEntrySize::Large, 'lg' => 'text-lg',
                                    default => $size,
                                }
                            ])
                        >
                            {{ $description }}
                        </div>
                    @endif

                    @if ($content && (! $contentBeforeDescription))
                        <div>{{ $content }}</div>
                    @endif
                </div>
            @endif
        </div>

        <div>
            @if ($action && ! $action->isHidden())
                {{ $action }}
            @endif
        </div>
    </div>
</div>
