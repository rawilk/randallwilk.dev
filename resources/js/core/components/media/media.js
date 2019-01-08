import { mergeData } from 'vue-functional-data-merge';
import MediaBody from './media-body';
import MediaAside from './media-aside';

export const props = {
    noBody: {
        type: Boolean,
        default: false
    },
    rightAlign: {
        type: Boolean,
        default: false
    },
    tag: {
        type: String,
        default: 'div'
    },
    verticalAlign: {
        type: String,
        default: 'top'
    }
};

export default {
    functional: true,

    props,

    render (h, { props, data, slots, children }) {
        let childNodes = props.noBody ? children : [];
        const $slots = slots();

        if (! props.noBody) {
            if ($slots.aside && ! props.rightAlign) {
                childNodes.push(
                    h(MediaAside, { staticClass: 'mr-3', props: { verticalAlign: props.verticalAlign } }, $slots.aside)
                );
            }

            childNodes.push(h(MediaBody, $slots.default));

            if ($slots.aside && props.rightAlign) {
                childNodes.push(
                    h(MediaAside, { staticClass: 'ml-3', props: { verticalAlign: props.verticalAlign } }, $slots.aside)
                );
            }
        }

        return h(props.tag, mergeData(data, { staticClass: 'media' }), childNodes);
    }
};