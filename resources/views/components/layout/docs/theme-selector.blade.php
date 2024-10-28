<div
    {{
        $attributes
            ->merge([
                'x-data' => 'themeSwitcher',
                'x-bind:data-theme' => 'theme',
            ], escape: false)
    }}
>
    <x-filament::dropdown
        placement="bottom-center"
        width="max-w-[9rem]"
        teleport
    >
        <x-slot:trigger>
            <p class="sr-only">Theme</p>
            <button
                type="button"
                class="flex w-9 h-9 items-center justify-center rounded-full ring-1 ring-black/15 dark:ring-inset dark:bg-slate-700 dark:ring-white/15"
                aria-label="Open theme options"
            >
                <x-heroicon-m-sun class="hidden w-6 h-6 fill-sky-400 [[data-theme=light]_&]:block" />
                <x-heroicon-m-moon class="hidden w-6 h-6 fill-sky-400 [[data-theme=dark]_&]:block" />
                <x-heroicon-m-sun class="hidden w-6 h-6 fill-slate-400 [:not(.dark)[data-theme=system]_&]:block" />
                <x-heroicon-m-moon class="hidden w-6 h-6 fill-slate-400 [.dark[data-theme=system]_&]:block" />
            </button>
        </x-slot:trigger>

        <x-filament::dropdown.list class="space-y-1" aria-label="Theme options">
            @foreach (App\Enums\ThemeOption::cases() as $case)
                @php
                    $jsValue = Js::from($case->value);
                @endphp

                <button
                    x-on:click="(theme = {{ $jsValue }}) && close()"
                    x-bind:aria-selected="theme === {{ $jsValue }}"
                    class="w-full flex cursor-pointer select-none items-center rounded-[0.5rem] p-1 hover:bg-gray-100 dark:hover:bg-white/5 transition duration-200"
                    x-bind:class="{
                        'text-sky-500': theme === {{ $jsValue }},
                        'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white': theme !== {{ $jsValue }},
                    }"
                >
                    <div
                        class="rounded-md bg-white p-1.5 ring-1 ring-gray-900/5 dark:bg-slate-700 dark:ring-inset dark:ring-white/5 transition duration-200"
                    >
                        <x-dynamic-component
                            :component="$case->getIcon()"
                            class="w-4 h-4"
                            x-bind:class="{
                                'fill-slate-400': theme !== {{ $jsValue }},
                                'fill-sky-400 dark:fill-sky-400': theme === {{ $jsValue }},
                            }"
                        />
                    </div>

                    <span class="ml-3">{{ $case->getLabel() }}</span>
                </button>
            @endforeach
        </x-filament::dropdown.list>
    </x-filament::dropdown>
</div>
