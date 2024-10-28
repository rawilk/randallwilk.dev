const defaultOptions = {
    threshold: 30,
};

export default (options = {}) => ({
    scrolled: false,
    ...defaultOptions,
    ...options,

    init() {
        this.scrolled = this.isScrolled();
    },

    handleScroll() {
        this.scrolled = this.isScrolled();
    },

    isScrolled() {
        if (this.threshold === 0) {
            return window.scrollY > 0;
        }

        return window.scrollY >= this.threshold || window.pageYOffset >= this.threshold;
    },
});
