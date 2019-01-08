import listenOnRootMixin from '../../mixins/component/listenOnRoot';
import { hasClass, reflow } from '../../utils/dom';

// Events we emit on $root
const EVENT_STATE = 'lara::collapse::state';
const EVENT_ACCORDION = 'lara::collapse::accordion';

// Events we listen to on $root
const EVENT_TOGGLE = 'lara::toggle::collapse';

export default {
    mixins: [listenOnRootMixin],

    model: {
        prop: 'visible',
        event: 'input'
    },

    props: {
        accordion: {
            type: String,
            default: null
        },
        id: {
            type: String,
            required: true
        },
        isNav: {
            type: Boolean,
            default: false
        },
        visible: {
            type: Boolean,
            default: false
        },
        tag: {
            type: String,
            default: 'div'
        }
    },

    computed: {
        /**
         * Generate the CSS classes.
         *
         * @returns {object}
         */
        classObject () {
            return {
                'navbar-collapse': this.isNav,
                'collapse': ! this.transitioning,
                'show': this.show && ! this.transitioning
            };
        }
    },

    data () {
        return {
            show: this.visible,
            transitioning: false
        };
    },

    render (h) {
        const content = h(
            this.tag,
            {
                class: this.classObject,
                directives: [{ name: 'show', value: this.show }],
                attrs: { id: this.id || null },
                on: { click: this.clickHandler }
            },
            [this.$slots.default]
        );

        return h(
            'transition',
            {
                props: {
                    enterClass: '',
                    enterActiveClass: 'collapsing',
                    enterToClass: '',
                    leaveClass: '',
                    leaveActiveClass: 'collapsing',
                    leaveToClass: ''
                },
                on: {
                    enter: this.onEnter,
                    afterEnter: this.onAfterEnter,
                    leave: this.onLeave,
                    afterLeave: this.onAfterLeave
                }
            },
            [content]
        );
    },

    created () {
        // Listen for toggle events to open/close us
        this.listenOnRoot(EVENT_TOGGLE, this.handleToggleEvent);

        // Listen to other collapses for accordion events
        this.listenOnRoot(EVENT_ACCORDION, this.handleAccordionEvent);
    },

    mounted () {
        if (this.isNav && typeof document !== 'undefined') {
            // Set up handlers
            window.addEventListener('resize', this.handleResize, false);
            window.addEventListener('orientationchange', this.handleResize, false);
            this.handleResize();
        }

        this.emitState();
    },

    beforeDestroy () {
        if (this.isNav && typeof document !== 'undefined') {
            window.removeEventListener('resize', this.handleResize, false);
            window.removeEventListener('orientationchange', this.handleResize, false);
        }
    },

    methods: {
        /**
         * Click event handler.
         *
         * @param {MouseEvent} event
         */
        clickHandler (event) {
            // If we are in a nav/navbar, close the collapse when non-disabled link clicked
            const el = event.target;

            if (! this.isNav || ! el || getComputedStyle(this.$el).display !== 'block') {
                return;
            }

            if (hasClass(el, 'nav-link') || hasClass(el, 'dropdown-item')) {
                this.show = false;
            }
        },

        /**
         * Handle accordion events.
         *
         * @param openedId
         * @param accordion
         */
        handleAccordionEvent (openedId, accordion) {
            if (! this.accordion || accordion !== this.accordion) {
                return;
            }

            if (openedId === this.id) {
                // Open this collapse if not shown
                if (! this.show) {
                    this.toggle();
                }
            } else {
                // Close this collapse if shown
                if (this.show) {
                    this.toggle();
                }
            }
        },

        /**
         * Handle resize.
         */
        handleResize () {
            // Handler for orientation/resize to set collapsed state in nav/navbar
            this.show = (getComputedStyle(this.$el).display === 'block');
        },

        /**
         * Handle the toggle event.
         *
         * @param {HTMLElement} target
         */
        handleToggleEvent (target) {
            if (target !== this.id) {
                return;
            }

            this.toggle();
        },

        /**
         * Emit state of collapse.
         */
        emitState () {
            this.$emit('input', this.show);

            // Let v-b-toggle know the state of this collapse
            this.$root.$emit(EVENT_STATE, this.id, this.show);

            if (this.accordion && this.show) {
                // Tell the other collapses in this accordion to close
                this.$root.$emit(EVENT_ACCORDION, this.id, this.accordion);
            }
        },

        /**
         * Handle on after enter.
         *
         * @param {HTMLElement} el
         */
        onAfterEnter (el) {
            el.style.height = null;
            this.transitioning = false;
            this.$emit('shown');
        },

        /**
         * Handle on after leave.
         *
         * @param {HTMLElement} el
         */
        onAfterLeave (el) {
            el.style.height = null;
            this.transitioning = false;
            this.$emit('hidden');
        },

        /**
         * Handle on enter.
         *
         * @param {HTMLElement} el
         */
        onEnter (el) {
            el.style.height = 0;
            reflow(el);

            el.style.height = el.scrollHeight + 'px';
            this.transitioning = true;

            // This should be moved out so we can add cancellable events
            this.$emit('show');
        },

        /**
         * Handle on leave.
         *
         * @param {HTMLElement} el
         */
        onLeave (el) {
            el.style.height = 'auto';
            el.style.display = 'block';
            el.style.height = el.getBoundingClientRect().height + 'px';

            reflow(el);

            this.transitioning = true;
            el.style.height = 0;

            // This should be moved out so we can add cancellable events
            this.$emit('hide');
        },

        /**
         * Toggle the collapse content.
         */
        toggle () {
            this.show = ! this.show;
        }
    },

    watch: {
        show (newVal, oldVal) {
            if (newVal !== oldVal) {
                this.emitState();
            }
        },

        visible (newVal) {
            if (newVal !== this.show) {
                this.show = newVal;
            }
        }
    }
};