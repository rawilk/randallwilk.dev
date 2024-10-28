export default () => ({
    theme: null,

    init() {
        this.$watch('theme', () => {
            // Prevent weird transitions in the header
            const transitionClass = '[&_*]:!transition-none';
            document.documentElement.classList.add(transitionClass);

            window.setTimeout(() => {
                document.documentElement.classList.remove(transitionClass);
            }, 0);

            this.$dispatch('theme-changed', this.theme);
        });

        this.theme = localStorage.getItem('theme') ?? 'light';
    },
});
