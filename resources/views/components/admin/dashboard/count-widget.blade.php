@props([
    'title' => '',
    'count' => 0,
    'icon' => false,
    'url' => false,
])

<x-card>
    <div class="flex items-center">
        @if ($icon)
            <div class="flex-shrink-0">
                <x-dynamic-component
                    :component="$icon"
                    class="h-6 w-6 text-slate-400"
                />
            </div>
        @endif

        <div class="ml-5 w-0 flex-1">
            <dl>
                <dt class="truncate text-sm font-medium text-slate-500">{{ $title }}</dt>
                <dd>
                    <div class="text-lg font-medium text-slate-900">{{ number_format($count) }}</div>
                </dd>
            </dl>
        </div>
    </div>

    @if ($url)
        <x-slot:footer>
            <div class="text-sm">
                <x-link :href="$url">{{ __('View all') }}</x-link>
            </div>
        </x-slot:footer>
    @endif
</x-card>
