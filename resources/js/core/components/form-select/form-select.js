import idMixin from '../../mixins/component/id';
import formOptionsMixin from '../../mixins/component/formOptions';
import formMixin from '../../mixins/component/form';
import formStateMixin from '../../mixins/component/formState';
import multiSelectMixin from './multiSelectMixin';
import { arrayFrom, isArray } from '../../utils/array';
import Multiselect from 'vue-multiselect';

export default {
    components: {
        Multiselect
    },

    mixins: [
        idMixin,
        formMixin,
        formStateMixin,
        formOptionsMixin,
        multiSelectMixin
    ],

    props: {
        ariaInvalid: {
            type: [Boolean, String],
            default: false
        },
        multiple: {
            type: Boolean,
            default: false
        },
        value: {},
    },

    computed: {
        /**
         * Generate the aria-invalid attribute.
         *
         * @returns {string|null}
         */
        computedAriaInvalid () {
            if (this.ariaInvalid === true || this.ariaInvalid === 'true') {
                return 'true';
            }

            return this.stateClass === 'is-invalid' ? 'true' : null;
        },

        /**
         * Generate the select CSS classes.
         *
         * @returns {array}
         */
        inputClass () {
            return [
                'form-control',
                this.stateClass
            ];
        },
    },

    data () {
        return {
            actualValue: this.value,
            localValue: null
        };
    },

    mounted () {
        // track-by is not working for some reason, so doing this as a work-around for now
        this.$nextTick(() => {
            this.localValue = this.findOptionByValue(this.value);
        });
    },

    render (h) {
        // const $slots = this.$slots;

        const deselectLabel = this.allowEmpty ? this.deselectLabel : '';

        return h(
            'multiselect',
            {
                ref: 'input',
                props: {
                    value: this.localValue,
                    options: this.formOptions,
                    id: this.safeId(),
                    name: this.name,
                    disabled: this.disabled,
                    required: this.required,
                    'aria-required': this.required ? 'true' : null,
                    'aria-invalid': this.computedAriaInvalid,
                    multiple: this.multiple,

                    // multiselect props
                    'label': 'text',
                    'select-label': this.selectLabel,
                    'select-group-label': this.selectGroupLabel,
                    'selected-label': this.selectedLabel,
                    'deselect-label': deselectLabel,
                    'deselect-group-label': this.deselectGroupLabel,
                    'show-labels': this.showLabels,
                    'open-direction': this.openDirection,
                    'show-no-results': this.showNoResults,
                    'tabindex': this.tabIndex,
                    'searchable': this.searchable,
                    'allow-empty': this.allowEmpty,
                    'reset-after': this.resetAfter,
                    'close-on-select': this.closeOnSelect,
                    'max': this.max,
                    'preserve-search': this.preserveSearch,
                    'preselect-first': this.preselectFirst,
                    'placeholder': this.placeholder,
                    'hide-selected': this.hideSelected,
                    'taggable': this.taggable,
                    'tag-placeholder': this.tagPlaceholder,
                    'tag-position': this.tagPosition,
                },
                on: {
                    input: option => {
                        if (this.multiple) {
                            let values = arrayFrom(option)
                                .filter(o => o.value !== null && o.value !== '')
                                .map(o => o.value);

                            values = [...new Set(values)];

                            this.actualValue = values;
                            this.localValue = this.findOptionByValue(values);
                        } else {
                            this.actualValue = option ? option.value : null;
                            this.localValue = option;
                        }
                    },
                    remove: removedOption => {
                        if (! this.multiple) {
                            return;
                        }

                        const options = arrayFrom(this.localValue)
                            .filter(option => option.value !== removedOption.value);

                        const values = options.map(option => option.value);

                        this.$nextTick(() => {
                            this.actualValue = values;
                            this.localValue = options;

                            if (removedOption.isCustom) {
                                const newOptions = arrayFrom(this.formOptions)
                                    .filter(option => option.value !== removedOption.value);

                                this.$nextTick(() => {
                                    this.$emit('options-updated', newOptions);
                                });
                            }
                        });
                    },
                    tag: newTag => {
                        // Make sure tag doesn't exist first
                        const index = this.formOptions
                            .findIndex(option => option.text.toString().toLowerCase() === newTag.toString().toLowerCase());

                        if (index > -1) {
                            return;
                        }

                        const option = {
                            value: newTag,
                            text: newTag,
                            disabled: false,
                            isCustom: true
                        };

                        const newOption = {
                            [this.valueField]: option.value,
                            [this.textField]: option.text,
                            disabled: false,
                            isCustom: true
                        };

                        this.$emit('options-updated', newOption);

                        this.$nextTick(() => {
                            if (this.multiple) {
                                if (! isArray(this.actualValue)) {
                                    this.actualValue = [this.actualValue];
                                }

                                this.actualValue.push(option.value);
                                this.localValue = this.findOptionByValue(this.actualValue);
                            } else {
                                this.actualValue = option ? option.value : null;
                                this.localValue = option;
                            }
                        });
                    }
                }
            },
            // [$slots.first, options, $slots.default]
        );
    },

    methods: {
        /**
         * Find an option by the given value.
         *
         * @param {*} value
         * @returns {*}
         */
        findOptionByValue (value) {
            if (this.multiple) {
                if (! isArray(value)) {
                    value = [value];
                }

                return value.map(selection => this.formOptions.find(option => option.value === selection));
            }

            return this.formOptions.find(option => option.value === value);
        },
    },

    watch: {
        actualValue (newValue) {
            this.$emit('input', newValue);
        },

        value (newValue) {
            this.actualValue = newValue;
            this.localValue = this.findOptionByValue(newValue);
        }
    }
};