import KeyCodes from '../../utils/keyCodes';
import observeDom from '../../utils/observeDom';
import idMixin from '../../mixins/component/id';
import { warn } from '../../utils/warn';

// Helper component
const bTabButtonHelper = {
    name: 'bTabButtonHelper',

    props: {
        content: { type: [String, Array], default: '' },
        href: { type: String, default: '#' },
        posInSet: { type: Number, default: null },
        setSize: { type: Number, default: null },
        controls: { type: String, default: null },
        id: { type: String, default: null },
        active: { type: Boolean, default: false },
        disabled: { type: Boolean, default: false },
        linkClass: { default: null },
        itemClass: { default: null },
        noKeyNav: { type: Boolean, default: false }
    },

    render (h) {
        const link = h('a', {
            class: [
                'nav-link',
                { active: this.active, disabled: this.disabled },
                this.linkClass
            ],
            attrs: {
                role: 'tab',
                tabindex: this.noKeyNav ? null : '-1',
                href: this.href,
                id: this.id,
                disabled: this.disabled,
                'aria-selected': this.active ? 'true' : 'false',
                'aria-setsize': this.setSize,
                'aria-posinset': this.posInSet,
                'aria-controls': this.controls
            },
            on: {
                click: this.handleClick,
                keydown: this.handleClick
            }
        }, this.content);

        return h(
            'li',
            { class: ['nav-item', this.itemClass], attrs: { role: 'presentation' } },
            [link]
        );
    },

    methods: {
        /**
         * Handle tab click.
         *
         * @param {MouseEvent} event
         */
        handleClick (event) {
            function stop () {
                event.preventDefault();
                event.stopPropagation();
            }

            if (event.type !== 'click' && this.noKeyNav) {
                return;
            }

            if (this.disabled) {
                stop();

                return;
            }

            if (event.type === 'click' || event.keyCode === KeyCodes.ENTER || event.keyCode === KeyCodes.SPACE) {
                stop();

                this.$emit('click', event);
            }
        }
    }
};

