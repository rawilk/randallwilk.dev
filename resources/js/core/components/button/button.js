import { mergeData } from 'vue-functional-data-merge';
import pluckProps from '../../utils/pluckProps';
import { concat } from '../../utils/array';
import { assign, keys } from '../../utils/object';
import { addClass, removeClass } from '../../utils/dom';
import Link, { propsFactory as linkPropsFactory } from '../link/link';

const btnProps = {
    block: {
        type: Boolean,
        default: false
    },
    busy: {
        type: Boolean,
        default: false
    },
    disabled: {
        type: Boolean,
        default: false
    },
    loaderAnimationDuration: {
        type: Number,
        default: 3500
    },
    loaderColor: {
        type: String,
        default: '#fff'
    },
    loaderSize: {
        type: Number,
        default: 20
    },
    size: {
        type: String,
        default: null
    },
    variant: {
        type: String,
        default: null
    },
    rounded: {
        type: Boolean,
        default: false
    },
    circle: {
        type: Boolean,
        default: false
    },
    type: {
        type: String,
        default: 'button'
    },
    pressed: {
        // tri-state prop: true, false or null
        // => on, off, not a toggle
        type: Boolean,
        default: null
    }
};

let linkProps = linkPropsFactory();
delete linkProps.href.default;
delete linkProps.to.default;
const linkPropKeys = keys(linkProps);

export const props = assign(linkProps, btnProps);

/**
 * Handle button focus.
 *
 * @param {Event} event
 */
function handleFocus (event) {
    if (event.type === 'focusin') {
        addClass(event.target, 'focus');
    } else if (event.type === 'focusout') {
        removeClass(event.target, 'focus');
    }
}

export default {
    functional: true,

    props,

    render (h, { props, data, listeners, slots }) {
        const isLink = Boolean(props.href || props.to);
        const isToggle = typeof props.pressed === 'boolean';

        const on = {
            click (event) {
                if ((props.disabled || props.busy) && event instanceof Event) {
                    event.stopPropagation();
                    event.preventDefault();
                } else if (isToggle) {
                    // Concat will normalize the value to an array
                    // without double wrapping an array value in an array.
                    concat(listeners['update:pressed']).forEach(fn => {
                        if (typeof fn === 'function') {
                            fn(! props.pressed);
                        }
                    });
                }
            }
        };

        if (isToggle) {
            on.focusin = handleFocus;
            on.focusout = handleFocus;
        }

        const componentData = {
            staticClass: 'btn',
            class: [
                props.variant ? `btn-${props.variant}` : `btn-secondary`,
                {
                    [`btn-${props.size}`]: !! props.size,
                    'btn-block': props.block && ! props.circle,
                    'btn-rounded': props.rounded,
                    'btn-circle': props.circle && ! props.rounded,
                    disabled: props.disabled || props.busy,
                    active: props.pressed,
                    busy: props.busy,
                }
            ],
            props: isLink ? pluckProps(linkPropKeys, props) : null,
            attrs: {
                type: isLink ? null : props.type,
                disabled: isLink ? null : props.disabled || props.busy,
                // Data attribute not used for js logic,
                // but only for BS4 style selectors.
                'data-toggle': isToggle ? 'button' : null,
                'aria-pressed': isToggle ? String(props.pressed) : null,
                // Tab index is used when the component becomes a link.
                // Links are tabbable, but don't allow disabled,
                // so we mimic that functionality by disabling tabbing.
                tabindex:
                    props.disabled && isLink
                        ? '-1'
                        : data.attrs ? data.attrs['tabindex'] : null
            },
            on
        };

        let content = [slots().default];
        if (props.busy) {
            content = [h(
                'b-loader',
                {
                    props: {
                        animationDuration: props.loaderAnimationDuration,
                        color: props.loaderColor,
                        size: props.loaderSize
                    }
                }
            )];
        }

        return h(isLink ? Link : 'button', mergeData(data, componentData), content);
    }
};