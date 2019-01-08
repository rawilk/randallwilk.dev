import idMixin from '../../mixins/component/id';
import formMixin from '../../mixins/component/form';
import formSizeMixin from '../../mixins/component/formSize';
import formStateMixin from '../../mixins/component/formState';
import { arrayIncludes } from '../../utils/array';

// Valid supported input types
const TYPES = [
    'text',
    'password',
    'email',
    'number',
    'url',
    'tel',
    'search',
    'range',
    'color',
    `date`,
    `time`,
    `datetime`,
    `datetime-local`,
    `month`,
    `week`
];

export default {
    mixins: [idMixin, formMixin, formSizeMixin, formStateMixin],

    props: {
        value: {
            default: null
        },
        type: {
            type: String,
            default: 'text',
            validator: type => arrayIncludes(TYPES, type)
        },
        ariaInvalid: {
            type: [Boolean, String],
            default: false
        },
        readonly: {
            type: Boolean,
            default: false
        },
        plaintext: {
            type: Boolean,
            default: false
        },
        autocomplete: {
            type: String,
            default: null
        },
        placeholder: {
            type: String,
            default: null
        },
        formatter: {
            type: Function
        },
        lazyFormatter: {
            type: Boolean,
            default: false
        }
    },

    computed: {
        /**
         * Generate the `aria-invalid` attribute.
         *
         * @returns {*}
         */
        computedAriaInvalid () {
            if (! this.ariaInvalid || this.ariaInvalid === 'false') {
                // this.ariaInvalid is null or false or 'false'
                return this.computedState === false ? 'true' : null;
            }

            if (this.ariaInvalid === true) {
                // User wants explicit aria-invalid=true
                return 'true';
            }

            // Most likely a string value (which could be 'true')
            return this.ariaInvalid;
        },

        /**
         * Generate the input CSS classes.
         *
         * @returns {array}
         */
        inputClass () {
            return [
                this.plaintext ? 'form-control-plaintext' : 'form-control',
                this.sizeFormClass,
                this.stateClass
            ];
        },

        /**
         * Determine the type of the input.
         *
         * @returns {string}
         */
        localType () {
            // We only allow certain types
            return arrayIncludes(TYPES, this.type) ? this.type : 'text';
        },
    },

    data () {
        return {
            localValue: this.value
        };
    },

    render (h) {
        return h('input', {
            ref: 'input',
            class: this.inputClass,
            domProps: { value: this.localValue },
            attrs: {
                id: this.safeId(),
                name: this.name,
                type: this.localType,
                disabled: this.disabled,
                required: this.required,
                readonly: this.readonly || this.plaintext,
                placeholder: this.placeholder,
                autocomplete: this.autocomplete || null,
                'aria-required': this.required ? 'true' : null,
                'aria-invalid': this.computedAriaInvalid
            },
            on: {
                input: this.onInput,
                change: this.onChange
            }
        });
    },

    methods: {
        /**
         * Handle the on change event.
         *
         * @param {Event} event
         */
        onChange (event) {
            this.localValue = this.format(event.target.value, event);
            this.$emit('change', this.localValue);
        },

        /**
         * Handle input.
         *
         * @param {Event} event
         */
        onInput (event) {
            const value = event.target.value;
            if (this.lazyFormatter) {
                // Update the model with the current un-formatted value
                this.localValue = value;
            } else {
                this.localValue = this.format(value, event);
            }
        },

        /**
         * Handle input focus.
         *
         */
        focus () {
            if (! this.disabled) {
                this.$el.focus();
            }
        },

        /**
         * Format the input value for display.
         *
         * @param {string} value
         * @param {*} e
         * @returns {*}
         */
        format (value, e) {
            if (this.formatter) {
                const formattedValue = this.formatter(value, e);

                if (formattedValue !== value) {
                    return formattedValue;
                }
            }

            return value;
        },
    },

    watch: {
        value (newVal, oldVal) {
            if (newVal !== oldVal) {
                this.localValue = newVal;
            }
        },

        localValue (newVal, oldVal) {
            if (newVal !== oldVal) {
                this.$emit('input', newVal);
            }
        }
    }
};