import moment from 'moment';
import { warn } from '../../../utils/warn';

export default Vue => {
    Vue.filter('calendar', (value, referenceTime = null, formats = {}) => calendar(value, referenceTime, formats));

    Vue.prototype.$calendar = (value, referenceTime = null, formats = {}) => calendar(value, referenceTime, formats);
};

/**
 * Format the given date depending on how close to a certain date (today by default) the date is.
 *
 * @param {string} value
 * @param {*} referenceTime
 * @param {object} formats
 * @returns {string}
 */
const calendar = (value, referenceTime = null, formats = {}) => {
    if (! value) {
        return '';
    }

    let date = moment(value);

    if (! date.isValid()) {
        warn(`Invalid date: '${value}'`);

        return value;
    }

    let reference = moment();

    if (moment(referenceTime).isValid()) {
        reference = moment(referenceTime);
    }

    try {
        return date.calendar(reference, formats);
    } catch (e) {
        warn('Calendar filter unable to parse date');

        return value;
    }
};