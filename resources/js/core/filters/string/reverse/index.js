export default Vue => {
    Vue.filter('reverse', string => reverse(string));

    Vue.prototype.$reverse = string => reverse(string);
};

/**
 * Reverse the contents of the given string.
 *
 * @param {string} string
 * @returns {string}
 */
const reverse = string => {
    if (! string) {
        return '';
    }

    return string.toString().split('').reverse().join('');
};