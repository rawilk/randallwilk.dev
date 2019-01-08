import Form from '../../utils/class/form.class';

export default {
    props: {
        field: String,
        serverField: String,
        serverErrors: {
            type: Object,
            default: null
        }
    },

    data () {
        return {
            localField: this.field,
            localServerField: this.serverField
        };
    },

    computed: {
        /**
         * Determine the state of the form field.
         *
         * @returns {boolean}
         */
        computedState () {
            return ! Boolean(this.hasClientError || this.hasServerError);
        },

        /**
         * Determine if there is a client side validation error.
         *
         * @returns {boolean}
         */
        hasClientError () {
            return this.hasValidation
                ? this.$validator.errors.has(this.localField)
                : false;
        },

        /**
         * Determine if there is a server side validation error.
         *
         * @returns {boolean}
         */
        hasServerError () {
            if (this.hasServerValidation) {
                // Server errors passed in have higher precedence over injected form
                if (!! this.serverErrors && Object.keys(this.serverErrors).length) {
                    return this.serverErrors.hasOwnProperty(this.localServerField);
                }

                return this.form.errors.has(this.localServerField);
            }

            return false;
        },

        /**
         * Determine if there is server side validation.
         *
         * @returns {boolean}
         */
        hasServerValidation () {
            return this.form instanceof Form || !! this.serverErrors;
        },

        /**
         * Determine if the client side validation exists (using Vee-Validate)
         *
         * @returns {boolean}
         */
        hasValidation () {
            return !! this.$validator;
        },

        /**
         * Generate the validation CSS class.
         *
         * @returns {string|null}
         */
        stateClass () {
            const state = this.computedState;

            if (state === true) {
                return 'is-valid';
            }

            if (state === false) {
                return 'has-error';
            }

            return null;
        },

        /**
         * Attempt to retrieve the first validation error.
         *
         * @returns {string|null}
         */
        validationError () {
            if (this.hasClientError) {
                return this.$validator.errors.first(this.localField);
            }

            if (this.hasServerError) {
                if (!! this.serverErrors && Object.keys(this.serverErrors).length) {
                    if (this.serverErrors.errors.hasOwnProperty(this.localServerField)) {
                        return this.serverErrors.errors[this.localServerField][0];
                    }
                }

                return this.form.errors.get(this.localServerField);
            }

            return null;
        }
    },

    created () {
        if (! this.localServerField || String(this.localServerField).trim() === '') {
            try {
                let arr = this.localField.split('.');

                if (arr.length > 1) {
                    arr.shift();
                }

                this.localServerField = arr.join('.');
            } catch (e) {}
        }
    }
};