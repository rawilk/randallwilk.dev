import bImg from '../image/img';
import idMixin from '../../mixins/component/id';

export default {
    components: { bImg },

    mixins: [idMixin],

    props: {
        imgSrc: {
            type: String,
            default: null
        },
        imgAlt: {
            type: String
        },
        imgWidth: {
            type: [Number, String]
        },
        imgHeight: {
            type: [Number, String]
        },
        imgBlank: {
            type: Boolean,
            default: false
        },
        imgBlankColor: {
            type: String,
            default: 'transparent'
        },
        contentVisibleUp: {
            type: String
        },
        contentTag: {
            type: String,
            default: 'div'
        },
        caption: {
            type: String
        },
        captionTag: {
            type: String,
            default: 'h3'
        },
        text: {
            type: String
        },
        textTag: {
            type: String,
            default: 'p'
        },
        background: {
            type: String
        }
    },

    computed: {
        /**
         * Generate the height.
         *
         * @returns {*}
         */
        computedHeight () {
            // Use local height, or try parent height
            return this.imgHeight || this.$parent.imgHeight;
        },

        /**
         * Determine the width.
         *
         * @returns {*}
         */
        computedWidth () {
            // Use local width, or try parent width
            return this.imgWidth || this.$parent.imgWidth;
        },

        /**
         * Generate the content classes.
         *
         * @returns {array}
         */
        contentClasses () {
            return [
                'carousel-caption',
                this.contentVisibleUp ? 'd-none' : '',
                this.contentVisibleUp ? `d-${this.contentVisibleUp}-block` : ''
            ];
        },
    },

    render (h) {
        const $slots = this.$slots;

        let img = $slots.img;
        if (! img && (this.imgSrc || this.imgBlank)) {
            img = h(
                'b-img',
                {
                    props: {
                        fluidGrow: true,
                        block: true,
                        src: this.imgSrc,
                        blank: this.imgBlank,
                        blankColor: this.imgBlankColor,
                        width: this.computedWidth,
                        height: this.computedHeight,
                        alt: this.imgAlt
                    }
                }
            );
        }

        const content = h(
            this.contentTag,
            { class: this.contentClasses },
            [
                this.caption ? h(this.captionTag, { domProps: { innerHTML: this.caption } }) : h(false),
                this.text ? h(this.textTag, { domProps: { innerHTML: this.text } }) : h(false),
                $slots.default
            ]
        );

        return h(
            'div',
            {
                class: ['carousel-item'],
                style: { background: this.background },
                attrs: { id: this.safeId(), role: 'listitem' }
            },
            [img, content]
        );
    }
};