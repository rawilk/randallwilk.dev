export default Vue => {
    Vue.filter('uppercase', string => uppercase(string));

    Vue.prototype.$uppercase = string => uppercase(string);
};

/**
 * Transform the given string to all caps.
 *
 * @param {string} string
 * @returns {string}
 * @usage {{ str | uppercase }}
 *      'foo' => 'FOO'
 */
const uppercase = string => {
    if (! string) {
        return '';
    }

    return string.toString().toUpperCase();
};