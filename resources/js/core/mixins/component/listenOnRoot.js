import { isArray } from '../../utils/array';

const LARARL = '__Lara_root_listeners__';

export default {
    beforeDestroy () {
        if (this[LARARL] && isArray(this[LARARL])) {
            while (this[LARARL].length > 0) {
                // shift to process in order
                const { event, callback } = this[LARARL].shift();

                this.$root.$off(event, callback);
            }
        }
    },

    methods: {
        /**
         * Safely register event listeners on the root Vue node.
         * While Vue automatically removes listeners for individual components,
         * when a component registers a listener on root and is destroyed,
         * this orphans a callback because the node is gone,
         * but the root does not clear the callback.
         *
         * This adds a non-reactive prop to a vm on the fly
         * in order to avoid object observation and its performance costs
         * to something that needs no reactivity.
         * It should be highly unlikely there are any naming collisions.
         *
         * @param {string} event
         * @param {function} callback
         * @chainable
         */
        listenOnRoot (event, callback) {
            if (! this[LARARL] || ! isArray(this[LARARL])) {
                this[LARARL] = [];
            }

            this[LARARL].push({ event, callback });
            this.$root.$on(event, callback);

            return this;
        },

        /**
         * Convenience method for calling vm.$emit on vm.$root.
         *
         * @param {string} event
         * @param {*} args
         * @chainable
         */
        emitOnRoot (event, ...args) {
            this.$root.$emit(event, ...args);

            return this;
        }
    }
};