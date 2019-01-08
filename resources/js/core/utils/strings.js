import { isString } from './typeChecks';

/**
 * Lower case the first letter of the given string.
 *
 * @param {string} str
 * @returns {string}
 */
export const lowerFirst = str => {
    if (! isString(str)) {
        str = String(str);
    }

    return str.charAt(0).toLowerCase() + str.slice(1);
};

/**
 * Add prefix to the given prop name.
 *
 * @param {string} prefix
 * @param {string} value
 * @returns {string}
 */
export const prefixPropName = (prefix, value) => {
    return prefix + upperFirst(value);
};

/**
 *  Suffix can be a falsey value so nothing is appended to string.
 *  -- Helps when looping over props & some shouldn't change.
 *  -- Use data last parameters to allow for currying.
 *
 * @param {string} suffix
 * @param {string} value
 * @returns {string}
 */
export const suffixPropName = (suffix, value) => {
    return value + (suffix ? upperFirst(suffix) : '');
};

/**
 * Capitalize the first letter of the given string.
 *
 * @param {string} str
 * @returns {string}
 */
export const upperFirst = str => {
    if (! isString(str)) {
        str = String(str);
    }

    return str.charAt(0).toUpperCase() + str.slice(1);
};

/**
 * Remove prefix from the given prop name.
 *
 * @param {string} prefix
 * @param {string} value
 * @returns {string}
 */
export const unPrefixPropName = (prefix, value) => {
    return lowerFirst(value.replace(prefix, ''));
};