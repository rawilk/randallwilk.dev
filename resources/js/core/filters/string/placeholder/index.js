export default Vue => {
    Vue.filter('placeholder', (value, placeholder) => replace(value, placeholder));

    Vue.prototype.$placeholder = (value, placeholder) => replace(value, placeholder);
};

/**
 * Output a default value if the given value is empty.
 *
 * @param {*} value
 * @param {*} placeholder
 * @returns {*}
 */
const replace = (value, placeholder) => (value === undefined || value === '' || value === null) ? placeholder : value;