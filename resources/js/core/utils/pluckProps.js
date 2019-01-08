import { keys } from './object';
import { isArray } from './array';
import identity from './identity';

/**
 * Given an array of properties or an object of property keys,
 * plucks all the values off the target object.
 *
 * @param {object|array} keysToPluck
 * @param {object} objToPluck
 * @param {function} transformFn
 * @return {object}
 */
export default function pluckProps (keysToPluck, objToPluck, transformFn = identity) {
    return (isArray(keysToPluck) ? keysToPluck.slice() : keys(keysToPluck)).reduce((memo, prop) => {
        return (memo[transformFn(prop)] = objToPluck[prop]), memo;
    }, {});
}