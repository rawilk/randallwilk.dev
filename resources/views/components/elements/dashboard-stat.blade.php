@props([
    'icon' => false,
    'title' => '',
    'count' => 0,
    'url' => '#',
    'urlDescription' => '',
])

<div class="flex flex-col bg-white overflow-hidden shadow md:rounded-lg">
    <div class="flex-grow px-4 py-5 sm:p-6">
        <div class="flex items-center">
            @if ($icon)
                <div class="flex-shrink-0 bg-blue-500 rounded-md p-3">
                    <x-dynamic-component :component="$icon" class="h-6 w-6 text-white" />
                </div>
            @endif

            <div class="ml-5 w-0 flex-1">
                <dt class="text-sm font-medium text-blue-gray-500 truncate">
                    {{ $title }}
                </dt>
                <dd class="flex items-baseline">
                    <div class="text-2xl font-semibold text-gray-900">
                        {{ number_format($count) }}
                    </div>
                </dd>
            </div>
        </div>
    </div>

    <div class="bg-blue-gray-50 px-4 py-4 sm:px-6">
        <div class="text-sm">
            <a href="{{ $url }}"
               class="font-medium app-link"
               @if (isExternalLink($url))
                   target="_blank"
                   rel="noopener nofollow noreferrer"
               @endif
            >
                {{ __('View all') }} <span class="sr-only">{{ $urlDescription }}</span>
            </a>
        </div>
    </div>
</div>
