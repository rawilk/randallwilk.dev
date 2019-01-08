import { mergeData } from 'vue-functional-data-merge';
import { arrayIncludes } from '../../utils/array';

export const props = {
    aspect: {
        type: String,
        default: '16by9'
    },
    tag: {
        type: String,
        default: 'div'
    },
    type: {
        type: String,
        default: 'iframe',
        validator: str => arrayIncludes(['iframe', 'embed', 'video', 'object', 'img', 'b-img', 'b-img-lazy'], str)
    }
};

export default {
    functional: true,

    props,

    render (h, { props, data, children }) {
        return h(
            props.tag,
            {
                ref: data.ref,
                staticClass: 'embed-responsive',
                class: {
                    [`embed-responsive-${props.aspect}`]: !! props.aspect
                }
            },
            [h(props.type, mergeData(data, { staticClass: 'embed-responsive-item' }), children)]
        );
    }
};