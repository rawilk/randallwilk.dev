import Popper from 'popper.js';
import PopOver from '../../utils/class/popover.class';
import { assign, keys } from '../../utils/object';
import { warn } from '../../utils/warn';

const inBrowser = typeof window !== 'undefined' && typeof document !== 'undefined';

// Key which we use to store tooltip object on element
const LARAPO = '__LARA_PopOver__';

// Valid event triggers
const validTriggers = {
    'focus': true,
    'hover': true,
    'click': true,
    'blur': true
};

// Build a PopOver config based on bindings (if any)
// Arguments and modifiers take precedence over passed value config object
function parseBindings (bindings) {
    // We start out with a blank config
    let config = {};

    // Process bindings.value
    if (typeof bindings.value === 'string') {
        // Value is popover content (html optionally supported)
        config.content = bindings.value;
    } else if (typeof bindings.value === 'function') {
        // Content generator function
        config.content = bindings.value;
    } else if (typeof bindings.value === 'object') {
        // Value is config object, so merge
        config = assign(bindings.value);
    }

    // If Argument, assume element ID of container element
    if (bindings.arg) {
        // Element ID specified as arg. We must prepend '#' to become a CSS selector
        config.container = `#${bindings.arg}`;
    }

    // Process modifiers
    keys(bindings.modifiers).forEach(mod => {
        if (/^html$/.test(mod)) {
            // Title allows HTML
            config.html = true;
        } else if (/^nofade$/.test(mod)) {
            // no animation
            config.animation = false;
        } else if (/^(auto|top(left|right)?|bottom(left|right)?|left(top|bottom)?|right(top|bottom)?)$/.test(mod)) {
            // placement of popover
            config.placement = mod;
        } else if (/^(window|viewport)$/.test(mod)) {
            // boundary of popover
            config.boundary = mod;
        } else if (/^d\d+$/.test(mod)) {
            // delay value
            const delay = parseInt(mod.slice(1), 10) || 0;

            if (delay) {
                config.delay = delay;
            }
        } else if (/^o-?\d+$/.test(mod)) {
            // offset value (negative allowed)
            const offset = parseInt(mod.slice(1), 10) || 0;
            if (offset) {
                config.offset = offset;
            }
        }
    });

    // Special handling of event trigger modifiers Trigger is a space separated list
    const selectedTriggers = {};

    // parse current config object trigger
    let triggers = typeof config.trigger === 'string' ? config.trigger.trim().split(/\s+/) : [];
    triggers.forEach(trigger => {
        if (validTriggers[trigger]) {
            selectedTriggers[trigger] = true;
        }
    });

    // Parse Modifiers for triggers
    keys(validTriggers).forEach(trigger => {
        if (bindings.modifiers[trigger]) {
            selectedTriggers[trigger] = true;
        }
    });

    // Sanitize triggers
    config.trigger = keys(selectedTriggers).join(' ');
    if (config.trigger === 'blur') {
        // Blur by itself is useless, so convert it to focus
        config.trigger = 'focus';
    }

    if (! config.trigger) {
        // remove trigger config
        delete config.trigger;
    }

    return config;
}

// Add or Update popover on our element
function applyLARAPO (el, bindings, vnode) {
    if (! inBrowser) {
        return;
    }

    if (! Popper) {
        // Popper is required for tooltips to work
        warn('v-b-popover: Popper.js is required for popovers to work');

        return;
    }

    if (el[LARAPO]) {
        el[LARAPO].updateConfig(parseBindings(bindings));
    } else {
        el[LARAPO] = new PopOver(el, parseBindings(bindings), vnode.context.$root);
    }
}

// Remove popover on our element
function removeLARAPO (el) {
    if (! inBrowser) {
        return;
    }

    if (el[LARAPO]) {
        el[LARAPO].destroy();
        el[LARAPO] = null;
        delete el[LARAPO];
    }
}

export default {
    bind (el, bindings, vnode) {
        applyLARAPO(el, bindings, vnode);
    },

    inserted (el, bindings, vnode) {
        applyLARAPO(el, bindings, vnode);
    },

    update (el, bindings, vnode) {
        if (bindings.value !== bindings.oldValue) {
            applyLARAPO(el, bindings, vnode);
        }
    },

    componentUpdated (el, bindings, vnode) {
        if (bindings.value !== bindings.oldValue) {
            applyLARAPO(el, bindings, vnode);
        }
    },

    unbind (el) {
        removeLARAPO(el);
    }
};