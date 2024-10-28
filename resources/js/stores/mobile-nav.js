export default (preventBodyOverflow = true) => ({
    isOpen: false,
    preventBodyOverflow,

    init() {
        if (this.isOpen && this.preventBodyOverflow) {
            document.body.classList.add('overflow-hidden');
        }
    },

    close: function () {
        this.isOpen = false;

        if (this.preventBodyOverflow) {
            document.body.classList.remove('overflow-hidden');
        }
    },

    open: function () {
        this.isOpen = true;

        if (this.preventBodyOverflow) {
            document.body.classList.add('overflow-hidden');
        }
    },
});
