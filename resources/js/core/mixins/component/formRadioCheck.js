export default {
    props: {
        value: {},
        checked: {
            // This is the model, except when in group mode
        },
        buttonVariant: {
            // Only applicable when rendered with button style
            type: String,
            default: null
        }
    },

    model: {
        prop: 'checked',
        event: 'input'
    },

    computed: {
        /**
         * Generate the button classes.
         *
         * @returns {array}
         */
        buttonClasses () {
            // Same for radio & check
            return [
                'btn',
                `btn-${this.get_ButtonVariant}`,
                this.get_Size ? `btn-${this.get_Size}` : '',
                // 'disabled' class makes "button" look disabled
                this.is_Disabled ? 'disabled' : '',
                // 'active' class makes "button" look pressed
                this.is_Checked ? 'active' : '',
                // Focus class makes button look focused
                this.hasFocus ? 'focus' : ''
            ];
        },

        /**
         * Getter/setter for if the checkbox/radio is checked.
         */
        computedLocalChecked: {
            get () {
                if (this.is_Child) {
                    return this.$parent.localChecked;
                } else {
                    return this.localChecked;
                }
            },
            set (val) {
                if (this.is_Child) {
                    this.$parent.localChecked = val;
                } else {
                    this.localChecked = val;
                }
            }
        },

        /**
         * Get the button variant class.
         *
         * @returns {string}
         */
        get_ButtonVariant () {
            // Local variant trumps parent variant
            return this.buttonVariant || (this.is_Child ? this.$parent.buttonVariant : null) || 'secondary';
        },

        /**
         * Get the name of the input.
         *
         * @returns {string|null}
         */
        get_Name () {
            return (this.is_Child ? (this.$parent.name || this.$parent.safeId()) : this.name) || null;
        },

        /**
         * Determine if checkbox/radio button is a button.
         *
         * @returns {boolean}
         */
        is_ButtonMode () {
            return Boolean(this.is_Child && this.$parent.buttons);
        },

        /**
         * Determine if this item belongs to a checkbox/radio group.
         *
         * @returns {boolean}
         */
        is_Child () {
            return Boolean(this.$parent && this.$parent.is_RadioCheckGroup);
        },

        /**
         * Determine if checkbox/radio button is custom styled.
         *
         * @returns {boolean}
         */
        is_Custom () {
            return ! this.is_Plain;
        },

        /**
         * Determine if checkbox/radio button is disabled.
         *
         * @returns {boolean}
         */
        is_Disabled () {
            // Child can be disabled while parent isn't
            return Boolean(this.is_Child ? (this.$parent.disabled || this.disabled) : this.disabled);
        },

        /**
         * Determine if checkbox/radio button is inline.
         *
         * @returns {boolean}
         */
        is_Inline () {
            return ! this.is_Stacked;
        },

        /**
         * Determine if checkbox/radio button is plain.
         *
         * @returns {boolean}
         */
        is_Plain () {
            return Boolean(this.is_Child ? this.$parent.plain : this.plain);
        },

        /**
         * Determine if the checkbox/radio button is required.
         *
         * @returns {boolean}
         */
        is_Required () {
            return Boolean(this.is_Child ? this.$parent.required : this.required);
        },

        /**
         * Determine if checkbox/radio button is stacked.
         *
         * @returns {boolean}
         */
        is_Stacked () {
            return Boolean(this.is_Child && this.$parent.stacked);
        }
    },

    data () {
        return {
            localChecked: this.checked,
            hasFocus: false
        };
    },

    methods: {
        /**
         * Handle checkbox/radio button focus.
         *
         * @param {Event} event
         */
        handleFocus (event) {
            // When in buttons mode, we need to add 'focus' class to label when radio focused
            if (this.is_ButtonMode && event.target) {
                if (event.type === 'focus') {
                    this.hasFocus = true;
                } else if (event.type === 'blur') {
                    this.hasFocus = false;
                }
            }
        }
    }
};