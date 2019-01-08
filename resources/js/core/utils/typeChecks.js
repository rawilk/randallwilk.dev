/**
 * Determine if the given item is a function.
 *
 * @param {function|*} obj
 * @returns {boolean}
 */
export const isFunction = obj => typeof obj === 'function';

/**
 * Determine if the given item is an object.
 *
 * @param {object|*} obj
 * @returns {boolean}
 */
export const isObject = obj => obj && ({}).toString.call(obj) === '[object Object]';

/**
 * Determine if the given item is a string.
 *
 * @param {string|*} str
 * @returns {boolean}
 */
export const isString = str => typeof str === 'string';