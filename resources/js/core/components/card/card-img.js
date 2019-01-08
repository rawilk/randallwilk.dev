import { mergeData } from 'vue-functional-data-merge';

export const props = {
    alt: {
        type: String,
        default: null
    },
    bottom: {
        type: Boolean,
        default: false
    },
    fluid: {
        type: Boolean,
        default: false
    },
    src: {
        type: String,
        required: true
    },
    top: {
        type: Boolean,
        default: false
    }
};

export default {
    functional: true,

    props,

    render (h, { props, data }) {
        let staticClass = 'card-img';

        if (props.top) {
            staticClass += '-top';
        } else if (props.bottom) {
            staticClass += '-bottom';
        }

        return h(
            'img',
            mergeData(data, {
                staticClass,
                class: { 'img-fluid': props.fluid },
                attrs: { src: props.src, alt: props.alt }
            })
        );
    }
};