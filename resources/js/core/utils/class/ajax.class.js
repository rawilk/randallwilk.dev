import axios from 'axios';
import { trans } from '../../filters/string/trans';

/**
 * Axios AJAX helpers. Goal of this class is to
 * make a unified api for making ajax requests
 * in the application.
 */

export default class Ajax {
    /**
     * Submit a `DELETE` request to the given endpoint.
     *
     * @param {string} endpoint
     * @param {object} payload
     * @param {object} config
     * @returns {Promise}
     */
    deleteRequest (endpoint, payload = {}, config = {}) {
        return this.submit('delete', endpoint, Ajax.normalizePayload(payload, config));
    }

    /**
     * Submit a `GET` request to the given endpoint.
     *
     * @param {string} endpoint
     * @param {object} payload
     * @param {object} config
     * @returns {Promise}
     */
    get (endpoint, payload = {}, config = {}) {
        return this.submit('get', endpoint, Ajax.normalizePayload(payload, config));
    }

    /**
     * Submit a `PATCH` request to the given endpoint.
     *
     * @param {string} endpoint
     * @param {object} payload
     * @param {object} config
     * @returns {Promise}
     */
    patch (endpoint, payload = {}, config = {}) {
        return this.submit('patch', endpoint, payload, config);
    }

    /**
     * Submit a `POST` request to the given endpoint.
     *
     * @param {string} endpoint
     * @param {object} payload
     * @param {object} config
     * @returns {Promise}
     */
    post (endpoint, payload = {}, config = {}) {
        return this.submit('post', endpoint, payload, config);
    }

    /**
     * Submit a `PUT` request to the given endpoint.
     *
     * @param {string} endpoint
     * @param {object} payload
     * @param {object} config
     * @returns {Promise}
     */
    put (endpoint, payload = {}, config = {}) {
        return this.submit('put', endpoint, payload, config);
    }

    /**
     * Merge the given payload and config together for
     * requests that only have one parameter, such as `GET` requests.
     *
     * @param {object} payload
     * @param {object} config
     * @returns {object}
     */
    static normalizePayload (payload, config) {
        return {
            params: payload,
            ...config
        };
    }

    /**
     * Normalize the given error response.
     *
     * @param {object} error
     * @returns {object}
     */
    static normalizeErrorResponse (error) {
        if (error.response) {
            // The request was made and the server responded with a status code
            // that falls out of the range of 2xx
            return {
                data: error.response.data,
                status: error.response.status,
                headers: error.response.headers
            };
        }

        if (error.request) {
            // The request was made but no response was received
            // `error.request` is an instance of XMLHttpRequest in the browser and an instance
            // of http.ClientRequest in node.js
            return { request: error.request };
        }

        // Something happened in setting up the request that triggered an error
        return { message: error.message };
    }

    /**
     * Set the CSRF token header in ajax requests.
     *
     * @param {string} token
     */
    static setCsrfToken (token) {
        axios.defaults.headers.common = {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': token
        };
    }

    /**
     * Submit an AJAX request to the given endpoint.
     *
     * @param {string} type
     * @param {string} endpoint
     * @param {object} payload
     * @param {object} config
     * @returns {Promise}
     */
    submit (type, endpoint, payload = {}, config = {}) {
        return new Promise((resolve, reject) => {
            axios[type](endpoint, payload, config)
                .then(({ data }) => resolve(data))
                .catch(error => reject(Ajax.normalizeErrorResponse(error)));
        });
    }

    /**
     * Get an appropriate error message.
     *
     * @param {object} error
     * @param {string} errorMessageKey
     * @returns {null|string}
     */
    static getErrorMessage (error, errorMessageKey = 'reason') {
        if (error.status >= 500) {
            return Ajax.getTranslatedValue(
                'requests.internal_server_error',
                'An unexpected error has occurred.'
            );
        }

        if (typeof error.data !== 'object') {
            return error.status === 404
                ? Ajax.getTranslatedValue(
                    'requests.resource_not_found',
                    'The resource you are looking for could not be found.'
                )
                : null;
        }

        if (errorMessageKey in error.data && error.data[errorMessageKey].toString().length) {
            return error.data[errorMessageKey];
        }

        return Ajax.getTranslatedValue(
            'requests.internal_server_error',
            'An unexpected error has occurred.'
        );
    }

    /**
     * Attempt to translate the given key.
     *
     * @param {string} key
     * @param {string} fallbackValue
     * @returns {string}
     */
    static getTranslatedValue (key, fallbackValue = '') {
        try {
            return trans(key);
        } catch (e) {
            return fallbackValue;
        }
    }
}