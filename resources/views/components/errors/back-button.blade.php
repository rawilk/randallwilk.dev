@props(['url' => '#'])

<div
    class="mt-4"
    x-data="{
        refresh() {
            @if (request()->headers->has('x-livewire'))
                const iframe = window.parent.document.getElementById('livewire-error');

                iframe && iframe.remove();
            @else
                window.location = @js($url);
            @endif
        }
    }"
>
    <x-filament::button
        :color="\Filament\Support\Colors\Color::Blue"
        x-on:click="refresh"
        icon="heroicon-m-arrow-left"
    >
        {{ request()->headers->has('x-livewire') ? __('messages.errors.home_button_livewire') : __('messages.errors.home_button') }}
    </x-filament::button>
</div>