export default {
    mixins: [idMixin],

    props: {
        tag: {
            type: String,
            default: 'div'
        },
        card: {
            type: Boolean,
            default: false
        },
        custom: {
            type: Boolean,
            default: true
        },
        justifyContentEnd: {
            type: Boolean,
            default: false
        },
        small: {
            type: Boolean,
            default: false
        },
        value: {
            type: [Number, String],
            default: null
        },
        pills: {
            type: Boolean,
            default: false
        },
        vertical: {
            type: Boolean,
            default: false
        },
        bottom: {
            type: Boolean,
            default: false
        },
        end: {
            // Synonym for 'bottom'
            type: Boolean,
            default: false
        },
        noFade: {
            type: Boolean,
            default: true
        },
        noNavStyle: {
            type: Boolean,
            default: false
        },
        noKeyNav: {
            type: Boolean,
            default: false
        },
        lazy: {
            // This prop is sniffed by the tab child
            type: Boolean,
            default: false
        },
        contentClass: {
            type: [String, Array, Object],
            default: null
        },
        navClass: {
            type: [String, Array, Object],
            default: null
        },
        navWrapperClass: {
            type: [String, Array, Object],
            default: null
        }
    },

    computed: {
        /**
         * Determine if tabs fade.
         *
         * @returns {boolean}
         */
        fade () {
            // This computed prop is sniffed by the tab child
            return ! this.noFade;
        },

        /**
         * Determine nav style.
         *
         * @returns {string}
         */
        navStyle () {
            return this.pills ? 'pills' : 'tabs';
        }
    },

    data () {
        return {
            currentTab: this.value,
            tabs: [],
            valueIsString: false
        };
    },

    render (h) {
        const tabs = this.tabs;

        // Navigation 'buttons'
        const buttons = tabs.map((tab, index) => {
            return h(bTabButtonHelper, {
                key: index,
                props: {
                    content: tab.$slots.title || tab.title,
                    href: tab.href,
                    id: tab.controlledBy || this.safeId(`_Lara_tab_${index + 1}_`),
                    active: tab.localActive,
                    disabled: tab.disabled,
                    setSize: tabs.length,
                    posInSet: index + 1,
                    controls: this.safeId('_Lara_tab_container_'),
                    linkClass: tab.titleLinkClass,
                    itemClass: tab.titleItemClass,
                    noKeyNav: this.noKeyNav
                },
                on: {
                    click: event => {
                        this.setTab(index);
                    }
                }
            });
        });

        // Nav 'button' wrapper
        let navs = h(
            'ul',
            {
                class: [
                    'nav',
                    {
                        [`nav-${this.navStyle}`]: ! this.noNavStyle,
                        [`card-header-${this.navStyle}`]: this.card && ! this.vertical,
                        'card-header': this.card && this.vertical,
                        'h-100': this.card && this.vertical,
                        'flex-column': this.vertical,
                        'border-bottom-0': this.vertical,
                        'rounded-0': this.vertical,
                        'justify-content-end': this.justifyContentEnd,
                        small: this.small
                    },
                    this.navClass
                ],
                attrs: {
                    role: 'tablist',
                    tabindex: this.noKeyNav ? null : '0',
                    id: this.safeId('_Lara_tab_controls_')
                },
                on: { keydown: this.onKeynav }
            },
            [buttons, this.$slots.tabs]
        );

        navs = h(
            'div',
            {
                class: [
                    {
                        'card-header': this.card && ! this.vertical && ! (this.end || this.bottom),
                        'card-footer': this.card && ! this.vertical && (this.end || this.bottom),
                        'col-auto': this.vertical,
                        'custom-tab': this.custom && ! this.pills,
                    },
                    this.navWrapperClass
                ]
            },
            [navs]
        );

        let empty;
        if (tabs && tabs.length) {
            empty = h(false);
        } else {
            empty = h(
                'div',
                { class: ['tab-pane', 'active', { 'card-body': this.card }] },
                this.$slots.empty
            );
        }

        // Main content section
        const content = h(
            'div',
            {
                ref: 'tabsContainer',
                class: ['tab-content', { col: this.vertical }, this.contentClass],
                attrs: { id: this.safeId('_Lara_tab_container_') }
            },
            [this.$slots.default, empty]
        );

        // Render final output
        return h(
            this.tag,
            {
                class: [
                    'tabs',
                    { row: this.vertical, 'no-gutters': this.vertical && this.card, 'tabs-vertical': this.vertical }
                ],
                attrs: { id: this.safeId() }
            },
            [
                this.end || this.bottom ? content : h(false),
                [navs],
                this.end || this.bottom ? h(false) : content
            ]
        );
    },

    created () {
        if (typeof this.value === 'string') {
            this.valueIsString = true;
        }
    },

    mounted () {
        this.updateTabs();

        // Observe Child changes so we can notify tabs change
        observeDom(this.$refs.tabsContainer, this.updateTabs.bind(this), {
            subtree: false
        });
    },

    methods: {
        /**
         * Util: Return the sign of a number (as -1, 0, or 1)
         *
         * @returns {number}
         */
        sign (x) {
            return x === 0 ? 0 : x > 0 ? 1 : -1;
        },

        /**
         * Find the index of the given tab.
         *
         * @param {string} tabId
         * @returns {number}
         */
        findTabIndex (tabId) {
            return this.tabs.findIndex(tab => tab.id === tabId);
        },

        /**
         * Handle keyboard navigation.
         *
         * @param {Event} event
         */
        onKeynav (event) {
            if (this.noKeyNav) {
                return;
            }

            const key = event.keyCode;
            const shift = event.shiftKey;

            function stop () {
                event.preventDefault();
                event.stopPropagation();
            }

            if (key === KeyCodes.UP_ARROW || key === KeyCodes.LEFT) {
                stop();

                if (shift) {
                    this.setTab(0, false, 1);
                } else {
                    this.previousTab();
                }
            } else if (key === KeyCodes.DOWN_ARROW || key === KeyCodes.RIGHT) {
                stop();

                if (shift) {
                    this.setTab(this.tabs.length - 1, false, -1);
                } else {
                    this.nextTab();
                }
            }
        },

        /**
         * Move to next tab.
         */
        nextTab () {
            this.setTab(this.currentTab + 1, false, 1);
        },

        /**
         * Move to previous tab
         */
        previousTab () {
            this.setTab(this.currentTab - 1, false, -1);
        },

        /**
         * Set active tab on the tabs collection and the child 'tab' component
         * Index is the tab we want to activate. Direction is the direction we are moving
         * so if the tab we requested is disabled, we can skip over it.
         * Force is used by updateTabs to ensure we have cleared any previous active tabs.
         *
         * @param {number|string} index
         * @param {boolean} force
         * @param {number} direction
         */
        setTab (index, force, direction) {
            direction = this.sign(direction || 0);

            if (typeof index === 'string') {
                const id = index;
                index = this.findTabIndex(id);

                if (index < 0) {
                    return warn(`Unable to locate tab with id: ${id}`);
                }
            }

            index = index || 0;

            // Prevent setting same tab and infinite loops!
            if (! force && index === this.currentTab) {
                return;
            }

            const tab = this.tabs[index];

            // Don't go beyond indexes!
            if (! tab) {
                // Reset the v-model to the current Tab
                this.$emit('input', this.currentTab);
                return;
            }

            // Ignore or Skip disabled
            if (tab.disabled) {
                if (direction) {
                    // Skip to next non disabled tab in specified direction (recursive)
                    this.setTab(index + direction, force, direction);
                }

                return;
            }

            // Activate requested current tab, and deactivate any old tabs
            this.tabs.forEach(t => {
                if (t === tab) {
                    // Set new tab as active
                    this.$set(t, 'localActive', true);
                } else {
                    // Ensure non current tabs are not active
                    this.$set(t, 'localActive', false);
                }
            });

            // Update currentTab
            this.currentTab = index;
        },

        /**
         * Dynamically update tabs list
         */
        updateTabs () {
            // Probe tabs
            this.tabs = this.$children.filter(child => child._isTab);

            // Set initial active tab
            let tabIndex = null;

            // Find *last* active non-disabled tab in current tabs
            // We trust tab state over currentTab
            this.tabs.forEach((tab, index) => {
                if (tab.localActive && ! tab.disabled) {
                    tabIndex = index;
                }
            });

            if (tabIndex === null && this.valueIsString) {
                const index = this.findTabIndex(this.value);
                tabIndex = index > -1 ? index : 0;
            }

            // Else try setting to currentTab
            if (tabIndex === null) {
                if (this.currentTab >= this.tabs.length) {
                    // Handle last tab being removed
                    this.setTab(this.tabs.length - 1, true, -1);

                    return;
                } else if (this.tabs[this.currentTab] && ! this.tabs[this.currentTab].disabled) {
                    tabIndex = this.currentTab;
                }
            }

            // Else find *first* non-disabled tab in current tabs
            if (tabIndex === null) {
                this.tabs.forEach((tab, index) => {
                    if (! tab.disabled && tabIndex === null) {
                        tabIndex = index;
                    }
                });
            }

            this.setTab(tabIndex || 0, true, 0);
        }
    },

    watch: {
        currentTab (value, oldValue) {
            if (value === oldValue) {
                return;
            }

            let valueToEmit = value;

            if (this.valueIsString) {
                const id = this.tabs[value].id;
                valueToEmit = id ? id : '';
            }

            this.$root.$emit('changed::tab', this, value, this.tabs[value]);
            this.$emit('input', valueToEmit);
            this.tabs[value].$emit('click');
        },

        value (value, oldValue) {
            if (value === oldValue) {
                return;
            }

            if (typeof oldValue !== 'number') {
                if (typeof oldValue === 'string') {
                    const index = this.findTabIndex(oldValue);
                    oldValue = index < 0 ? 0 : index;
                } else {
                    oldValue = 0;
                }
            }

            if (typeof value === 'string') {
                const index = this.findTabIndex(value);
                value = index < 0 ? 0 : index;
            }

            // Moving left or right?
            const direction = value < oldValue ? -1 : 1;
            this.setTab(value, false, direction);
        }
    }
};