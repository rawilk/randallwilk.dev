import { keys } from './object';

const allListenTypes = { hover: true, click: true, focus: true };

const laraBoundEventListeners = '__Lara_boundEventListeners__';

const bindTargets = (vnode, binding, listenTypes, fn) => {
    const targets = keys(binding.modifiers || {})
        .filter(t => ! allListenTypes[t]);

    if (binding.value) {
        targets.push(binding.value);
    }

    const listener = () => {
        fn({ targets, vnode });
    };

    keys(allListenTypes).forEach(type => {
        if (listenTypes[type] || binding.modifiers[type]) {
            vnode.elm.addEventListener(type, listener);
            const boundListeners = vnode.elm[laraBoundEventListeners] || {};

            boundListeners[type] = boundListeners[type] || [];
            boundListeners[type].push(listener);

            vnode.elm[laraBoundEventListeners] = boundListeners;
        }
    });

    // Return the list of targets
    return targets;
};

const unbindTargets = (vnode, binding, listenTypes) => {
    keys(allListenTypes).forEach(type => {
        if (listenTypes[type] || binding.modifiers[type]) {
            const boundListeners = vnode.elm[laraBoundEventListeners] && vnode.elm[laraBoundEventListeners][type];

            if (boundListeners) {
                boundListeners.forEach(listener => vnode.elm.removeEventListener(type, listener));

                delete vnode.elm[laraBoundEventListeners][type];
            }
        }
    });
};

export {
    bindTargets,
    unbindTargets
};

export default bindTargets;