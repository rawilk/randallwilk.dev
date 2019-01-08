export default Vue => {
    Vue.filter('pluralize', (value, ...args) => pluralize(value, args));

    Vue.prototype.$pluralize = (value, ...args) => pluralize(value, args);
};

/**
 * Generate a pluralized string for the given amount.
 *
 * @param {number} amount
 * @param {*} args
 * @returns {string}
 *
 * @usage {{ count }} {{ count | pluralize('item') }}
 *      1 => '1 item'
 *      2 => '2 items'
 *
 * @usage {{ date }}{{ date | pluralize('st', 'nd', 'rd', 'th' }}
 *      1 => '1st'
 *      2 => '2nd'
 *      3 => '3rd'
 *      4 => '4th'
 *      5 => '5th'
 */
const pluralize = (amount, args) => {
    return args.length > 1
        ? (args[amount % 10 - 1] || args[args.length - 1])
        : (args[0] + (amount === 1 ? '' : 's'));
};