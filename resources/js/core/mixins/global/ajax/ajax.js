import Ajax from '../../../utils/class/ajax.class';

/**
 * Global ajax mixin available to every component in the Vue instance.
 */

export default {
    created () {
        this.ajax = new Ajax();
    },

    methods: {
        /**
         * Submit a `DELETE` request to the given endpoint.
         *
         * @param {string} endpoint
         * @param {object} payload
         * @param {object} config
         * @returns {Promise}
         */
        deleteRequest (endpoint, payload = {}, config = {}) {
            return this.ajax.deleteRequest(endpoint, payload, config);
        },

        /**
         * Submit a `GET` request to the given endpoint.
         *
         * @param {string} endpoint
         * @param {object} payload
         * @param {object} config
         * @returns {Promise}
         */
        getRequest (endpoint, payload = {}, config = {}) {
            return this.ajax.get(endpoint, payload, config);
        },

        /**
         * Get an appropriate error message from the ajax response.
         *
         * @param {object} error
         * @param {string} messageKey
         * @returns {string}
         */
        getAjaxErrorMessage (error, messageKey = 'reason') {
            return Ajax.getErrorMessage(error, messageKey);
        },

        /**
         * Submit a `PATCH` request to the given endpoint.
         *
         * @param {string} endpoint
         * @param {object} payload
         * @param {object} config
         * @returns {Promise}
         */
        patchRequest (endpoint, payload = {}, config = {}) {
            return this.ajax.patch(endpoint, payload, config);
        },

        /**
         * Submit a `POST` request to the given endpoint.
         *
         * @param {string} endpoint
         * @param {object} payload
         * @param {object} config
         * @returns {Promise}
         */
        postRequest (endpoint, payload = {}, config = {}) {
            return this.ajax.post(endpoint, payload, config);
        },

        /**
         * Submit a `PUT` request to the given endpoint.
         *
         * @param {string} endpoint
         * @param {object} payload
         * @param {object} config
         * @returns {Promise}
         */
        putRequest (endpoint, payload = {}, config = {}) {
            return this.ajax.put(endpoint, payload, config);
        }
    }
};