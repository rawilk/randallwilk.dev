import { mergeData } from 'vue-functional-data-merge';
import { prefixPropName } from '../../utils/strings';
import copyProps from '../../utils/copyProps';
import { assign } from '../../utils/object';
import cardMixin from '../../mixins/component/card';

export const props = assign(
    {},
    copyProps(cardMixin.props, prefixPropName.bind(null, 'footer')),
    {
        footer: {
            type: String,
            default: null
        },
        footerClass: {
            type: [String, Object, Array],
            default: null
        }
    }
);

export default {
    functional: true,

    props,

    render (h, { props, data, slots, children }) {
        return h(
            props.footerTag,
            mergeData(data, {
                staticClass: 'card-footer',
                class: [
                    props.footerClass,
                    {
                        [`bg-${props.footerBgVariant}`]: !! props.footerBgVariant,
                        [`border-${props.footerBorderVariant}`]: !! props.footerBorderVariant,
                        [`text-${props.footerTextVariant}`]: !! props.footerTextVariant,
                        ['d-none']: !! props.collapsed
                    }
                ]
            }),
            children || [h('div', { domProps: { innerHTML: props.footer } })]
        );
    }
};