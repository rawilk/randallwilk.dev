const bulkSelectClass = 'selected';

export default {
    computed: {
        /**
         * Determine if main bulk select checkbox is checked.
         *
         * @returns {boolean}
         */
        bulkChecked () {
            if (! this.bulkSelectProperty) {
                return false;
            }

            return ! this.bulkIndeterminate
                && this.bulkSelect.length === this[this.bulkSelectProperty].length;
        },

        /**
         * Determine if bulk select main checkbox is indeterminate.
         *
         * @returns {boolean}
         */
        bulkIndeterminate () {
            if (! this.bulkSelectProperty) {
                return false;
            }

            return this.bulkSelect.length >= 1 && this.bulkSelect.length < this[this.bulkSelectProperty].length;
        },
    },

    data () {
        return {
            bulkSelectProperty: '',
            bulkSelect: []
        };
    },

    methods: {
        /**
         * Add or remove all items from bulk select.
         */
        bulkToggleAll () {
            if (! this.bulkSelectProperty) {
                return;
            }

            const length = this[this.bulkSelectProperty].length;

            if (this.bulkSelect.length < length) {
                this.bulkSelect = this[this.bulkSelectProperty].map(item => {
                    this.setBulkItemRowVariant(item);

                    return item.id
                });
            } else {
                this.clearBulkSelection();
                this[this.bulkSelectProperty].forEach(item => this.removeBulkItemRowVariant(item));
            }
        },

        /**
         * Clear all items out of bulk selection.
         */
        clearBulkSelection () {
            this.bulkSelect.splice(0, this.bulkSelect.length);
        },

        /**
         * Determine if the given item is bulk selected.
         *
         * @param {number} id
         * @returns {boolean}
         */
        itemIsBulkSelected (id) {
            return this.getBulkItemIndex(id) > -1;
        },

        /**
         * Get the index of the given bulk selected item.
         *
         * @param {number} id
         * @returns {number}
         */
        getBulkItemIndex (id) {
            return this.bulkSelect.findIndex(i => i === id);
        },

        /**
         * Add or remove the given item to the bulk selected items.
         *
         * @param {number} id
         * @param {object|null} item
         */
        toggleBulkSelectItem (id, item = null) {
            const index = this.getBulkItemIndex(id);

            if (index > -1) {
                this.$delete(this.bulkSelect, index);

                if (item !== null) {
                    this.removeBulkItemRowVariant(item);
                }
            } else {
                this.bulkSelect.push(id);

                if (item !== null) {
                    this.setBulkItemRowVariant(item);
                }
            }
        },

        /**
         * Remove the bulk selected background color variant from the given item.
         *
         * @param {object} item
         */
        removeBulkItemRowVariant (item) {
            if (typeof item._rowVariant_orig !== 'undefined') {
                item._rowVariant = item._rowVariant_orig;
                this.$delete(item, '_rowVariant_orig');
            } else {
                this.$delete(item, '_rowVariant');
            }
        },

        /**
         * Set a background color to help user visually see row is selected.
         *
         * @param {object} item
         */
        setBulkItemRowVariant (item) {
            if (typeof item._rowVariant !== 'undefined' && item._rowVariant !== bulkSelectClass) {
                this.$set(item, '_rowVariant_orig', item._rowVariant);
            }

            this.$set(item, '_rowVariant', bulkSelectClass);
        }
    }
};