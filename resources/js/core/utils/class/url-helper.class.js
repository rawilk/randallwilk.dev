export default class UrlHelper {
    /**
     * Initialize the class.
     *
     * @param {null|string} url
     */
    constructor (url = null) {
        this.search = url ? url : window.location.search;
        this.urlParams = new URLSearchParams(this.search);
    }

    /**
     * Get all key/value pairs in the query string except for keys
     * in the given blacklist.
     *
     * @param {array} blacklist
     * @returns {object}
     */
    getAll (blacklist = []) {
        let query = {};

        for (let key of this.urlParams.keys()) {
            if (! blacklist.includes(key)) {
                if (key.endsWith('[]')) {
                    query[key.substring(0, key.length - 2)] = this.urlParams.getAll(key);
                } else {
                    query[key] = this.urlParams.get(key);
                }
            }
        }

        return query;
    }

    /**
     * Attempt to get the given key from the url query string.
     *
     * @param {string} param
     * @returns {any}
     */
    getParam (param) {
        if (param.endsWith('[]')) {
            return this.urlParams.has(param) ? this.urlParams.getAll(param) : null;
        }

        return this.urlParams.has(param) ? this.urlParams.get(param) : null;
    }

    /**
     * Update the query string without reloading the page.
     *
     * @param {string} newQueryString
     */
    static replaceQueryString (newQueryString) {
        let newUrl = window.location.origin + window.location.pathname + `?${newQueryString}`;

        window.history.pushState(null, '', `?${newQueryString}`);
    }

    /**
     * Set the given query parameter.
     *
     * @param {string} param
     * @param {*} value
     */
    set (param, value) {
        this.urlParams.set(param, value);
    }

    /**
     * Get the url query params as a string.
     *
     * @returns {string}
     */
    toString () {
        return this.urlParams.toString();
    }
}