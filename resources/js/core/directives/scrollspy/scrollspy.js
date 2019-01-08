/**
 * ScrollSpy directive v-b-scrollspy
 */

import ScrollSpy from './scrollspy.class';
import { keys } from '../../utils/object';

const inBrowser = typeof window !== 'undefined';
const isServer = ! inBrowser;

// Key we use to store our Instance
const LARASS = '__LARA_ScrollSpy__';

/**
 * Generate config from bindings.
 *
 * @param {object} binding
 */
function makeConfig (binding) {
    const config = {};

    // If Argument, assume element ID
    if (binding.arg) {
        // Element ID specified as arg. We must pre-pend #
        config.element = '#' + binding.arg;
    }

    // Process modifiers
    keys(binding.modifiers).forEach(mod => {
        if (/^\d+$/.test(mod)) {
            // Offset value
            config.offset = parseInt(mod, 10);
        } else if (/^(auto|position|offset)$/.test(mod)) {
            // Offset method
            config.method = mod;
        }
    });

    // Process value
    if (typeof binding.value === 'string') {
        // Value is a CSS ID or selector
        config.element = binding.value;
    } else if (typeof binding.value === 'number') {
        // Value is offset
        config.offset = Math.round(binding.value);
    } else if (typeof binding.value === 'object') {
        // Value is config object
        // Filter the object based on our supported config options
        keys(binding.value).filter(k => Boolean(ScrollSpy.DefaultType[k])).forEach(k => {
            config[k] = binding.value[k];
        });
    }

    return config;
}

function addLARASS (el, binding, vnode) {
    if (isServer) {
        return;
    }

    const config = makeConfig(binding);

    if (! el[LARASS]) {
        el[LARASS] = new ScrollSpy(el, config, vnode.context.$root);
    } else {
        el[LARASS].updateConfig(config, vnode.context.$root);
    }

    return el[LARASS];
}

function removeLARASS (el) {
    if (el[LARASS]) {
        el[LARASS].dispose();
        el[LARASS] = null;
    }
}

export default {
    bind (el, binding, vnode) {
        addLARASS(el, binding, vnode);
    },

    inserted (el, binding, vnode) {
        addLARASS(el, binding, vnode);
    },

    update (el, binding, vnode) {
        addLARASS(el, binding, vnode);
    },

    componentUpdated (el, binding, vnode) {
        addLARASS(el, binding, vnode);
    },

    unbind (el) {
        if (isServer) {
            return;
        }

        // Remove scroll event listener on scrollElId
        removeLARASS(el);
    }
};