// SSR Safe Client Side ID attribute generation

export default {
    props: {
        id: {
            type: String,
            default: null
        }
    },

    computed: {
        /**
         * Generate a local id.
         *
         * @returns {string}
         * @private
         */
        localId_ () {
            if (! this.$isServer && ! this.id && typeof this._uid !== 'undefined') {
                return '__LARA__' + this._uid;
            }
        }
    },

    methods: {
        /**
         * Generate a safe id.
         *
         * @param {string} suffix
         * @returns {string|null}
         */
        safeId (suffix = '') {
            const id = this.id || this.localId_ || null;

            if (! id) {
                return null;
            }

            suffix = String(suffix).replace(/\s+/g, '_');

            return suffix ? id + '_' + suffix : id;
        }
    }
};