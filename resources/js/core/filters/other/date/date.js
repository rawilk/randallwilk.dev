import moment from 'moment';
import { warn } from '../../../utils/warn';

export default Vue => {
    Vue.filter('date', (value, format) => date(value, format));

    Vue.prototype.$date = (value, format) => date(value, format);
};

/**
 * Format the given date.
 *
 * @param {string} value
 * @param {string} format
 * @returns {*}
 */
const date = (value, format = 'MM/DD/YYYY') => {
    if (! value) {
        return '';
    }

    let date = moment(value);

    if (! date.isValid()) {
        warn(`Invalid date: '${value}'`);

        return value;
    }

    try {
        return date.format(format);
    } catch (e) {
        warn(`Failed to format date with format: '${format}'`);

        return value;
    }
};