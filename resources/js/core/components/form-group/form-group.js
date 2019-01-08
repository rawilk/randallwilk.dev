import { warn } from '../../utils/warn';
import { select, selectAll, isVisible, setAttr, removeAttr, getAttr } from '../../utils/dom';
import idMixin from '../../mixins/component/id';
import formValidationMixin from '../../mixins/component/formValidation';
import bFormRow from '../layout/form-row';
import bFormText from '../form/form-text';
import bFormInvalidFeedback from '../form/form-invalid-feedback';
import bFormValidFeedback from '../form/form-valid-feedback';

// Selector for finding first input in the form-group
const SELECTOR = 'input:not(:disabled),textarea:not(:disabled),select:not(:disabled)';

export default {
    mixins: [idMixin, formValidationMixin],

    inject: ['$validator', 'form'],

    components: {
        bFormRow,
        bFormText,
        bFormInvalidFeedback,
        bFormValidFeedback
    },

    props: {
        breakpoint: {
            type: String,
            default: 'sm'
        },
        description: {
            type: String,
            default: null
        },
        horizontal: {
            type: Boolean,
            default: false
        },
        invalidFeedback: {
            type: String,
            default: null
        },
        label: {
            type: String,
            default: null
        },
        labelCols: {
            type: [Number, String],
            default: 3,
            validator (value) {
                if (Number(value) >= 1 && Number(value) <= 11) {
                    return true;
                }

                warn('b-form-group: label-cols must be a value between 1 and 11');

                return false;
            }
        },
        labelFor: {
            type: String,
            default: null
        },
        labelSize: {
            type: String,
            default: null
        },
        labelSrOnly: {
            type: Boolean,
            default: false
        },
        labelTextAlign: {
            type: String,
            default: null
        },
        validated: {
            type: Boolean,
            default: false
        },
        validFeedback: {
            type: String,
            default: null
        }
    },

    computed: {
        /**
         * Generate the described by ids.
         *
         * @returns {string|null}
         */
        describedByIds () {
            return [
                this.descriptionId,
                this.invalidFeedbackId,
                this.validFeedbackId
            ].filter(i => i).join(' ') || null;
        },

        /**
         * Generate the description id.
         *
         * @returns {string|null}
         */
        descriptionId () {
            return this.hasDescription ? this.safeId('_Lara_description_') : null;
        },

        /**
         * Generate the form group CSS classes.
         *
         * @returns {array}
         */
        groupClasses () {
            return [
                'b-form-group',
                'form-group',
                this.validated ? 'was-validated' : null,
                this.stateClass,
            ];
        },

        /**
         * Determine if the form group has a description.
         *
         * @returns {boolean}
         */
        hasDescription () {
            return !! this.description || !! this.$slots['description'];
        },

        /**
         * Determine if the form group has a label.
         *
         * @returns {boolean}
         */
        hasLabel () {
            return !! this.label || !! this.$slots['label'];
        },

        /**
         * Determine if the form group has invalid feedback.
         *
         * @returns {boolean}
         */
        hasInvalidFeedback () {
            if (this.computedState === true) {
                // If the form group state is explicitly valid, we return false
                return false;
            }

            return this.validationError || !! this.invalidFeedback || !! this.$slots['invalid-feedback'];
        },

        /**
         * Determine if this form group has valid feedback.
         *
         * @returns {boolean}
         */
        hasValidFeedback () {
            if (this.computedState === false) {
                // If the form group state is explicitly invalid, we return false
                return false;
            }

            return !! this.validFeedback || !! this.$slots['valid-feedback'];
        },

        /**
         * Generate the input layout CSS classes.
         *
         * @returns {array}
         */
        inputLayoutClasses () {
            return [
                this.horizontal ? `col-${this.breakpoint}-${12 - Number(this.labelCols)}` : null
            ];
        },

        /**
         * Generate the invalid feedback id.
         *
         * @returns {string|null}
         */
        invalidFeedbackId () {
            return this.hasInvalidFeedback ? this.safeId('_Lara_feedback_invalid_') : null;
        },

        /**
         * Generate the label's CSS classes.
         *
         * @returns {array}
         */
        labelClasses () {
            return [
                (this.labelSize || this.labelFor) ? 'col-form-label' : 'col-form-legend',
                this.labelSize ? `col-form-label-${this.labelSize}` : null,
                this.labelTextAlign ? `text-${this.labelTextAlign}` : null,
                this.horizontal ? null : 'pt-0',
                this.labelClass
            ];
        },

        /**
         * Generate the label's id.
         *
         * @returns {string|null}
         */
        labelId () {
            return this.hasLabel ? this.safeId('_Lara_label_') : null;
        },

        /**
         * Generate the label layout CSS classes.
         *
         * @returns {array}
         */
        labelLayoutClasses () {
            return [
                this.horizontal ? `col-${this.breakpoint}-${this.labelCols}` : null
            ];
        },

        /**
         * Generate the valid feedback id.
         *
         * @returns {string|null}
         */
        validFeedbackId () {
            return this.hasValidFeedback ? this.safeId('_Lara_feedback_valid_') : null;
        },
    },

    render (h) {
        const t = this;
        const $slots = t.$slots;

        // Label / Legend
        let legend = h(false);
        if (t.hasLabel) {
            let children = $slots['label'];
            const legendTag = 'label';
            const legendDomProps = children ? {} : { innerHTML: t.label };
            const legendAttrs = { id: t.labelId, for: t.labelFor || null };
            const legendClick = (t.labelFor || t.labelSrOnly) ? {} : { click: t.legendClick };

            if (t.horizontal) {
                // Horizontal layout with label
                if (t.labelSrOnly) {
                    // Screen Reader only so we wrap label/legend in a div to preserve layout
                    children = h(
                        legendTag,
                        { class: ['sr-only'], attrs: legendAttrs, domProps: legendDomProps },
                        children
                    );

                    legend = h('div', { class: t.labelLayoutClasses }, [children]);
                } else {
                    legend = h(
                        legendTag,
                        {
                            class: [t.labelLayoutClasses, t.labelClasses],
                            attrs: legendAttrs,
                            domProps: legendDomProps,
                            on: legendClick
                        }
                    );
                }
            } else {
                // Vertical layout with label
                legend = h(
                    legendTag,
                    {
                        class: t.labelSrOnly ? ['sr-only'] : t.labelClasses,
                        attrs: legendAttrs,
                        domProps: legendDomProps,
                        on: legendClick
                    },
                    children
                );
            }
        } else if (t.horizontal) {
            // No label but has horizontal layout, so we need a spacer element for layout
            legend = h('div', { class: t.labelLayoutClasses });
        }

        // Invalid feedback text (explicitly hidden if state is valid)
        let invalidFeedback = h(false);
        if (t.hasInvalidFeedback) {
            let domProps = {};

            if (! $slots['invalid-feedback']) {
                domProps = { innerHTML: t.validationError || t.invalidFeedback || '' };
            }

            invalidFeedback = h(
                'b-form-invalid-feedback',
                {
                    props: {
                        id: t.invalidFeedbackId,
                        forceShow: t.computedState === false
                    },
                    attrs: {
                        role: 'alert',
                        'aria-live': 'assertive',
                        'aria-atomic': 'true'
                    },
                    domProps
                },
                $slots['invalid-feedback'] || $slots['feedback']
            );
        }

        // Valid feedback text (explicitly hidden if state is invalid)
        let validFeedback = h(false);
        if (t.hasValidFeedback) {
            const domProps = $slots['feedback'] ? {} : { innerHTML: t.validFeedback || '' };

            validFeedback = h(
                'b-form-valid-feedback',
                {
                    props: {
                        id: t.validFeedbackId,
                        forceShow: t.computedState === true
                    },
                    attrs: {
                        role: 'alert',
                        'aria-live': 'assertive',
                        'aria-atomic': 'true'
                    },
                    domProps
                },
                $slots['valid-feedback']
            );
        }

        // Form help text (description)
        let description = h(false);
        if (t.hasDescription) {
            const domProps = $slots['description'] ? {} : { innerHTML: t.description || '' };

            description = h(
                'b-form-text',
                { attrs: { id: t.descriptionId }, domProps },
                $slots['description']
            );
        }

        // Build content layout
        const content = h(
            'div',
            {
                ref: 'content',
                class: t.inputLayoutClasses,
                attrs: t.labelFor ? {} : { role: 'group', 'aria-labeledby': t.labelId }
            },
            [$slots['default'], invalidFeedback, validFeedback, description]
        );

        // Generate main form-group wrapper
        return h(
            !! t.labelFor ? 'div' : 'fieldset',
            {
                class: t.groupClasses,
                attrs: {
                    id: t.safeId(),
                    disabled: t.disabled,
                    role: 'group',
                    'aria-invalid': t.computedState === false ? 'true' : null,
                    'aria-labeledby': t.labelId,
                    'aria-describedby': t.labelFor ? null : t.describedByIds
                }
            },
            t.horizontal ? [h('b-form-row', {}, [legend, content])] : [legend, content]
        );
    },

    mounted () {
        this.$nextTick(() => {
            // Set the aria-describedby IDs on the input specified by label-for.
            // We do this in a nextTick to ensure the children have finished rendering.
            this.setInputDescribedBy(this.describedByIds);
            this.setInputLabeledBy();
        });
    },

    methods: {
        /**
         * Handle a legend click inside the form group.
         *
         * @param {MouseEvent} event
         */
        legendClick (event) {
            const tagName = event.target ? event.target.tagName : '';

            if (/^(input|select|textarea)$/i.test(tagName)) {
                // If clicked an input inside legend, we just let the default happen
                return;
            }

            // Focus the first non-disabled visible input when the legend element is clicked
            const inputs = selectAll(SELECTOR, this.$refs.content).filter(isVisible);
            if (inputs[0] && inputs[0].focus) {
                inputs[0].focus();
            }
        },

        /**
         * Set aria-labeledby attribute on the form group input.
         */
        setInputLabeledBy () {
            if (this.labelFor && typeof document !== 'undefined') {
                const input = select(`#${this.labelFor}`, this.$refs.content);

                if (input) {
                    setAttr(input, 'aria-labeledby', this.labelId);
                }
            }
        },

        /**
         * Set the aria-describedby attribute on the form group input.
         *
         * @param add
         * @param remove
         */
        setInputDescribedBy (add, remove) {
            // Sets the `aria-describedby` attribute on the input if label-for is set.
            // Optionally accepts a string of IDs to remove as the second parameter
            if (this.labelFor && typeof document !== 'undefined') {
                const input = select(`#${this.labelFor}`, this.$refs.content);
                if (input) {
                    const adb = 'aria-describedby';
                    let ids = (getAttr(input, adb) || '').split(/\s+/);

                    remove = (remove || '').split(/\s+/);

                    // Update ID list, preserving any original IDs
                    ids = ids.filter(id => remove.indexOf(id) === -1).concat(add || '').join(' ').trim();

                    if (ids) {
                        setAttr(input, adb, ids);
                    } else {
                        removeAttr(input, adb);
                    }
                }
            }
        }
    },

    watch: {
        describedByIds (add, remove) {
            if (add !== remove) {
                this.setInputDescribedBy(add, remove);
            }
        },

        labelId (label, oldLabel) {
            if (label !== oldLabel) {
                this.setInputLabeledBy();
            }
        }
    }
};