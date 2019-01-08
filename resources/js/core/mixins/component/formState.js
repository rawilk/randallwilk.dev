/**
 * Form control contextual state class computation
 *
 * Returned class is either 'is-valid' or 'is-invalid' based on the 'state' prop
 * state can be one of five values:
 *  - true or 'valid' for is-valid
 *  - false or 'invalid' for is-invalid
 *  - null (or empty string) for no contextual state
 */

export default {
    props: {
        state: {
            // true/'valid', false/'invalid', '', null
            type: [Boolean, String],
            default: null
        }
    },

    computed: {
        /**
         * Determine if input control is valid.
         *
         * @returns {boolean|null}
         */
        computedState () {
            const state = this.state;

            if (state === true || state === 'valid') {
                return true;
            }

            if (state === false || state === 'invalid') {
                return false;
            }

            return null;
        },

        /**
         * Generate the css state class for the input.
         *
         * @returns {null|string}
         */
        stateClass () {
            const state = this.computedState;

            if (state === true) {
                return 'is-valid';
            }

            if (state === false) {
                return 'is-invalid';
            }

            return null;
        }
    }
};