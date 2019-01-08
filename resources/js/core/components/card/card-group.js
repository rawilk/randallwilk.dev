import { mergeData } from 'vue-functional-data-merge';

export const props = {
    columns: {
        type: Boolean,
        default: false
    },
    deck: {
        type: Boolean,
        default: false
    },
    tag: {
        type: String,
        default: 'div'
    }
};

export default {
    functional: true,

    props,

    render (h, { props, data, children }) {
        let staticClass = 'card-group';

        if (props.columns) {
            staticClass = 'card-columns';
        }

        if (props.deck && ! props.columns) {
            staticClass = 'card-deck';
        }

        return h(props.tag, mergeData(data, { staticClass }), children);
    }
};