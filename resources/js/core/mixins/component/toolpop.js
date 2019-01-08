/**
 * Tooltip/Popover component mixin
 * Common props
 */
import { isArray } from '../../utils/array';
import { assign } from '../../utils/object';
import { isElement, getById } from '../../utils/dom';
import { HTMLElement } from '../../utils/ssr';
import observeDom from '../../utils/observeDom';

const PLACEMENTS = {
    top: 'top',
    topleft: 'topleft',
    topright: 'topright',
    right: 'right',
    righttop: 'righttop',
    rightbottom: 'rightbottom',
    bottom: 'bottom',
    bottomleft: 'bottomleft',
    bottomright: 'bottomright',
    left: 'left',
    lefttop: 'lefttop',
    leftbottom: 'leftbottom',
    auto: 'auto'
};

const OBSERVER_CONFIG = {
    subtree: true,
    childList: true,
    characterData: true,
    attributes: true,
    attributeFilter: ['class', 'style']
};

export default {
    props: {
        target: {
            // String ID of element, or element/component reference
            type: [String, Object, HTMLElement, Function]
        },
        delay: {
            type: [Number, Object, String],
            default: 0
        },
        offsetTop: {
            type: [Number, String],
            default: 0
        },
        noFade: {
            type: Boolean,
            default: false
        },
        container: {
            // String ID of container, if null body is used (default)
            type: String,
            default: null
        },
        boundary: {
            // String: scrollParent, window, or viewport
            // Element: element reference
            type: [String, Object],
            default: 'scrollParent'
        },
        show: {
            type: Boolean,
            default: false
        },
        disabled: {
            type: Boolean,
            default: false
        }
    },

    computed: {
        /**
         * Generate a base configuration object for tooltip/popover.
         *
         * @returns {object}
         */
        baseConfig () {
            const container = this.container;
            let delay = (typeof this.delay === 'object') ? this.delay : (parseInt(this.delay, 10) || 0);

            return {
                title: (this.title || '').trim() || '',
                content: (this.content || '').trim() || '',
                placement: PLACEMENTS[this.placement] || 'auto',
                // Container currently needs to be an ID with '#' prepended, if null then body is used
                container: container ? (/^#/.test(container) ? container : `#${container}`) : false,
                // boundariesElement passed to popper
                boundary: this.boundary,
                // Show/Hide delay
                delay: delay || 0,
                // Offset can be css distance. if no units, pixels are assumed
                offsetTop: this.offsetTop || 0,
                animation: ! this.noFade,
                trigger: isArray(this.triggers) ? this.triggers.join(' ') : this.triggers,
                callbacks: {
                    show: this.onShow,
                    shown: this.onShown,
                    hide: this.onHide,
                    hidden: this.onHidden,
                    enabled: this.onEnabled,
                    disabled: this.onDisabled
                }
            };
        }
    },

    created () {
        // Create non-reactive property
        this._toolpop = null;
        this._obs_title = null;
        this._obs_content = null;
    },

    mounted () {
        // We do this in a next tick to ensure DOM has rendered first
        this.$nextTick(() => {
            // Instantiate ToolTip/PopOver on target
            // The createToolpop method must exist in main component
            if (this.createToolpop()) {
                if (this.disabled) {
                    // Initially disabled
                    this.onDisable();
                }

                // Listen to open signals from others
                this.$on('open', this.onOpen);

                // Listen to close signals from others
                this.$on('close', this.onClose);

                // Listen to disable signals from others
                this.$on('disable', this.onDisable);

                // Listen to disable signals from others
                this.$on('enable', this.onEnable);

                // Observe content Child changes so we can notify popper of possible size change
                this.setObservers(true);

                // Set initially open state
                if (this.show) {
                    this.onOpen();
                }
            }
        });
    },

    updated () {
        // If content/props changes, etc
        if (this._toolpop) {
            this._toolpop.updateConfig(this.getConfig());
        }
    },

    activated () {
        // Called when component is inside a <keep-alive> and component brought online
        this.setObservers(true);
    },

    deactivated () {
        // Called when component is inside a <keep-alive> and component taken offline
        if (this._toolpop) {
            this.setObservers(false);
            this._toolpop.hide();
        }
    },

    beforeDestroy () {
        // Shutdown our local event listeners
        this.$off('open', this.onOpen);
        this.$off('close', this.onClose);
        this.$off('disable', this.onDisable);
        this.$off('enable', this.onEnable);

        this.setObservers(false);

        // bring our content back if needed
        this.bringItBack();
        if (this._toolpop) {
            this._toolpop.destroy();
            this._toolpop = null;
        }
    },

    methods: {
        /**
         * Bring content back if needed to keep Vue happy.
         */
        bringItBack () {
            if (this.$el && this.$refs.title) {
                this.$el.appendChild(this.$refs.title);
            }

            if (this.$el && this.$refs.content) {
                this.$el.appendChild(this.$refs.content);
            }
        },

        /**
         * Get tooltip/popover configuration object.
         *
         * @returns {object}
         */
        getConfig () {
            const cfg = assign({}, this.baseConfig);

            if (this.$refs.title && this.$refs.title.innerHTML.trim()) {
                // If slot has content, it overrides 'title' prop
                // We use the DOM node as content to allow components!
                cfg.title = this.$refs.title;
                cfg.html = true;
            }

            if (this.$refs.content && this.$refs.content.innerHTML.trim()) {
                // If slot has content, it overrides 'content' prop
                // We use the DOM node as content to allow components!
                cfg.content = this.$refs.content;
                cfg.html = true;
            }

            return cfg;
        },

        /**
         * Get tooltip/popover target.
         *
         * @returns {*}
         */
        getTarget () {
            let target = this.target;

            if (typeof target === 'function') {
                target = target();
            }

            if (typeof target === 'string') {
                // Assume ID of element
                return getById(target);
            } else if (typeof target === 'object' && isElement(target.$el)) {
                // Component reference
                return target.$el;
            } else if (typeof target === 'object' && isElement(target)) {
                // Element reference
                return target;
            }

            return null;
        },

        /**
         * Handle close event.
         *
         * @param {function} callback
         */
        onClose (callback) {
            if (this._toolpop) {
                this._toolpop.hide(callback);
            } else if (typeof callback === 'function') {
                callback();
            }
        },

        /**
         * Handle disable event.
         */
        onDisable () {
            if (this._toolpop) {
                this._toolpop.disable();
            }
        },

        /**
         * Handle disabled event.
         *
         * @param {Event} event
         */
        onDisabled (event) {
            if (! event || event.type !== 'disabled') {
                // Prevent possible endless loop if user mistakenly fires disabled instead of disable
                return;
            }
            this.$emit('update:disabled', true);
            this.$emit('enabled');
        },

        /**
         * Handle enable event.
         */
        onEnable () {
            if (this._toolpop) {
                this._toolpop.enable();
            }
        },

        /**
         * Handle enabled event.
         *
         * @param {Event} event
         */
        onEnabled (event) {
            if (! event || event.type !== 'enabled') {
                // Prevent possible endless loop if user mistakenly fires enabled instead of enable
                return;
            }

            this.$emit('update:disabled', false);
            this.$emit('disabled');
        },

        /**
         * Handle hide event.
         *
         * @param {Event} event
         */
        onHide (event) {
            this.$emit('hide', event);
        },

        /**
         * Handle hidden event.
         *
         * @param {Event} event
         */
        onHidden (event) {
            this.setObservers(false);
            // bring our content back if needed to keep Vue happy
            // Tooltip class will move it back to tip when shown again
            this.bringItBack();
            this.$emit('update:show', false);
            this.$emit('hidden', event);
        },

        /**
         * Handle open event.
         */
        onOpen () {
            if (this._toolpop) {
                this._toolpop.show();
            }
        },

        /**
         * Handle shown event.
         *
         * @param {Event} event
         */
        onShow (event) {
            this.$emit('show', event);
        },

        /**
         * Handle shown event.
         *
         * @param {Event} event
         */
        onShown (event) {
            this.setObservers(true);
            this.$emit('update:show', true);
            this.$emit('shown', event);
        },

        /**
         * Set element observers.
         *
         * @param {boolean} on
         */
        setObservers (on) {
            if (on) {
                if (this.$refs.title) {
                    this._obs_title = observeDom(this.$refs.title, this.updatePosition.bind(this), OBSERVER_CONFIG);
                }
                if (this.$refs.content) {
                    this._obs_content = observeDom(this.$refs.content, this.updatePosition.bind(this), OBSERVER_CONFIG);
                }
            } else {
                if (this._obs_title) {
                    this._obs_title.disconnect();
                    this._obs_title = null;
                }
                if (this._obs_content) {
                    this._obs_content.disconnect();
                    this._obs_content = null;
                }
            }
        },

        /**
         * Update tooltip/popover position.
         */
        updatePosition () {
            if (this._toolpop) {
                // Instruct popper to reposition popover if necessary
                this._toolpop.update();
            }
        }
    },

    watch: {
        disabled (disabled, old) {
            if (disabled === old) {
                return;
            }

            disabled ? this.onDisable() : this.onEnable();
        },

        show (show, old) {
            if (show === old) {
                return;
            }

            show ? this.onOpen() : this.onClose();
        }
    }
};