import bProgressBar from './progress-bar';

export default {
    components: { bProgressBar },

    props: {
        // These props can be inherited from the child b-progress-bar(s)
        animated: {
            type: Boolean,
            default: false
        },
        height: {
            type: String,
            default: null
        },
        max: {
            type: Number,
            default: 100
        },
        precision: {
            type: Number,
            default: 0
        },
        showProgress: {
            type: Boolean,
            default: false
        },
        showValue: {
            type: Boolean,
            default: false
        },
        striped: {
            type: Boolean,
            default: false
        },
        variant: {
            type: String,
            default: null
        },

        // This prop is not inherited by child b-progress-bar(s)
        value: {
            type: Number,
            default: 0
        }
    },

    computed: {
        /**
         * Determine the progress bar height.
         *
         * @returns {object}
         */
        progressHeight () {
            return { height: this.height || null };
        }
    },

    render (h) {
        let childNodes = this.$slots.default;

        if (! childNodes) {
            childNodes = h(
                'b-progress-bar',
                {
                    props: {
                        value: this.value,
                        max: this.max,
                        precision: this.precision,
                        variant: this.variant,
                        animated: this.animated,
                        striped: this.striped,
                        showProgress: this.showProgress,
                        showValue: this.showValue
                    }
                }
            );
        }

        return h('div', { class: ['progress'], style: this.progressHeight }, [childNodes]);
    }
};