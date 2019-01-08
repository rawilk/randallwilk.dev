import { mergeData } from 'vue-functional-data-merge';

export const props = {
    flush: {
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
        const componentData = {
            staticClass: 'list-group',
            class: { 'list-group-flush': props.flush }
        };

        return h(props.tag, mergeData(data, componentData), children);
    }
};