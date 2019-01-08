import idMixin from '../../mixins/component/id';
import formMixin from '../../mixins/component/form';
import formOptionsMixin from '../../mixins/component/formOptions';
import formSizeMixin from '../../mixins/component/formSize';
import formStateMixin from '../../mixins/component/formState';
import formCustomMixin from '../../mixins/component/formCustom';
import bCheckbox from './form-checkbox';

export default {
    mixins: [
        idMixin,
        formMixin,
        formSizeMixin,
        formStateMixin,
        formCustomMixin,
        formOptionsMixin
    ],

    components: { bCheckbox },

    model: {
        prop: 'checked',
        event: 'input'
    },

    props: {
        plain: {
            // Mixin override
            type: Boolean,
            default: true
        },
        checked: {
            type: [String, Number, Object, Array, Boolean],
            default: null
        },
        validated: {
            type: Boolean,
            default: false
        },
        ariaInvalid: {
            type: [Boolean, String],
            default: false
        },
        stacked: {
            type: Boolean,
            default: false
        },
        buttons: {
            // Render as button style
            type: Boolean,
            default: false
        },
        buttonVariant: {
            // Only applicable when rendered with button style
            type: String,
            default: 'secondary'
        }
    },

    computed: {
        /**
         * Determine if `aria-invalid`.
         *
         * @returns {*}
         */
        computedAriaInvalid () {
            if (this.ariaInvalid === true || this.ariaInvalid === 'true' || this.ariaInvalid === '') {
                return 'true';
            }

            return this.get_State === false ? 'true' : null;
        },

        /**
         * Get the state.
         *
         * @returns {*}
         */
        get_State () {
            // Child radios sniff this value
            return this.computedState;
        },

        /**
         * Generate the group CSS classes.
         *
         * @returns {array}
         */
        groupClasses () {
            if (this.buttons) {
                return [
                    'btn-group-toggle',
                    this.stacked ? 'btn-group-vertical' : 'btn-group',
                    this.size ? `btn-group-${this.size}` : '',
                    this.validated ? `was-validated` : ''
                ];
            }

            return [
                this.sizeFormClass,
                this.stacked && this.custom ? 'custom-controls-stacked' : '',
                this.validated ? `was-validated` : ''
            ];
        }
    },

    data () {
        return {
            localChecked: this.checked || [],

            // Flag for children
            is_RadioCheckGroup: true
        };
    },

    render (h) {
        const $slots = this.$slots;

        const checks = this.formOptions.map((option, index) => {
            return h(
                'b-checkbox',
                {
                    key: `check_${index}_opt`,
                    props: {
                        id: this.safeId(`_Lara_check_${index}_opt_`),
                        name: this.name,
                        value: option.value,
                        required: this.name && this.required,
                        disabled: option.disabled
                    }
                },
                [h('span', { domProps: { innerHTML: option.text } })]
            );
        });

        return h(
            'div',
            {
                class: this.groupClasses,
                attrs: {
                    id: this.safeId(),
                    role: 'group',
                    tabindex: '-1',
                    'aria-required': this.required ? 'true' : null,
                    'aria-invalid': this.computedAriaInvalid
                }
            },
            [$slots.first, checks, $slots.default]
        );
    },

    watch: {
        checked () {
            this.localChecked = this.checked;
        },

        localChecked (newVal) {
            this.$emit('input', newVal);
        }
    }
};