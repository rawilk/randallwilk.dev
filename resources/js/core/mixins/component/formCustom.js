export default {
    props: {
        plain: {
            type: Boolean,
            default: false
        }
    },

    computed: {
        /**
         * Determine if custom styling should be applied.
         *
         * @returns {boolean}
         */
        custom () {
            return ! this.plain;
        }
    }
};