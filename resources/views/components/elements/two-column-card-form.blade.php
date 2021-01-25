@props([
    'title' => false,
    'description' => false,
])

<div {{ $attributes }}>
    <div class="md:grid md:grid-cols-3 md:gap-6">
        <div class="md:col-span-1">
            <div class="px-4 sm:px-0">
                @if ($title)
                    <h3 class="text-lg font-medium leading-6 text-blue-gray-900">{{ $title }}</h3>
                @endif

                @if ($description)
                    <div class="mt-1 text-sm leading-5 text-blue-gray-600">{{ $description }}</div>
                @endif
            </div>
        </div>

        <div class="mt-5 md:mt-0 md:col-span-2">
            {{ $slot }}
        </div>
    </div>
</div>
