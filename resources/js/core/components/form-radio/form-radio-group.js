import idMixin from '../../mixins/component/id';
import formOptionsMixin from '../../mixins/component/formOptions';
import formMixin from '../../mixins/component/form';
import formSizeMixin from '../../mixins/component/formSize';
import formStateMixin from '../../mixins/component/formState';
import formCustomMixin from '../../mixins/component/formCustom';
import bRadio from './form-radio';

export default {
    mixins: [
        idMixin,
        formMixin,
        formSizeMixin,
        formStateMixin,
        formCustomMixin,
        formOptionsMixin
    ],

    components: { bRadio },

    model: {
        prop: 'checked',
        event: 'input'
    },

    props: {
        checked: {
            type: [String, Object, Number, Boolean],
            default: null
        },
        validated: {
            // Used for applying hte `was-validated` class to the group
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
        },
        plain: {
            type: Boolean,
            default: true
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
            // Required by child radios
            return this.computedState;
        },

        /**
         * Generate the CSS group classes.
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
            localChecked: this.checked,
            // Flag for children
            is_RadioCheckGroup: true
        };
    },

    render (h) {
        const $slots = this.$slots;

        const radios = this.formOptions.map((option, index) => {
            return h(
                'b-radio',
                {
                    key: `radio_${index}_opt`,
                    props: {
                        id: this.safeId(`_Lara_radio_${index}_opt_`),
                        name: this.name,
                        value: option.value,
                        required: Boolean(this.name && this.required),
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
                    role: 'radiogroup',
                    tabindex: '-1',
                    'aria-required': this.required ? 'true' : null,
                    'aria-invalid': this.computedAriaInvalid
                }
            },
            [$slots.first, radios, $slots.default]
        );
    },

    watch: {
        checked (newVal, oldVal) {
            this.localChecked = this.checked;
        },

        localChecked (newVal, oldVal) {
            this.$emit('input', newVal);
        }
    }
};