<div x-data="{
        show: @entangle($attributes->wire('model')),
        focusables() {
            // All focusable element types...
            let selector = 'a, button, input, textarea, select, details, [tabindex]:not([tabindex=\'-1\'])';

            return [...$root.querySelectorAll(selector)]
                // All non-disabled elements...
                .filter(el => ! el.hasAttribute('disabled'));
        },
        firstFocusable() { return this.focusables()[0] },
        lastFocusable() { return this.focusables().slice(-1)[0] },
        nextFocusable() { return this.focusables()[this.nextFocusableIndex()] || this.firstFocusable() },
        prevFocusable() { return this.focusables()[this.prevFocusableIndex()] || this.lastFocusable() },
        nextFocusableIndex() { return (this.focusables().indexOf(document.activeElement) + 1) % (this.focusables().length + 1) },
        prevFocusableIndex() { return Math.max(0, this.focusables().indexOf(document.activeElement)) -1 },
        autofocus() { let focusable = $root.querySelector('[autofocus]'); if (focusable) focusable.focus() },
     }"
     x-init="$watch('show', value => value && setTimeout(autofocus, 500))"
     x-on:close.stop="show = false"
     x-on:keydown.escape.window="show = false"
     x-on:keydown.tab.prevent="$event.shiftKey || nextFocusable().focus()"
     x-show="show"
     style="display: none;"
     id="{{ $id }}"
    class="fixed z-top inset-0 overflow-hidden"
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

        <section class="absolute inset-y-0 right-0 pl-10 max-w-full flex {{ $wide ? 'sm:pl-16' : '' }}">

            {{-- panel --}}
            <div x-show="show"
                 x-transition:enter="transform transition ease-in-out duration-500 sm:duration-700"
                 x-transition:enter-start="translate-x-full"
                 x-transition:enter-end="translate-x-0"
                 x-transition:leave="transform transition ease-in-out duration-500 sm:duration-700"
                 x-transition:leave-start="translate-x-0"
                 x-transition:leave-end="translate-x-full"
                 role="dialog"
                 aria-modal="true"
                 {{ $attributes->except(['wire:model', 'wire:model.defer'])->merge([
                    'class' => 'w-screen ' . ($wide ? 'max-w-2xl' : 'max-w-md')
                 ]) }}
            >
                {{-- close button --}}
                @if ($showClose)
                    <div x-show="show"
                         x-transition:enter="ease-in-out duration-500"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100"
                         x-transition:leave="ease-in-out duration-500"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0"
                         class="absolute top-0 left-0 -ml-8 pt-4 pr-2 flex sm:-ml-10 sm:pr-4"
                    >
                        <button x-on:click="show = false"
                                type="button"
                                aria-label="{{ __('Close panel') }}"
                                class="text-blue-gray-300 hover:text-white transition-colors focus:outline-blue-gray"
                        >
                            <x-heroicon-s-x class="h-6 w-6" />
                        </button>
                    </div>
                @endif

                <div class="h-full flex flex-col space-y-6 bg-white shadow-xl">
                    <div class="min-h-0 flex-1 flex flex-col space-y-6 overflow-y-scroll">
                        {{-- header --}}
                        @if ($header)
                            {{ $header }}
                        @endif

                        {{-- body --}}
                        <div class="relative flex-1 px-4 sm:px-6">
                            {{ $slot }}
                        </div>
                    </div>

                    {{-- footer --}}
                    @if ($footer)
                        <div class="flex-shrink-0">
                            {{ $footer }}
                        </div>
                    @endif
                </div>
            </div>
        </section>
    </div>
</div>
