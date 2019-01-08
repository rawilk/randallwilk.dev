import idMixin from '../../mixins/component/id';
import formRadioCheckMixin from '../../mixins/component/formRadioCheck';
import formMixin from '../../mixins/component/form';
import formSizeMixin from '../../mixins/component/formSize';
import formStateMixin from '../../mixins/component/formState';
import { isArray } from '../../utils/array';
import looseEqual from '../../utils/looseEqual';

export default {
    mixins: [
        idMixin,
        formRadioCheckMixin,
        formMixin,
        formSizeMixin,
        formStateMixin,
    ],

    props: {
        value: {
            default: true
        },
        uncheckedValue: {
            // Not applicable in multi-check mode
            default: false
        },
        variant: {
            type: String,
            default: null
        },
        onLabel: {
            type: String,
            default: null
        },
        offLabel: {
            type: String,
            default: null
        },
    },

    computed: {
        /**
         * Determine if in checked state.
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
    },

    render (h) {
        const input = h('input', {
            ref: 'check',
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
                'aria-required': this.is_Required ? 'true' :  null,
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

        const lever = h('span', {
            class: [
                'lever',
                !! this.variant ? `switch-col-${this.variant}` : null,
            ]
        });

        let offLabel = h(false);
        if (!! this.$slots.offLabel) {
            offLabel = this.$slots.offLabel;
        } else if (!! this.offLabel) {
            offLabel = this.offLabel;
        }

        let onLabel = h(false);
        if (!! this.$slots.onLabel) {
            onLabel = this.$slots.onLabel;
        } else if (!! this.$slots.default) {
            onLabel = this.$slots.default;
        } else if (!! this.onLabel) {
            onLabel = this.onLabel;
        }

        const label = h('label', {}, [offLabel, input, lever, onLabel]);

        return h(
            'div',
            {
                staticClass: 'switch'
            },
            [label]
        );
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
        },
    },

    watch: {
        computedLocalChecked (newVal, oldVal) {
            if (looseEqual(newVal, oldVal)) {
                return;
            }

            this.$emit('input', newVal);
        },

        checked (newVal, oldVal) {
            if (this.is_Child || looseEqual(newVal, oldVal)) {
                return;
            }

            this.computedLocalChecked = newVal;
        }
    }
};