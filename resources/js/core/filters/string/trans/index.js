import { warn } from '../../../utils/warn';

export default Vue => {
    Vue.filter('trans', (key, replacements = {}) => trans(key, replacements));
    Vue.filter('transChoice', (key, count = 1, replacements = {}) => transChoice(key, count, replacements));

    Vue.prototype.$trans = (key, replacements = {}) => trans(key, replacements);
    Vue.prototype.$transChoice = (key, count = 1, replacements = {}) => transChoice(key, count, replacements);
};

/**
 * Translate the given key.
 *
 * @param {string} key
 * @param {object} replacements
 * @returns {string}
 */
export const trans = (key, replacements = {}) => {
    if (! key) {
        return '';
    }

    if (! Lang.has(key)) {
        warn(`No translation available for key: "${key}"`);
    }

    return Lang.get(key, replacements);
};

/**
 * Get the singular or plural form for the given translation key.
 *
 * @param {string} key
 * @param {number|object} count
 * @param {object} replacements
 * @returns {string}
 */
export const transChoice = (key, count = 1, replacements = {}) => {
    if (! key) {
        return '';
    }

    if (! Lang.has(key)) {
        warn(`No translation available for key: "${key}"`);
    }

    return Lang.choice(key, count, replacements);
};