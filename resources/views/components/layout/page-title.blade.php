@props([
    'breadcrumbs' => null,
    'breadcrumbParams' => [],
    'actions' => false,
])

<div>
    <x-breadcrumbs :breadcrumbs="$breadcrumbs" :params="$breadcrumbParams" />

    <div class="mt-2 md:flex md:items-center md:justify-between">
        <div class="flex-1 min-w-0">
            <h1 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:leading-9 sm:truncate">{{ $slot }}</h1>
        </div>

        @if ($actions)
            <div class="mt-4 flex-shrink-0 flex md:mt-0 md:ml-4">
                {{ $actions }}
            </div>
        @endif
    </div>
</div>
