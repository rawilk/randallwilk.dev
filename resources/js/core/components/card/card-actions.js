import { mergeData } from 'vue-functional-data-merge';
import { closest, hasClass, hide, select, show, toggleClass } from '../../utils/dom';

let collapseIcons = { 'collapse-closed': 'mdi mdi-plus', 'collapse-open': 'mdi mdi-minus' };
let expandIcons = { 'expand-closed': 'mdi mdi-arrow-expand', 'expand-open': 'mdi mdi-arrow-collapse' };

/**
 * Default click handler to use when one is not provided.
 *
 * @param {MouseEvent} event
 */
export const defaultCollapseClick = event => {
    const target = event.target;
    const card = closest('.card', target);

    if (! card) {
        return;
    }

    const elements = ['.card-body', '.card-footer'];

    if (hasClass(target, collapseIcons['collapse-open'])) {
        elements.forEach(el => hide(select(el, card)));
    } else {
        elements.forEach(el => show(select(el, card)));
    }

    toggleClass(target, `${collapseIcons['collapse-open']} ${collapseIcons['collapse-closed']}`);
};

/**
 * Default event handler for closing.
 *
 * @param {MouseEvent} event
 */
export const defaultCloseClick = event => {
    const target = event.target;
    const card = closest('.card', target);

    if (! card) {
        return;
    }

    hide(card);
};

export const props = {
    closeable: {
        type: Boolean,
        default: false
    },
    collapse: {
        type: Boolean,
        default: false
    },
    collapsed: {
        type: Boolean,
        default: null
    },
    icons: {
        type: Object,
        default: () => ({})
    },
    expandable: {
        type: Boolean,
        default: false
    }
};

export default {
    functional: true,

    props,

    render (h, { data, props, listeners }) {
        let actions = [];

        if (props.collapse) {
            collapseIcons = Object.assign(collapseIcons, props.icons);

            const collapseClick = listeners['collapse-click'] || defaultCollapseClick;
            actions.push(h(
                'a',
                {
                    staticClass: 'card-collapse',
                    class: [
                        {
                            [collapseIcons['collapse-open']]: props.collapsed === null || props.collapsed === false,
                            [collapseIcons['collapse-closed']]: !! props.collapsed
                        }
                    ],
                    on: {
                        click: event => {
                            event.preventDefault();
                            collapseClick(event);
                        }
                    }
                }
            ));
        }

        if (props.expandable) {
            expandIcons = Object.assign(expandIcons, props.icons);

            actions.push(h(
                'a',
                {
                    staticClass: 'card-expand',
                    class: [
                        expandIcons['expand-closed']
                    ],
                    on: {
                        click: e => {
                            e.preventDefault();
                            const target = e.target;
                            const card = closest('.card', target);

                            if (! card) {
                                return;
                            }

                            toggleClass(target, `${expandIcons['expand-closed']} ${expandIcons['expand-open']}`);
                            toggleClass(card, 'card-fullscreen');
                        }
                    }
                }
            ));
        }

        if (props.closeable) {
            const closeIcons = Object.assign({ close: 'mdi mdi-close' }, props.icons);

            const closeClick = listeners['close-click'] || defaultCloseClick;
            actions.push(h(
                'a',
                {
                    staticClass: 'card-close',
                    class: [
                        closeIcons.close
                    ],
                    on: {
                        click: event => {
                            event.preventDefault();
                            closeClick(event);
                        }
                    }
                }
            ));
        }

        return h(
            'div',
            mergeData(data, {
                staticClass: 'card-actions'
            }),
            actions
        );
    }
};