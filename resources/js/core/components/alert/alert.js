import bButtonClose from '../button/button-close';

export default {
    components: { bButtonClose },

    model: {
        prop: 'show',
        event: 'input'
    },

    props: {
        dismissible: {
            type: Boolean,
            default: true
        },
        dismissLabel: {
            type: String,
            default: 'Close'
        },
        fade: {
            type: Boolean,
            default: true
        },
        show: {
            type: [Boolean, Number],
            default: false
        },
        variant: {
            type: String,
            default: 'info'
        }
    },

    computed: {
        /**
         * Generate the alert variant.
         *
         * @returns {string}
         */
        alertVariant () {
            return `alert-${this.variant}`;
        },

        /**
         * Generate the classes for the alert.
         *
         * @returns {object}
         */
        classObject () {
            return [
                'alert',
                this.alertVariant,
                this.dismissible ? 'alert-dismissible' : ''
            ];
        },

        /**
         * Determine if shown.
         *
         * @returns {boolean}
         */
        localShow () {
            return ! this.dismissed && (this.countDownTimerId || this.show);
        },
    },

    data () {
        return {
            countDownTimerId: null,
            dismissed: false
        };
    },

    render (h) {
        if (! this.localShow) {
            // If not showing, render placeholder
            return h(false);
        }

        let dismissBtn = h(false);
        if (this.dismissible) {
            // Add dismiss button
            dismissBtn = h(
                'b-button-close',
                { attrs: { 'aria-label': this.dismissLabel }, on: { click: this.dismiss } },
                [this.$slots.dismiss]
            );
        }

        const alert = h(
            'div',
            { class: this.classObject, attrs: { role: 'alert', 'aria-live': 'polite', 'aria-atomic': true } },
            [dismissBtn, this.$slots.default]
        );

        return ! this.fade ? alert : h(
            'transition',
            { props: { name: 'fade', appear: true } },
            [alert]
        );
    },

    mounted () {
        this.showChanged();
    },

    destroyed () {
        this.clearCounter();
    },

    methods: {
        /**
         * Clear the countdown timer.
         */
        clearCounter () {
            if (this.countDownTimerId) {
                clearInterval(this.countDownTimerId);
                this.countDownTimerId = null;
            }
        },

        /**
         * Dismiss the alert.
         */
        dismiss () {
            this.clearCounter();

            this.dismissed = true;
            this.$emit('dismissed');
            this.$emit('input', false);

            if (typeof this.show === 'number') {
                this.$emit('dismiss-count-down', 0);
                this.$emit('input', 0);
            } else {
                this.$emit('input', false);
            }
        },

        /**
         * Alert was shown or hidden.
         */
        showChanged () {
            // Reset counter status
            this.clearCounter();

            // Reset dismiss status
            this.dismissed = false;

            // No timer for boolean values
            if (this.show === true || this.show === false || this.show === null || this.show === 0) {
                return;
            }

            // Start counter
            let dismissCountDown = this.show;
            this.countDownTimerId = setInterval(() => {
                if (dismissCountDown < 1) {
                    this.dismiss();
                    return;
                }

                dismissCountDown--;

                this.$emit('dismiss-count-down', dismissCountDown);
                this.$emit('input', dismissCountDown);
            }, 1000);
        }
    },

    watch: {
        show () {
            this.showChanged();
        }
    }
};