export default {
    props: {
        label: {
            type: String,
            default: null
        },
        value: {
            type: Number,
            default: 0
        },

        // $parent prop values take precedence over the following props
        // Which is why they are defaulted to null
        max: {
            type: Number,
            default: null
        },
        precision: {
            type: Number,
            default: null
        },
        variant: {
            type: String,
            default: null
        },
        striped: {
            type: Boolean,
            default: null
        },
        animated: {
            type: Boolean,
            default: null
        },
        showProgress: {
            type: Boolean,
            default: null
        },
        showValue: {
            type: Boolean,
            default: null
        }
    },

    computed: {
        /**
         * Determine if the progress bar is animated.
         *
         * @returns {boolean}
         */
        computedAnimated () {
            // Prefer our local setting over parent setting
            return typeof this.animated === 'boolean' ? this.animated : (this.$parent.animated || false);
        },

        /**
         * Generate max progress.
         *
         * @returns {number}
         */
        computedMax () {
            // Prefer local setting over parent setting
            return typeof this.max === 'number' ? this.max : (this.$parent.max || 100);
        },

        /**
         * Generate the precision.
         *
         * @returns {number}
         */
        computedPrecision () {
            // Prefer local setting over parent setting
            return typeof this.precision === 'number' ? this.precision : (this.$parent.precision || 0);
        },

        /**
         * Determine if the progress should be shown.
         *
         * @returns {boolean}
         */
        computedShowProgress () {
            // Prefer our local setting over parent setting
            return typeof this.showProgress === 'boolean' ? this.showProgress : (this.$parent.showProgress || false);
        },

        /**
         * Determine if the value should be shown.
         *
         * @returns {boolean}
         */
        computedShowValue () {
            // Prefer local setting over parent setting
            return typeof this.showValue === 'boolean' ? this.showValue : (this.$parent.showValue || false);
        },

        /**
         * Determine if the progress bar is striped.
         *
         * @returns {boolean}
         */
        computedStriped () {
            // Prefer local setting over parent setting
            return typeof this.striped === 'boolean' ? this.striped : (this.$parent.striped || false);
        },

        /**
         * Generate the variant.
         *
         * @returns {string}
         */
        computedVariant () {
            // Prefer local setting over parent setting
            return this.variant || this.$parent.variant;
        },

        /**
         * Generate the progress bar CSS classes.
         *
         * @returns {array}
         */
        progressBarClasses () {
            return [
                'progress-bar',
                this.computedVariant ? `bg-${this.computedVariant}` : '',
                (this.computedStriped || this.computedAnimated) ? 'progress-bar-striped' : '',
                this.computedAnimated ? 'progress-bar-animated' : ''
            ];
        },

        /**
         * Generate progress bar CSS styles.
         *
         * @returns {object}
         */
        progressBarStyles () {
            return {
                width: (100 * (this.value / this.computedMax)) + '%'
            };
        },

        /**
         * Determine the current progress.
         *
         * @returns {number}
         */
        progress () {
            const p = Math.pow(10, this.computedPrecision);

            return Math.round((100 * p * this.value) / this.computedMax) / p;
        }
    },

    render (h) {
        let childNodes = h(false);

        if (this.$slots.default) {
            childNodes = this.$slots.default;
        } else if (this.label) {
            childNodes = h('span', { domProps: { innerHTML: this.label } });
        } else if (this.computedShowProgress) {
            childNodes = this.progress.toFixed(this.computedPrecision);
        } else if (this.computedShowValue) {
            childNodes = this.value.toFixed(this.computedPrecision);
        }

        return h(
            'div',
            {
                class: this.progressBarClasses,
                style: this.progressBarStyles,
                attrs: {
                    role: 'progressbar',
                    'aria-valuemin': '0',
                    'aria-valuemax': this.computedMax.toString(),
                    'aria-valuenow': this.value.toFixed(this.computedPrecision)
                }
            },
            [childNodes]
        );
    }
};