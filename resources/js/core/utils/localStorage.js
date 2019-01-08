import { isObject } from './typeChecks';
import { isArray } from './array';

/**
 * Retrieve the value from local storage for the given key.
 *
 * @param {string} key
 * @param {*} defaultValue
 * @returns {*}
 */
export const getItem = (key, defaultValue = '') => {
    const item = localStorage.getItem(key);

    if (typeof item === 'undefined' || item === null) {
        return defaultValue;
    }

    try {
        return JSON.parse(item);
    } catch (e) {
        return item;
    }
};

/**
 * Remove the given key from local storage.
 *
 * @param {string} key
 */
export const removeItem = key => localStorage.removeItem(key);

/**
 * Set a value in local storage for the given key.
 *
 * @param {string} key
 * @param {*} value
 */
export const setItem = (key, value) => {
    if (isArray(value) || isObject(value)) {
        value = JSON.stringify(value);
    }

    localStorage.setItem(key, value);
};