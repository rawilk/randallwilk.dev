export default {
    props: {
        /**
         * Indicates the input is disabled.
         *
         * @type {boolean}
         */
        disabled: {
            type: Boolean,
            default: false
        },

        /**
         * The id of the input.
         *
         * @type {string}
         */
        id: String,

        /**
         * The name of the input.
         *
         * @type {string}
         */
        name: String,

        /**
         * Indicates the input is a required field.
         *
         * @type {boolean}
         */
        required: {
            type: Boolean,
            default: false
        }
    }
};