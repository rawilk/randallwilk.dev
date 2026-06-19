<div class="grid grid-cols-1 w-full">
    <input
        {{
            $attributes
                ->class([
                    'w-full col-start-1 row-start-1 block rounded-md',
                    'bg-white text-gray-900 outline-gray-300 placeholder:text-gray-400 focus:outline-blue-600',
                    'py-3 pr-14 pl-4 text-base sm:text-sm/6 sm:pr-12',
                    'outline-1 -outline-offset-1 focus:outline-2 focus:-outline-offset-2',
                ])
                ->merge([
                    'type' => 'search',
                    'autocomplete' => 'off',
                ])
        }}
    >

    <x-filament::icon
        :icon="Filament\Support\Icons\Heroicon::MagnifyingGlass"
        class="pointer-events-none col-start-1 row-start-1 mr-3 size-6 self-center justify-self-end text-gray-400 sm:size-5"
    />
</div>
