<div class="relative z-10" x-data="themeSelector" x-cloak>
    <x-dropdown width-class="w-36 !min-w-[9rem]">
        <x-slot:trigger>
            <label class="sr-only">{{ __('Theme') }}</label>
            <button
                    type="button"
                    class="flex h-6 w-6 items-center justify-center rounded-lg shadow-md shadow-black/5 ring-1 ring-black/5 dark:bg-slate-700 dark:ring-inset dark:ring-white/5"
            >
                <x-svg-theme.light class="hidden w-4 h-4 fill-sky-400 [[data-theme=light]_&]:block" />
                <x-svg-theme.dark class="hidden w-4 h-4 fill-sky-400 [[data-theme=dark]_&]:block" />
                <x-svg-theme.light class="hidden w-4 h-4 fill-slate-400 [:not(.dark)[data-theme=system]_&]:block" />
                <x-svg-theme.dark class="hidden w-4 h-4 fill-slate-400 [.dark[data-theme=system]_&]:block" />
            </button>
        </x-slot:trigger>

        <x-slot:menu class="!p-0 bg-white dark:bg-slate-800 rounded-md"></x-slot:menu>

        <div class="p-3 bg-white text-sm font-medium dark:bg-slate-800 rounded-md">
            @foreach (\App\Enums\ThemeOption::cases() as $case)
                <li class="flex cursor-pointer select-none items-center rounded-[0.625rem] p-1 hover:bg-slate-100 dark:hover:bg-slate-900/40"
                    x-bind:class="{
                        'text-sky-500': isSelected('{{ $case->value }}'),
                        'text-slate-700 dark:text-slate-400 hover:text-slate-900': ! isSelected('{{ $case->value }}'),
                    }"
                    role="option"
                    tabindex="-1"
                    x-on:click="selectTheme('{{ $case->value }}')"
                    x-bind:aria-selected="isSelected('{{ $case->value }}')"
                >
                    <x-dynamic-component
                            :component="'svg-' . $case->icon()"
                            class="h-4 w-4 fill-slate-400"
                            x-bind:class="{
                            'fill-slate-400': ! isSelected('{{ $case->value }}'),
                            'fill-sky-400 dark:fill-sky-400': isSelected('{{ $case->value }}'),
                        }"
                    />
                    <div class="ml-3">{{ $case->label() }}</div>
                </li>
            @endforeach
        </div>
    </x-dropdown>
</div>
