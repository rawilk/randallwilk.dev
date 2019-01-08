import { mergeData } from 'vue-functional-data-merge';

export default {
    functional: true,

    props: {
        /**
         * The text to show in the tooltip.
         *
         * @type {string}
         */
        title: {
            type: String,
            default: ''
        },

        /**
         * The triggers that will show the tooltip.
         *
         * @type {Array}
         */
        triggers: {
            type: Array,
            default: () => (['hover', 'click'])
        }
    },

    render (h, { props, data }) {
        let modifiers = {};

        props.triggers.forEach(trigger => modifiers[trigger] = true);

        if (! Object.keys(modifiers).length) {
            modifiers.hover = true;
        }

        return h(
            'i',
            mergeData(data, {
                staticClass: 'mdi mdi-help-circle',
                domProps: {
                    title: props.title
                },
                directives: [
                    {
                        name: 'b-tooltip',
                        rawName: 'v-b-tooltip',
                        modifiers
                    }
                ]
            })
        );
    }
};