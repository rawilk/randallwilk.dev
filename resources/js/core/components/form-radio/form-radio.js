import idMixin from '../../mixins/component/id';
import formMixin from '../../mixins/component/form';
import formStateMixin from '../../mixins/component/formState';
import formRadioCheckMixin from '../../mixins/component/formRadioCheck';
import looseEqual from '../../utils/looseEqual';

export default {
    mixins: [idMixin, formRadioCheckMixin, formMixin, formStateMixin],

    props: {
        plain: {
            type: Boolean,
            default: true
        },
        gap: {
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
         * Determine if radio is checked.
         *
         * @returns {boolean}
         */
        is_Checked () {
            return looseEqual(this.value, this.computedLocalChecked);
        },

        /**
         * Generate radio CSS classes.
         *
         * @returns {array}
         */
        labelClasses () {
            // Specific to radio
            return [
                this.get_Size ? `form-control-${this.get_Size}` : '',
                'custom-control',
                'custom-radio',
                this.get_StateClass
            ];
        }
    },

    render (h) {
        const input = h('input', {
            ref: 'radio',
            class: [
                this.is_ButtonMode
                    ? ''
                    : this.is_Plain ? null : 'custom-control-input',
                this.get_StateClass,
                { 'with-gap': this.gap && ! this.is_ButtonMode },
                this.variant && ! this.is_ButtonMode ? `radio-col-${this.variant}` : null,
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
                type: 'radio',
                name: this.get_Name,
                required: this.get_Name && this.is_Required,
                disabled: this.is_Disabled,
                autocomplete: 'off'
            },
            domProps: {
                value: this.value,
                checked: looseEqual(this.computedLocalChecked, this.value)
            },
            on: {
                focus: this.handleFocus,
                blur: this.handleFocus,
                change: this.emitChange,
                __c: evt => {
                    this.computedLocalChecked = this.value;
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
                        { 'custom-control-inline': ! this.is_Plain && ! this.is_Stacked }
                    ]
                },
                [input, description]
            );
        } else {
            return h('label', { class: [this.buttonClasses] }, [input, description]);
        }
    },

    methods: {
        /**
         * Emit an event change.
         */
        emitChange ({ target: { checked } }) {
            // Change is only emitted on user interaction
            this.$emit('change', checked ? this.value : null);

            // If this is a child of form-radio-group, we emit a change event on it as well
            if (this.is_Child) {
                this.$parent.$emit('change', this.computedLocalChecked);
            }
        }
    },

    watch: {
        // Radio Groups can only have a single value, so our watchers are simple
        checked (newVal) {
            this.computedLocalChecked = newVal;
        },

        computedLocalChecked () {
            this.$emit('input', this.computedLocalChecked);
        }
    }
};