import { isObject } from './typeChecks';
import RecursiveIterator from 'recursive-iterator';

/**
 * Determine if the given object has any files inside of it.
 *
 * @param {object} obj
 * @returns {boolean}
 */
export const hasFile = obj => {
    return Object.keys(obj).some(key => {
        if (isObject(obj[key])) {
            return hasFile(obj[key]);
        }

        return obj[key] instanceof Blob || obj[key] instanceof FileList || obj[key] instanceof File;
    });
};

/**
 * Determine if the given item is a file.
 *
 * @param {*} item
 * @returns {boolean}
 */
export const isFile = item => item instanceof File;

/**
 * Convert the given object to FormData.
 *
 * @param {object} obj
 * @returns {FormData}
 */
export const toFormData = obj => {
    if (! isObject(obj)) {
        throw new TypeError('Argument must be an object!');
    }

    let form = new FormData();
    let iterator = new RecursiveIterator(obj, 0, true);

    const appendToForm = (path, node, filename) => {
        const name = toName(path);

        if (typeof filename === 'undefined') {
            form.append(name, node);
        } else {
            form.append(name, node, filename);
        }
    };

    iterator.onStepInto = ({ parent, node }) => {
        const type = getType(node);

        switch (type) {
            case 'Array':
            case 'Object':
            case 'FileList':
                return true; // step into
            default:
                return false; // prevent step into
        }
    };

    for (let { node, path } of iterator) {
        const type = getType(node);

        switch (type) {
            case 'Array':
            case 'Object':
                break;
            case 'File':
                appendToForm(path, node);
                break;
            case 'Blob':
                appendToForm(path, node, node.name);
                break;
            default:
                appendToForm(path, node);
                break;
        }
    }

    return form;
};

/**
 * Returns the type of anything.
 *
 * @param {*} item
 * @returns {string}
 */
const getType = item => Object.prototype.toString.call(item).slice(8, -1);

/**
 * Convert the given path to a FormData name.
 *
 * @param {array} path
 * @returns {string}
 */
const toName = path => {
    let array = path.map(part => `[${part}]`);

    array[0] = path[0];

    return array.join('');
};