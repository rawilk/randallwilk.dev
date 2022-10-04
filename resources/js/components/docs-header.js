export default (options = {}) => ({
    isScrolled: false,

    onScroll() {
        this.isScrolled = window.scrollY > 0;
    },
});
