<div
    x-data
    {{ $attributes->except(['x-data', 'style'])->class('h-auto max-w-full') }}
    style="max-width: min(100%, calc(100vw - 2rem)); {{ $attributes->get('style') }}"
>
    <div role="button"
         class="cursor-pointer"
         x-on:click="$dispatch('lightbox-show', { src: '{{ $src }}', description: '{{ $alt }}' })"
    >
        <img
            src="{{ $src }}"
            alt="{{ $alt }}"
            class="w-full h-full object-cover m-0"
        >
    </div>
</div>

@once
<div
    x-data="{
        src: '',
        description: '',
        show: false,
        zoomed: false,
        showDialog({ src, description }) {
            this.show = true;
            this.src = src;
            this.description = description;
            this.zoomed = false;
            document.body.classList.add('overflow-hidden');
        },
        hide() {
            this.show = false;
            this.src = '';
            this.description = '';
            this.zoomed = false;
            document.body.classList.remove('overflow-hidden');
        },
        toggleZoom() {
            this.zoomed = ! this.zoomed;
        },
    }"
    x-on:lightbox-show.window="showDialog($event.detail)"
    class="relative z-101"
    x-show="show"
    role="dialog"
    aria-modal="true"
    x-on:click.away="hide"
    x-on:keydown.esc.window="hide"
    x-cloak
>
    {{-- backdrop --}}
    <div x-show="show"
         class="fixed inset-0 bg-gray-800/75 transition-opacity backdrop-blur"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         x-on:click="hide"
         x-cloak
    >
    </div>

    {{-- content --}}
    <div class="fixed inset-0 z-101" x-on:click="hide">
        <div class="flex min-h-full items-center justify-center p-4 text-center"
             x-show="show"
             x-cloak
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
        >
            <div class="fixed top-4 right-14">
                <button
                    type="button"
                    x-on:click.stop="toggleZoom"
                    x-bind:aria-label="zoomed ? '{{ __('Fit image to screen') }}' : '{{ __('Zoom image') }}'"
                    class="text-white opacity-75 hover:opacity-100 transition-opacity outline-none focus:outline-none"
                >
                    <x-heroicon-m-magnifying-glass-plus x-show="! zoomed" class="h-6 w-6" />
                    <x-heroicon-m-arrows-pointing-in x-show="zoomed" class="h-6 w-6" />
                </button>
            </div>

            <div class="fixed top-4 right-4">
                <button
                    type="button"
                    x-on:click.stop="hide"
                    class="text-white opacity-75 hover:opacity-100 transition-opacity outline-none focus:outline-none"
                >
                    <x-heroicon-m-x-mark class="h-6 w-6" />
                    <span class="sr-only">{{ __('Close lightbox') }}</span>
                </button>
            </div>

            <div
                class="flex w-full flex-col overflow-auto"
                style="max-width: min(1200px, calc(100vw - 2rem)); max-height: calc(100dvh - 2rem);"
                x-on:click.stop
            >
                <div class="p-2">
                    <img
                        x-bind:src="src"
                        x-bind:alt="description"
                        x-on:click.stop="toggleZoom"
                        x-bind:class="zoomed ? 'cursor-zoom-out' : 'cursor-zoom-in'"
                        x-bind:style="zoomed ? 'max-width: none; max-height: none;' : 'max-width: 100%; max-height: min(850px, calc(100dvh - 6rem));'"
                        class="m-0 h-auto w-auto object-contain"
                    >
                    <p x-text="description" class="text-center text-white"></p>
                </div>
            </div>
        </div>
    </div>
</div>
@endonce
