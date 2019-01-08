// Props for the multi-select component

export default {
    props: {
        /**
         * Allows user to remove all selected value. If set to false,
         * at least one option must be selected.
         *
         * @type {boolean}
         */
        allowEmpty: {
            type: Boolean,
            default: false
        },

        /**
         * Enable/disable closing of select after selecting an option.
         *
         * @type {boolean}
         */
        closeOnSelect: {
            type: Boolean,
            default: true
        },

        /**
         * String to show when pointing to an already selected option.
         *
         * @type {string}
         */
        deselectLabel: {
            type: String,
            default: 'Press enter to remove'
        },

        /**
         * String to show when pointing to an already selected option group.
         *
         * @type {string}
         */
        deselectGroupLabel: {
            type: String,
            default: 'Press enter to deselect group'
        },

        /**
         * Hide already selected options.
         *
         * @type {boolean}
         */
        hideSelected: {
            type: Boolean,
            default: false
        },

        /**
         * Number of allowed selected options.
         *
         * @type {number}
         */
        max: {
            type: Number,
            default: null
        },

        /**
         * Fixed opening direction instead of auto. Possible
         * options include `above` / `top`, or `below` / `bottom`.
         *
         * @type {string}
         */
        openDirection: {
            type: String,
            default: ''
        },

        /**
         * Equivalent to the placeholder attribute on a <select> input.
         *
         * @type {string}
         */
        placeholder: {
            type: String,
            default: 'Select an option'
        },

        /**
         * If set to true, it will preserve the search query
         * when opening/closing the component.
         *
         * @type {boolean}
         */
        preserveSearch: {
            type: Boolean,
            default: false
        },

        /**
         * Selects the first option if initial value is empty.
         *
         * @type {boolean}
         */
        preselectFirst: {
            type: Boolean,
            default: false
        },

        /**
         * Reset the value, search and selected after the value changes.
         *
         * @type {boolean}
         */
        resetAfter: {
            type: Boolean,
            default: false
        },

        /**
         * Adds/removes the search input.
         *
         * @type {boolean}
         */
        searchable: {
            type: Boolean,
            default: true
        },

        /**
         * String to show when pointing to an option.
         *
         * @type {string}
         */
        selectLabel: {
            type: String,
            default: ''
        },

        /**
         * String to show when pointing to an option.
         *
         * @type {string}
         */
        selectGroupLabel: {
            type: String,
            default: 'Press enter to select group'
        },

        /**
         * String to show next to the select option.
         *
         * @type {string}
         */
        selectedLabel: {
            type: String,
            default: 'Selected'
        },

        /**
         * Determines if labels should be shown on highlighted options.
         *
         * @type {boolean}
         */
        showLabels: {
            type: Boolean,
            default: true
        },

        /**
         * Show the noResult slot if no results are found.
         *
         * @type {boolean}
         */
        showNoResults: {
            type: Boolean,
            default: true
        },

        /**
         * Specify the tabindex of the Multiselect component.
         *
         * @type {number}
         */
        tabIndex: {
            type: Number,
            default: 0
        },

        /**
         * Disable / Enable tagging.
         *
         * @type {boolean}
         */
        taggable: {
            type: Boolean,
            default: false
        },

        /**
         * String to show when highlighting a potential tag.
         *
         * @type {string}
         */
        tagPlaceholder: {
            type: String,
            default: 'Press enter to create a tag'
        },

        /**
         * By default new tags will appear above the search results.
         * Changing to `bottom` will revert this behavior and will prioritize
         * the search results.
         *
         * @type {string}
         */
        tagPosition: {
            type: String,
            default: 'top'
        }
    }
};