export default {
    data () {
        return {
            filter: null
        };
    },

    methods: {
        /**
         * Handle table filtering.
         *
         * @param {array} filteredItems
         */
        onFilter (filteredItems) {
            if (typeof this.currentPage !== 'undefined') {
                this.currentPage = 1;
            }

            if (typeof this.totalRows !== 'undefined') {
                this.totalRows = filteredItems.length;
            }
        }
    }
};