import { Ziggy } from '../../../../ziggy';
import route from '../../../../../../vendor/tightenco/ziggy/src/js/route';

export default {
    methods: {
        /**
         * Get the endpoint for the given route name.
         *
         * @param {string} name
         * @param {object|null} params
         * @param {boolean} absolute
         * @returns {Router}
         */
        route (name, params = {}, absolute = false) {
            if (params === null) {
                params = {};
            }

            return route(name, params, absolute, Ziggy);
        }
    }
};