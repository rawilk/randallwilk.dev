export default {
    props: {
        size: {
            type: String,
            default: null
        }
    },

    computed: {
        /**
         * Generate the form control size.
         *
         * @returns {array}
         */
        sizeFormClass () {
            return [
                this.size ? `form-control-${this.size}` : null
            ];
        },

        /**
         * Generate the bootstrap button size class.
         *
         * @returns {array}
         */
        sizeBtnClass () {
            return [
                this.size ? `btn-${this.size}` : null
            ];
        }
    }
};