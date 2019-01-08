import { mergeData } from 'vue-functional-data-merge';
import { prefixPropName } from '../../utils/strings';
import copyProps from '../../utils/copyProps';
import { assign } from '../../utils/object';
import cardMixin from '../../mixins/component/card';
import { props as actionProps } from './card-actions';

export const props = assign(
    {},
    copyProps(cardMixin.props, prefixPropName.bind(null, 'header')),
    actionProps,
    {
        header: {
            type: String,
            default: null
        },
        headerClass: {
            type: [String, Object, Array],
            default: null
        }
    }
);

export default {
    functional: true,

    props,

    render (h, { props, data, slots, children, listeners }) {
        let content;

        // Slot takes priority over prop
        if (children) {
            content = [children];
        } else {
            content = [h('div', { domProps: { innerHTML: props.header } })];
        }

        // Render card action buttons if any are specified
        const hasCardActions = props.collapse || props.expandable || props.closeable;
        if (hasCardActions) {
            const collapseClick = listeners['collapse-click'] || null;
            const closeClick = listeners['close-click'] || null;

            content.push(h(
                'b-card-actions',
                {
                    props,
                    on: {
                        'collapse-click': event => collapseClick(event),
                        'close-click': event => closeClick(event)
                    }
                }
            ));
        }

        return h(
            props.headerTag,
            mergeData(data, {
                staticClass: 'card-header',
                class: [
                    props.headerClass,
                    {
                        [`bg-${props.headerBgVariant}`]: !! props.headerBgVariant,
                        [`border-${props.headerBorderVariant}`]: !! props.headerBorderVariant,
                        [`text-${props.headerTextVariant}`]: !! props.headerTextVariant
                    }
                ]
            }),
            content
        );
    }
};