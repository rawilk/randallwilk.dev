import idMixin from '../../mixins/component/id';
import formRadioCheckMixin from '../../mixins/component/formRadioCheck';
import formMixin from '../../mixins/component/form';
import formSizeMixin from '../../mixins/component/formSize';
import formStateMixin from '../../mixins/component/formState';
import formCustomMixin from '../../mixins/component/formCustom';
import { isArray } from '../../utils/array';
import looseEqual from '../../utils/looseEqual';

export default {
    mixins: [
        idMixin,
        formRadioCheckMixin,
        formMixin,
        formSizeMixin,
        formStateMixin,
        formCustomMixin
    ],

    props: {
        value: {
            default: true
        },
        uncheckedValue: {
            // Not applicable in multi-check mode
            default: false
        },
        indeterminate: {
            // Not applicable in multi-check mode
            type: Boolean,
            default: false
        },
        plain: {
            // Override of mixin
            type: Boolean,
            default: true
        },
        filledIn: {
            type: Boolean,
            default: false
        },
        variant: {
            type: String,
            default: null
        }
    },

    computed: {
        /**
         * Determine if checkbox is checked.
         *
         * @returns {boolean}
         */
        is_Checked () {
            const checked = this.computedLocalChecked;

            if (isArray(checked)) {
                for (let i = 0; i < checked.length; i++) {
                    if (looseEqual(checked[i], this.value)) {
                        return true;
                    }
                }

                return false;
            } else {
                return looseEqual(checked, this.value);
            }
        },

        /**
         * Generate the label CSS classes.
         *
         * @returns {array}
         */
        labelClasses () {
            return [
                'custom-control',
                'custom-checkbox',
                this.get_Size ? `form-control-${this.get_Size}` : '',
                this.get_StateClass
            ];
        },
    },

    render (h) {
        const input = h('input', {
            ref: 'check',
            class: [
                this.is_ButtonMode
                    ? ''
                    : this.is_Plain ? 'form-check-input' : 'custom-control-input',
                this.get_StateClass,
                { 'filled-in': this.is_Plain && this.filledIn },
                !! this.variant && ! this.is_ButtonMode ? `chk-col-${this.variant}` : null,
            ],
            directives: [
                {
                    name: 'model',
                    rawName: 'v-model',
                    value: this.computedLocalChecked,
                    expression: 'computedLocalChecked'
                }
            ],
            attrs: {
                id: this.safeId(),
                type: 'checkbox',
                name: this.get_Name,
                disabled: this.is_Disabled,
                required: this.is_Required,
                autocomplete: 'off',
                'true-value': this.value,
                'false-value': this.uncheckedValue,
                'aria-required': this.is_Required ? 'true' : null
            },
            domProps: { value: this.value, checked: this.is_Checked },
            on: {
                focus: this.handleFocus,
                blur: this.handleFocus,
                change: this.emitChange,
                __c: evt => {
                    const $$a = this.computedLocalChecked;
                    const $$el = evt.target;

                    if (isArray($$a)) {
                        // Multiple checkbox
                        const $$v = this.value;
                        let $$i = this._i($$a, $$v); // Vue's 'loose' Array.indexOf

                        if ($$el.checked) {
                            // Append value to array
                            $$i < 0 && (this.computedLocalChecked = $$a.concat([$$v]));
                        } else {
                            // Remove value from array
                            $$i > -1 &&
                            (this.computedLocalChecked = $$a
                                .slice(0, $$i)
                                .concat($$a.slice($$i + 1)));
                        }
                    } else {
                        // Single checkbox
                        this.computedLocalChecked = $$el.checked ? this.value : this.uncheckedValue;
                    }
                }
            }
        });

        const description = h(
            this.is_ButtonMode ? 'span' : 'label',
            {
                class: this.is_ButtonMode
                    ? null
                    : this.is_Plain ? 'form-check-label' : 'custom-control-label',
                attrs: { for: this.is_ButtonMode ? null : this.safeId() }
            },
            [this.$slots.default]
        );

        if (! this.is_ButtonMode) {
            return h(
                'div',
                {
                    class: [
                        this.is_Plain ? 'form-check' : this.labelClasses,
                        { 'form-check-inline': this.is_Plain && ! this.is_Stacked },
                        { 'custom-control-inline': ! this.is_Plain && ! this.is_Stacked },
                    ]
                },
                [input, description]
            );
        } else {
            return h('label', { class: [this.buttonClasses] }, [input, description]);
        }
    },

    mounted () {
        // Set initial indeterminate state
        this.setIndeterminate(this.indeterminate);
    },

    methods: {
        /**
         * Emit a changed event.
         */
        emitChange ({ target: { checked } }) {
            // Change event is only fired via user interaction
            // And we only emit the value of this checkbox
            if (this.is_Child || isArray(this.computedLocalChecked)) {
                this.$emit('change', checked ? this.value : null);

                if (this.is_Child) {
                    // If we are a child of form-checkbox-group, emit change on parent
                    this.$parent.$emit('change', this.computedLocalChecked);
                }
            } else {
                // Single radio mode supports unchecked value
                this.$emit('change', checked ? this.value : this.uncheckedValue);
            }

            this.$emit('update:indeterminate', this.$refs.check.indeterminate);
        },

        /**
         * Set indeterminate state.
         *
         * @param {boolean} state
         */
        setIndeterminate (state) {
            // Indeterminate only supported in single checkbox mode
            if (this.is_Child || isArray(this.computedLocalChecked)) {
                return;
            }

            this.$refs.check.indeterminate = state;

            // Emit update event to prop
            this.$emit('update:indeterminate', this.$refs.check.indeterminate);
        }
    },

    watch: {
        computedLocalChecked (newVal, oldVal) {
            if (looseEqual(newVal, oldVal)) {
                return;
            }

            this.$emit('input', newVal);
            this.$emit('update:indeterminate', this.$refs.check.indeterminate);
        },

        checked (newVal, oldVal) {
            if (this.is_Child || looseEqual(newVal, oldVal)) {
                return;
            }

            this.computedLocalChecked = newVal;
        },

        indeterminate (newVal) {
            this.setIndeterminate(newVal);
        }
    }
};