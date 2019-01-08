export default Vue => {
    Vue.filter('prettyBytes', (value, showRaw = true) => prettyBytes(value, showRaw));

    Vue.prototype.$prettyBytes = (value, showRaw = true) => prettyBytes(value, showRaw);
};

/**
 * Convert the given file size to a human readable format.
 *
 * @param {string|number} value
 * @param {boolean} showRaw
 * @returns {string}
 */
const prettyBytes = (value, showRaw = true) => {
    if (! value) {
        return '';
    }

    let rawNum = value;
    value = parseFloat(value);

    let exponent,
        unit,
        neg = value < 0;

    const units = ['B', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];

    if (neg) {
        value = - value;
    }

    if (value < 1) {
        return (neg ? '-' : '') + `${value} B`;
    }

    exponent = Math.min(Math.floor(Math.log(value) / Math.log(1000)), units.length - 1);
    value = (value / Math.pow(1000, exponent)).toFixed(2) * 1;
    unit = units[exponent];

    rawNum = parseFloat(rawNum).toLocaleString('en');

    let str = (neg ? '-' : '') + `${value} ${unit}`;

    if (showRaw) {
        str += ` (${rawNum} bytes)`;
    }

    return str;
};