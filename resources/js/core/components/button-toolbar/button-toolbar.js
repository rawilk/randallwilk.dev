import { isVisible, selectAll } from '../../utils/dom';
import KeyCodes from '../../utils/keyCodes';

const ITEM_SELECTOR = [
    '.btn:not(.disabled):not([disabled]):not(.dropdown-item)',
    '.form-control:not(.disabled):not([disabled])',
    'select:not(.disabled):not([disabled])',
    'input[type="checkbox"]:not(.disabled)',
    'input[type="radio"]:not(.disabled)'
].join(',');

export default {
    props: {
        justify: {
            type: Boolean,
            default: false
        },
        keyNav: {
            type: Boolean,
            default: false
        }
    },

    computed: {
        /**
         * Generate the CSS classes for the toolbar.
         *
         * @returns {array}
         */
        classObject () {
            return [
                'btn-toolbar',
                (this.justify && ! this.vertical) ? 'justify-content-between' : ''
            ];
        }
    },

    render (h) {
        return h(
            'div',
            {
                class: this.classObject,
                attrs: {
                    role: 'toolbar',
                    tabindex: this.keyNav ? '0' : null
                },
                on: {
                    focusin: this.onFocusin,
                    keydown: this.onKeydown
                }
            },
            [this.$slots.default]
        );
    },

    mounted () {
        if (this.keyNav) {
            // Pre-set the tabindexes if the markup does not include tabindex="-1" on the toolbar items
            this.getItems();
        }
    },

    methods: {
        /**
         * Give focus to the first item in the toolbar.
         *
         * @param {Event} event
         */
        focusFirst (event) {
            const items = this.getItems();

            if (items.length > 0) {
                this.setItemFocus(items[0]);
            }
        },

        /**
         * Give focus to the last item in the toolbar.
         *
         * @param {Event} event
         */
        focusLast (event) {
            const items = this.getItems();

            if (items.length > 0) {
                this.setItemFocus(items[items.length - 1]);
            }
        },

        /**
         * Give focus to the next item in the toolbar.
         *
         * @param {Event} event
         * @param {*} previous
         */
        focusNext (event, previous) {
            const items = this.getItems();

            if (items.length < 1) {
                return;
            }

            let index = items.indexOf(event.target);

            if (previous && index > 0) {
                index--;
            } else if (! previous && index < items.length - 1) {
                index++;
            }

            if (index < 0) {
                index = 0;
            }

            this.setItemFocus(items[index]);
        },

        /**
         * Get the items in the toolbar that can be focused.
         *
         * @returns {array}
         */
        getItems () {
            let items = selectAll(ITEM_SELECTOR, this.$el);

            items.forEach(item => {
                // Ensure tabfocus is -1 on any new elements
                item.tabIndex = -1;
            });

            return items.filter(el => isVisible(el));
        },

        /**
         * Handle on focus in.
         *
         * @param {Event} event
         */
        onFocusin (event) {
            if (event.target === this.$el) {
                event.preventDefault();
                event.stopPropagation();

                this.focusFirst(event);
            }
        },

        /**
         * Handle keydown event.
         *
         * @param {Event} event
         */
        onKeydown (event) {
            if (! this.keyNav) {
                return;
            }

            const key = event.keyCode;
            const shift = event.shiftKey;

            if (key === KeyCodes.UP_ARROW || key === KeyCodes.LEFT_ARROW) {
                event.preventDefault();
                event.stopPropagation();

                if (shift) {
                    this.focusFirst(event);
                } else {
                    this.focusNext(event, true);
                }
            } else if (key === KeyCodes.DOWN_ARROW || key === KeyCodes.RIGHT_ARROW) {
                event.preventDefault();
                event.stopPropagation();

                if (shift) {
                    this.focusLast(event);
                } else {
                    this.focusNext(event, false);
                }
            }
        },

        /**
         * Give focus to the given item.
         *
         * @param {HTMLElement} item
         */
        setItemFocus (item) {
            this.$nextTick(() => {
                if (item.focus) {
                    item.focus();
                }
            });
        }
    }
};