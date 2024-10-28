<div
    x-data="{
        theme: null,
        prefersDarkMode: false,

        init() {
            this.$watch('theme', () => {
                this.$dispatch('theme-changed', this.theme);
            });

            this.prefersDarkMode = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;

            this.theme = localStorage.getItem('theme') || @js(filament()->getDefaultThemeMode()->value);
        },

        shouldShow(theme) {
            if (this.theme === 'system') {
                return theme === 'light'
                    ? this.prefersDarkMode === true
                    : this.prefersDarkMode === false;
            }

            return theme !== this.theme;
        },
    }"
    wire:ignore
>
    <x-auth.theme-switcher-button
        theme="light"
        icon="heroicon-m-sun"
    />

    <x-auth.theme-switcher-button
        theme="dark"
        icon="heroicon-m-moon"
    />
</div>
