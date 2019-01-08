export default Vue => {
    Vue.filter('lowercase', string => lowercase(string));

    Vue.prototype.$lowercase = string => lowercase(string);
};

/**
 * Convert the given string to lowercase.
 *
 * @param {string} string
 * @returns {string}
 * @example FOOBAR => foobar
 */
const lowercase = string => {
    if (! string) {
        return '';
    }

    return string.toString().toLowerCase();
};