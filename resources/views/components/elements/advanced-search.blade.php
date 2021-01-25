@props([
    'wireClick' => 'resetFilters',
    'slideOut' => false, // true makes the filters "slide out" from the bottom
])

@if ($slideOut)
    <div x-data="{
            show: @entangle($attributes->wire('model')),
            focusables() {
                // All focusable element types...
                let selector = 'a, button, input, textarea, select, details, [tabindex]:not([tabindex=\'-1\'])';

                return [...$refs.content.querySelectorAll(selector)]
                    // All non-disabled elements...
                    .filter(el => ! el.hasAttribute('disabled'));
            },
            firstFocusable() { return this.focusables()[0] },
            lastFocusable() { return this.focusables().slice(-1)[0] },
            nextFocusable() { return this.focusables()[this.nextFocusableIndex()] || this.firstFocusable() },
            prevFocusable() { return this.focusables()[this.prevFocusableIndex()] || this.lastFocusable() },
            nextFocusableIndex() { return (this.focusables().indexOf(document.activeElement) + 1) % (this.focusables().length + 1) },
            prevFocusableIndex() { return Math.max(0, this.focusables().indexOf(document.activeElement)) -1 },
            autofocus() { let focusable = $el.querySelector('[autofocus]'); if (focusable) focusable.focus() },
         }"
         x-init="$watch('show', value => value && setTimeout(autofocus, 500))"
         x-show="show"
         x-on:close.stop="show = false"
         x-on:keydown.escape.window="show = false"
         style="display:none;"
         wire:ignore.self
         class="fixed z-top bottom-0 left-0 right-0 top-0 w-screen overflow-hidden"
    >
        <div class="absolute inset-0 overflow-hidden">
            {{-- overlay --}}
            <div x-show="show"
                 x-on:click="show = false"
                 x-transition:enter="ease-in-out duration-500"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in-out duration-500"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="absolute inset-0 bg-blue-gray-500 bg-opacity-75 transition-opacity"
            >
            </div>

            {{-- content --}}
            <div {{ $attributes->except(['wire:model', 'wire:model.defer'])->merge([
                       'class' => 'w-screen max-w-full overflow-hidden bg-white h-3/4 sm:h-3/5 absolute bottom-0 left-0 right-0 flex flex-col'
                    ])
                 }}
                 x-show="show"
                 x-transition:enter="transform transition ease-in-out duration-500 sm:duration-700"
                 x-transition:enter-start="translate-y-full"
                 x-transition:enter-end="translate-y-0"
                 x-transition:leave="transform transition ease-in-out duration-500 sm:duration-700"
                 x-transition:leave-start="translate-y-0"
                 x-transition:leave-end="translate-y-full"
                 role="dialog"
                 aria-modal="true"
            >
                <div class="py-4 bg-blue-gray-200">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8 relative w-full flex justify-between items-center">
                        <h3 class="text-lg text-blue-gray-600 font-medium">{{ __('Advanced Search') }}</h3>

                        {{-- close button --}}
                        <div x-show="show"
                             x-transition:enter="ease-in-out duration-500"
                             x-transition:enter-start="opacity-0"
                             x-transition:enter-end="opacity-100"
                             x-transition:leave="ease-in-out duration-500"
                             x-transition:leave-start="opacity-100"
                             x-transition:leave-end="opacity-0"
                             class="flex"
                        >
                            <button x-on:click="show = false"
                                    type="button"
                                    class="text-blue-gray-400 hover:text-white p-3 rounded-full hover:bg-blue-gray-400 transition-colors focus:outline-blue-gray"
                            >
                                <span class="sr-only">{{ __('Close') }}</span>
                                <x-css-close class="h-6 w-6" />
                            </button>
                        </div>
                    </div>
                </div>

                <div x-ref="content" class="w-screen max-w-full overflow-y-auto flex-1">
                    <div class="py-6 max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
                        {{ $slot }}
                    </div>
                </div>

                <div class="w-full py-4 bg-blue-gray-50 border-t border-t-blue-gray-100">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8 flex items-center justify-end space-x-4">
                        <div wire:loading.delay.class.remove="hidden"
                             class="hidden text-blue-gray-500 text-xs font-medium flex items-center space-x-2 animate-pulse"
                        >
                            <x-heroicon-s-refresh class="h-4 w-4 animate-spin" />

                            <span>{{ __('labels.filtering_loading_state') }}</span>
                        </div>

                        <x-button.link wire:click="{{ $wireClick }}">{{ __('labels.reset_filters') }}</x-button.link>
                    </div>
                </div>
            </div>
        </div>
    </div>
@else
    <div {{ $attributes->merge(['class' => 'bg-blue-gray-200 p-4 rounded shadow-inner flex-row space-y-4 relative']) }}>
        <div>
            {{ $slot }}
        </div>

        <div class="text-right">
            <x-button.link wire:click="{{ $wireClick }}">{{ __('labels.reset_filters') }}</x-button.link>
        </div>
    </div>
@endif
