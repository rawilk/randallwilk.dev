<x-slide-over :id="$id" :wide="$wide" :show-close="false" {{ $attributes }}>
    <x-slot name="header">
        <header class="px-4 py-6 bg-blue-gray-200 sm:px-6">
            <div class="flex items-start justify-between space-x-3">
                <div class="space-y-1">
                    @if ($title)
                        <h2 class="text-lg leading-7 font-medium text-blue-gray-900">
                            {{ $title }}
                        </h2>
                    @endif

                    @if ($subTitle)
                        <p class="text-sm text-blue-gray-500 leading-5">
                            {{ $subTitle }}
                        </p>
                    @endif
                </div>

                <div class="h-7 flex items-center">
                    <button x-on:click="show = false"
                            aria-label="{{ __('Close Panel') }}"
                            class="text-blue-gray-400 hover:text-blue-gray-300 hover:bg-blue-gray-400 rounded-full transition-colors focus:outline-blue-gray p-3"
                    >
                        <x-heroicon-s-x class="h-6 w-6"  />
                    </button>
                </div>
            </div>
        </header>
    </x-slot>

    {{ $slot }}

    @if ($footer)
        <x-slot name="footer">
            <div class="bg-blue-gray-100 px-4 py-4">
                {{ $footer }}
            </div>
        </x-slot>
    @endif
</x-slide-over>
