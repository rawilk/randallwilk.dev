export default (options = {}) => ({
    scrolled: false,
    threshold: options.threshold || 30,

    init() {
        this.scrolled = this.isScrolled();
    },

    handleScroll() {
        this.scrolled = this.isScrolled();
    },

    isScrolled() {
        return window.scrollY >= this.threshold || window.pageYOffset >= this.threshold;
    },
});
