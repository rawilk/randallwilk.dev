export default Vue => {
    Vue.filter('camelcase', string => camelcase(string));

    Vue.prototype.$camelcase = string => camelcase(string);
};

/**
 * Convert the given string to camel case.
 *
 * @param {string} string
 * @returns {string}
 * @example foo_bar => fooBar
 */
const camelcase = string => {
    if (! string) {
        return '';
    }

    return string.toString().toLowerCase()
        .replace(/[-_]+/g, ' ')
        .replace(/[^\w\s]/g, '')
        .replace(/ (.)/g, function($1) { return $1.toUpperCase(); })
        .replace(/ /g, '' );
};