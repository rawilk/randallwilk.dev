const mediaQuery = window.matchMedia('(prefers-color-scheme: dark)');

const updateTheme = newTheme => {
    let theme = 'system';
    try {
        if (! newTheme) {
            newTheme = window.localStorage.theme;
        }

        if (newTheme === 'dark') {
            theme = 'dark';
            document.documentElement.classList.add('dark');
        } else if (newTheme === 'light') {
            theme = 'light';
            document.documentElement.classList.remove('dark');
        } else if (mediaQuery.matches) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    } catch {
        theme = 'light';
        document.documentElement.classList.remove('dark');
    }

    if (! document.documentElement.hasAttribute('data-theme')) {
        document.documentElement.setAttribute('data-theme', theme);
    }

    return theme;
};

const updateThemeWithoutTransitions = newTheme => {
    updateTheme(newTheme);

    const transitionClass = '[&_*]:!transition-none';
    document.documentElement.classList.add(transitionClass);

    window.setTimeout(() => {
        document.documentElement.classList.remove(transitionClass);
    }, 0);
};

export default (options = {}) => ({
    selectedTheme: 'light',

    init() {
        this.selectedTheme = updateTheme();
    },

    isSelected(theme) {
        return theme === this.selectedTheme;
    },

    selectTheme(theme) {
        const oldTheme = this.selectedTheme;

        document.documentElement.setAttribute('data-theme', theme);
        this.selectedTheme = theme;

        if (theme !== oldTheme) {
            try {
                window.localStorage.setItem('theme', theme);
            } catch {
            }

            updateThemeWithoutTransitions(theme);
        }

        window.dispatchEvent(
            new CustomEvent('modal-shown', {
                detail: {},
                bubbles: true,
                composed: true,
                cancelable: true,
            })
        );
    },
});

mediaQuery.addEventListener('change', () => updateThemeWithoutTransitions(null));
window.addEventListener('storage', () => updateThemeWithoutTransitions(null));
