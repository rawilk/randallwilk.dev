<div
    x-data
    {{ $attributes->except('x-data')->class('max-w-full h-auto') }}
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
        showDialog({ src, description }) {
            this.show = true;
            this.src = src;
            this.description = description;
            document.body.classList.add('overflow-hidden');
        },
        hide() {
            this.show = false;
            this.src = '';
            this.description = '';
            document.body.classList.remove('overflow-hidden');
        },
    }"
    x-on:lightbox-show.window="showDialog($event.detail)"
    class="relative z-[101]"
    x-show="show"
    role="dialog"
    aria-modal="true"
    x-on:click.away="hide"
    x-on:keydown.esc.window="hide"
    x-cloak
>
    {{-- backdrop --}}
    <div x-show="show"
         class="fixed inset-0 bg-gray-800 bg-opacity-75 transition-opacity backdrop-blur"
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
    <div class="fixed inset-0 z-[101]" x-on:click="hide">
        <div class="flex min-h-full items-end justify-center p-4 sm:p-0 sm:items-center text-center"
             x-show="show"
             x-cloak
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        >
            <div class="fixed top-4 right-4">
                <button
                    type="button"
                    x-on:click="hide"
                    class="text-white opacity-75 hover:opacity-100 transition-opacity outline-none focus:outline-none"
                >
                    <x-heroicon-m-x-mark class="h-6 w-6" />
                    <span class="sr-only">{{ __('Close lightbox') }}</span>
                </button>
            </div>

            <div class="flex flex-col max-h-full overflow-auto">
                <div class="p-2">
                    <img x-bind:src="src" x-bind:alt="description" class="m-0 object-contain max-w-[800px] max-h-[600px] sm:max-w-[1200px] sm:max-h-[850px]">
                    <p x-text="description" class="text-center text-white"></p>
                </div>
            </div>
        </div>
    </div>
</div>
@endonce
