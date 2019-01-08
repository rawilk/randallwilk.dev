import bImg from './img';
import { isVisible, getBCR, eventOn, eventOff } from '../../utils/dom';

const THROTTLE = 100;

export default {
    components: { bImg },

    props: {
        alt: {
            type: String,
            default: null
        },
        blankColor: {
            type: String,
            default: 'transparent'
        },
        blankHeight: {
            type: [Number, String],
            default: null
        },
        blankSrc: {
            // If null, a blank image is generated
            type: String,
            default: null
        },
        blankWidth: {
            type: [Number, String],
            default: null
        },
        block: {
            type: Boolean,
            default: false
        },
        center: {
            type: Boolean,
            default: false
        },
        fluid: {
            type: Boolean,
            default: false
        },
        fluidGrow: {
            type: Boolean,
            default: false
        },
        height: {
            type: [Number, String],
            default: null
        },
        left: {
            type: Boolean,
            default: false
        },
        offsetTop: {
            type: [Number, String],
            default: 360
        },
        right: {
            type: Boolean,
            default: false
        },
        rounded: {
            type: [Boolean, String],
            default: false
        },
        src: {
            type: String,
            default: null,
            required: true
        },
        throttle: {
            type: [Number, String],
            default: THROTTLE
        },
        thumbnail: {
            type: Boolean,
            default: false
        },
        width: {
            type: [Number, String],
            default: null
        }
    },

    computed: {
        /**
         * Determine if blank placeholder should be shown.
         *
         * @returns {boolean}
         */
        computedBlank () {
            return ! ((this.isShown || this.blankSrc));
        },

        /**
         * Generate the height of the image.
         *
         * @returns {*}
         */
        computedHeight () {
            return this.isShown ? this.height : (this.blankHeight || this.height);
        },

        /**
         * Generate the img src.
         *
         * @returns {*}
         */
        computedSrc () {
            return (! this.blankSrc || this.isShown) ? this.src : this.blankSrc;
        },

        /**
         * Generate the width of the image.
         *
         * @returns {*}
         */
        computedWidth () {
            return this.isShown ? this.width : (this.blankWidth || this.width);
        },
    },

    data () {
        return {
            isShown: false,
            scrollTimeout: null
        };
    },

    render (h) {
        return h(
            'b-img',
            {
                props: {
                    src: this.computedSrc,
                    alt: this.alt,
                    blank: this.computedBlank,
                    blankColor: this.blankColor,
                    width: this.computedWidth,
                    height: this.computedHeight,
                    fluid: this.fluid,
                    fluidGrow: this.fluidGrow,
                    block: this.block,
                    thumbnail: this.thumbnail,
                    rounded: this.rounded,
                    left: this.left,
                    right: this.right,
                    center: this.center
                }
            }
        );
    },

    mounted () {
        this.setListeners(true);
        this.checkView();
    },

    activated () {
        this.setListeners(true);
        this.checkView();
    },

    deactivated () {
        this.setListeners(false);
    },

    beforeDestroy () {
        this.setListeners(false);
    },

    methods: {
        /**
         * Check if visible.
         */
        checkView () {
            // check bounding box + offset to see if we should show
            if (! isVisible(this.$el)) {
                // Element is hidden, so skip for now
                return;
            }

            const offset = parseInt(this.offsetTop, 10) || 0;
            const docElement = document.documentElement;

            const view = {
                left: 0 - offset,
                top: 0 - offset,
                bottom: docElement.clientHeight + offset,
                right: docElement.clientWidth + offset
            };

            const box = getBCR(this.$el);

            if (box.right >= view.left && box.bottom >= view.top && box.left <= view.right && box.top <= view.bottom) {
                // image is in view (or about to be in view)
                this.isShown = true;
                this.setListeners(false);
            }
        },

        /**
         * Handle on scroll.
         */
        onScroll () {
            if (this.isShown) {
                this.setListeners(false);
            } else {
                clearTimeout(this.scrollTimeout);
                this.scrollTimeout = setTimeout(this.checkView, parseInt(this.throttle, 10) || THROTTLE);
            }
        },

        /**
         * Set the event listeners.
         *
         * @param {boolean} on
         */
        setListeners (on) {
            clearTimeout(this.scrollTimer);
            this.scrollTimeout = null;
            const root = window;

            if (on) {
                eventOn(root, 'scroll', this.onScroll);
                eventOn(root, 'resize', this.onScroll);
                eventOn(root, 'orientationchange', this.onScroll);
            } else {
                eventOff(root, 'scroll', this.onScroll);
                eventOff(root, 'resize', this.onScroll);
                eventOff(root, 'orientationchange', this.onScroll);
            }
        }
    }
};