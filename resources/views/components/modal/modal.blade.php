<div x-data="{
        show: @entangle($attributes->wire('model')),
        hideModal($dispatch) {
            this.show = false;
            $dispatch('modal-closed', '{{ $id }}');
        },
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
     {{--x-init="$watch('show', value => value && setTimeout(autofocus, 50))"--}}
     x-init="$watch('show', value => {
        if (value) {
            $dispatch('modal-shown', '{{ $id }}');
        }

        value && setTimeout(autofocus, 50);
     })"
     x-on:close.stop="hideModal($dispatch)"
     x-on:keydown.escape.window="hideModal($dispatch)"
     x-on:keydown.tab.prevent="$event.shiftKey || nextFocusable().focus()"
     {{-- this throws an error for some reason... --}}
     {{--x-on:keydown.shift.tab.prevent="prevFocusable.focus()"--}}
     x-show="show"
     class="fixed z-top inset-0 overflow-y-auto"
     style="display: none;"
     id="{{ $id }}"
>
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        {{-- Backdrop --}}
        <div x-show="show"
             class="fixed inset-0 transform transition-opacity"
             x-on:click="hideModal($dispatch)"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
        >
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>

        {{-- This element is to trick the browser into centering the modal contents. --}}
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen"></span>&#8203;

        {{-- Modal --}}
        <div x-show="show"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             role="dialog"
             aria-modal="true"
             {{ $attributes->except(['wire:model', 'wire:model.defer'])->merge([
                'class' => "inline-block align-bottom text-left bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:w-full {$maxWidth()}"
             ]) }}
        >
            @if ($showClose)
                <div class="hidden sm:block absolute top-0 right-0 z-10 pt-4 pr-4">
                    <button x-on:click="hideModal($dispatch)"
                            type="button"
                            class="text-gray-400 hover:text-gray-500 focus:outline-none focus:text-gray-500 transition ease-in-out duration-150"
                            aria-label="{{ __('Close') }}"
                    >
                        <x-heroicon-s-x class="h-6 w-6" />
                    </button>
                </div>
            @endif

            {{ $slot }}
        </div>
    </div>
</div>
