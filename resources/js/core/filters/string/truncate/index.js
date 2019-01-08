export default Vue => {
    Vue.filter('truncate', (string, maxLength, ellipsis) => truncate(string, maxLength, ellipsis));

    Vue.prototype.$truncate = (string, maxLength, ellipsis) => truncate(string, maxLength, ellipsis);
};

/**
 * Truncate the given string if its length is longer
 * than the provided max length.
 *
 * @param {string} string
 * @param {number} maxLength
 * @param {string} ellipsis
 * @returns {string}
 */
const truncate = (string, maxLength = 15, ellipsis = '...') => {
    if (! string) {
        return '';
    }

    string = string.toString();

    return string.length <= maxLength
        ? string
        : string.substring(0, maxLength) + ellipsis;
};