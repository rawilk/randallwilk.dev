import Popper from 'popper.js';
import clickoutMixin from '../clickout';
import listenOnRootMixin from './listenOnRoot';
import { arrayFrom } from '../../utils/array';
import { assign } from '../../utils/object';
import KeyCodes from '../../utils/keyCodes';
import { warn } from '../../utils/warn';
import { isVisible, closest, selectAll, getAttr, eventOn, eventOff } from '../../utils/dom';

// Return an Array of visible items
function filterVisible (els) {
    return (els || []).filter(isVisible)
}

// Dropdown item CSS selectors
// TODO: .dropdown-form handling
const ITEM_SELECTOR = '.dropdown-item:not(.disabled):not([disabled])';

// Popper attachment positions
const AttachmentMap = {
    // DropUp Left Align
    TOP: 'top-start',
    // DropUp Right Align
    TOPEND: 'top-end',
    // Dropdown left Align
    BOTTOM: 'bottom-start',
    // Dropdown Right Align
    BOTTOMEND: 'bottom-end'
};

export default {
    mixins: [clickoutMixin, listenOnRootMixin],

    props: {
        dropup: {
            // place on top if possible
            type: Boolean,
            default: false
        },
        disabled: {
            type: Boolean,
            default: false
        },
        noFlip: {
            // Disable auto-flipping of menu from bottom<=>top
            type: Boolean,
            default: false
        },
        offset: {
            // Number of pixels to offset menu, or a CSS unit value (i.e. 1px, 1rem, etc)
            type: [Number, String],
            default: 0
        },
        popperOpts: {
            type: Object,
            default: () => ({})
        },
        right: {
            // Right align menu (default is left align)
            type: Boolean,
            default: false
        },
        text: {
            // Button label
            type: String,
            default: ''
        }
    },

    computed: {
        toggler () {
            return this.$refs.toggle.$el || this.$refs.toggle;
        }
    },

    data () {
        return {
            visible: false,
            inNavbar: null
        };
    },

    created () {
        // Create non-reactive property
        this._popper = null;
    },

    mounted () {
        // To keep one dropdown opened on page
        this.listenOnRoot('lara::dropdown::shown', this.rootCloseListener);

        // Hide when clicked on links
        this.listenOnRoot('clicked::link', this.rootCloseListener);

        // Use new namespaced events
        this.listenOnRoot('lara::link::clicked', this.rootCloseListener);
    },

    deactivated () {
        // In case we are inside a `<keep-alive>`
        this.visible = false;
        this.setTouchStart(false);
        this.removePopper();
    },

    beforeDestroy () {
        this.visible = false;
        this.setTouchStart(false);
        this.removePopper();
    },

    methods: {
        /**
         * Handle click in dropdown.
         *
         * @param {Event} event
         */
        click (event) {
            // Called only in split button mode, for the split button
            if (this.disabled) {
                this.visible = false;
                return;
            }

            this.$emit('click', event);
        },

        /**
         * Handle click out.
         */
        clickOutListener () {
            this.visible = false;
        },

        /**
         * Create popper instance.
         *
         * @param {HTMLElement} element
         */
        createPopper (element) {
            this.removePopper();
            this._popper = new Popper(element, this.$refs.menu, this.getPopperConfig());
        },

        /**
         * Focus first dropdown item.
         */
        focusFirstItem () {
            const item = this.getFirstItem();

            if (item) {
                this.focusItem(0, [item]);
            }
        },

        /**
         * Focus an item.
         *
         * @param {number} idx
         * @param {array} items
         */
        focusItem (idx, items) {
            let el = items.find((el, i) => i === idx);

            if (el && getAttr(el, 'tabindex') !== '-1') {
                el.focus();
            }
        },

        /**
         * Handle focus next.
         *
         * @param {Event} event
         * @param {boolean} up
         */
        focusNext (event, up) {
            if (! this.visible) {
                return;
            }

            event.preventDefault();
            event.stopPropagation();

            this.$nextTick(() => {
                const items = this.getItems();

                if (items.length < 1) {
                    return;
                }

                let index = items.indexOf(event.target);

                if (up && index > 0) {
                    index--;
                } else if (! up && index < items.length - 1) {
                    index++;
                }

                if (index < 0) {
                    index = 0;
                }

                this.focusItem(index, items);
            });
        },

        /**
         * Toggle focus.
         */
        focusToggler () {
            let toggler = this.toggler;

            if (toggler && toggler.focus) {
                toggler.focus();
            }
        },

        /**
         * Get first dropdown item.
         *
         * @returns {*|null}
         */
        getFirstItem () {
            // Get the first non-disabled item
            let item = this.getItems()[0];

            return item || null;
        },

        /**
         * Get dropdown items.
         *
         * @returns {*}
         */
        getItems () {
            // Get all items
            return filterVisible(selectAll(ITEM_SELECTOR, this.$refs.menu));
        },

        /**
         * Get popper configuration.
         *
         * @returns {object}
         */
        getPopperConfig () {
            let placement = AttachmentMap.BOTTOM;

            if (this.dropup && this.right) {
                // dropup + right
                placement = AttachmentMap.TOPEND;
            } else if (this.dropup) {
                // dropup + left
                placement = AttachmentMap.TOP;
            } else if (this.right) {
                // dropdown + right
                placement = AttachmentMap.BOTTOMEND;
            }

            let popperConfig = {
                placement,
                modifiers: {
                    offset: {
                        offset: this.offset || 0
                    },
                    flip: {
                        enabled: ! this.noFlip
                    }
                }
            };

            if (this.boundary) {
                popperConfig.modifiers.preventOverflow = {
                    boundariesElement: this.boundary
                };
            }

            if (this.animation) {
                popperConfig.modifiers.computeStyle = { gpuAcceleration: false };
            }

            return assign(popperConfig, this.popperOpts || {});
        },

        /**
         * Hide the dropdown.
         */
        hide () {
            if (this.disabled) {
                return;
            }

            this.visible = false;
        },

        /**
         * Hide the menu.
         */
        hideMenu () {
            // TODO: move emit hide to visible watcher, to allow cancelling of hide
            this.$emit('hide');
            this.setTouchStart(false);
            this.emitOnRoot('lara::dropdown::hidden', this);
            this.$emit('hidden');
            this.removePopper();
        },

        /**
         * Handle escape key.
         *
         * @param {Event} event
         */
        onEsc (event) {
            if (this.visible) {
                this.visible = false;

                event.preventDefault();
                event.stopPropagation();

                // Return focus to original trigger button
                this.$nextTick(this.focusToggler);
            }
        },

        /**
         * Handle focus out.
         *
         * @param {Event} event
         */
        onFocusOut (event) {
            if (this.$refs.menu.contains(event.relatedTarget)) {
                return;
            }

            this.visible = false;
        },

        /**
         * Handle keydown in dropdown.
         *
         * @param {Event} event
         */
        onKeydown (event) {
            // Called from dropdown menu context
            const key = event.keyCode;

            if (key === KeyCodes.ESC) {
                // Close on ESC
                this.onEsc(event);
            } else if (key === KeyCodes.TAB) {
                // Close on tab out
                this.onTab(event);
            } else if (key === KeyCodes.DOWN_ARROW) {
                // Down Arrow
                this.focusNext(event, false);
            } else if (key === KeyCodes.UP_ARROW) {
                // Up Arrow
                this.focusNext(event, true);
            }
        },

        /**
         * Handle mouse over.
         *
         * @param {MouseEvent} event
         */
        onMouseOver (event) {
            // Focus the item on hover
            // TODO: Special handling for inputs? Inputs are in a special .dropdown-form container
            const item = event.target;

            if (item.classList.contains('dropdown-item')
                && ! item.disabled
                && ! item.classList.contains('disabled')
                && item.focus) {
                item.focus();
            }
        },

        /**
         * Handle tab key.
         *
         * @param {Event} event
         */
        onTab (event) {
            if (this.visible) {
                // TODO: Need special handler for dealing with form inputs
                // Tab, if in a text-like input, we should just focus next item in the dropdown
                // Note: Inputs are in a special .dropdown-form container
                this.visible = false;
            }
        },

        /**
         * Remove popper instance.
         */
        removePopper () {
            if (this._popper) {
                // Ensure popper event listeners are removed cleanly
                this._popper.destroy();
            }

            this._popper = null;
        },

        /**
         * Handle root close.
         *
         * @param {object} vm
         */
        rootCloseListener (vm) {
            if (vm !== this) {
                this.visible = false;
            }
        },

        /**
         * Touch-enabled devices.
         *
         * @param {boolean} on
         */
        setTouchStart (on) {
            /*
             * If this is a touch-enabled device we add extra
             * empty mouseover listeners to the body's immediate children;
             * only needed because of broken event delegation on iOS
             * https://www.quirksmode.org/blog/archives/2014/02/mouse_event_bub.html
             */
            if ('ontouchstart' in document.documentElement) {
                const children = arrayFrom(document.body.children);

                children.forEach(el => {
                    if (on) {
                        eventOn(el, 'mouseover', this._noop);
                    } else {
                        eventOff(el, 'mouseover', this._noop);
                    }
                });
            }
        },

        /**
         * Show the dropdown.
         */
        show () {
            if (this.disabled) {
                return;
            }

            this.visible = true;
        },

        /**
         * Show the menu.
         */
        showMenu () {
            if (this.disabled) {
                return;
            }

            // TODO: move emit show to visible watcher, to allow cancelling of show
            this.$emit('show');

            // Ensure other menus are closed
            this.emitOnRoot('lara::dropdown::shown', this);

            // Are we in a navbar ?
            if (this.inNavbar === null && this.isNav) {
                this.inNavbar = Boolean(closest('.navbar', this.$el));
            }

            // Disable totally Popper.js for Dropdown in Navbar
            if (! this.inNavbar) {
                if (typeof Popper === 'undefined') {
                    warn('b-dropdown: Popper.js not found. Falling back to CSS positioning.');
                } else {
                    // for dropup with alignment we use the parent element as popper container
                    let element = ((this.dropup && this.right) || this.split) ? this.$el : this.$refs.toggle;

                    // Make sure we have a reference to an element, not a component!
                    element = element.$el || element;

                    // Instantiate popper.js
                    this.createPopper(element);
                }
            }

            this.setTouchStart(true);
            this.$emit('shown');

            // Focus on the first item on show
            this.$nextTick(this.focusFirstItem);
        },

        /**
         * Show/hide the dropdown.
         *
         * @param {Event} event
         */
        toggle (event) {
            // Called only by a button that toggles the menu
            event = event || {};

            const type = event.type;
            const key = event.keyCode;
            if (type !== 'click' && ! (type === 'keydown' && (key === KeyCodes.ENTER || key === KeyCodes.SPACE || key === KeyCodes.DOWN_ARROW))) {
                // We only toggle on Click, Enter, Space, and Arrow Down
                return;
            }

            event.preventDefault();
            event.stopPropagation();

            if (this.disabled) {
                this.visible = false;
                return;
            }

            // Toggle visibility
            this.visible = ! this.visible;
        },

        /**
         * Event handler used for touchstart -- does nothing.
         *
         * @private
         */
        _noop () {
            //
        },
    },

    watch: {
        disabled (state, old) {
            if (state !== old && state && this.visible) {
                // Hide dropdown if disabled changes to true
                this.visible = false;
            }
        },

        visible (state, old) {
            if (state === old) {
                // Avoid duplicated emits
                return;
            }

            if (state) {
                this.showMenu();
            } else {
                this.hideMenu();
            }
        }
    }
};