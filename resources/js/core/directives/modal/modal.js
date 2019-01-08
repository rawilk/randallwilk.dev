import { bindTargets, unbindTargets } from '../../utils/target';
import { setAttr, removeAttr } from '../../utils/dom';

const listenTypes = { click: true };

export default {
    bind (el, binding, vnode) {
        bindTargets(vnode, binding, listenTypes, ({ targets, vnode }) => {
            targets.forEach(target => {
                vnode.context.$root.$emit('lara::show::modal', target, vnode.elm);
            });
        });

        if (el.tagName !== 'BUTTON') {
            // If element is not a button, we add `role="button"` for accessibility
            setAttr(el, 'role', 'button');
        }
    },

    unbind (el, binding, vnode) {
        unbindTargets(vnode, binding, listenTypes);

        if (el.tagName !== 'BUTTON') {
            // If element is not a button, we add `role="button"` for accessibility
            removeAttr(el, 'role', 'button');
        }
    }
};