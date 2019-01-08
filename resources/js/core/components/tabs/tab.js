import idMixin from '../../mixins/component/id';

export default {
    mixins: [idMixin],

    props: {
        active: {
            type: Boolean,
            default: false
        },
        buttonId: {
            type: String,
            default: ''
        },
        disabled: {
            type: Boolean,
            default: false
        },
        headHtml: {
            type: String,
            default: null
        },
        href: {
            type: String,
            default: '#'
        },
        id: {
            type: String,
            default: null
        },
        noBody: {
            type: Boolean,
            default: false
        },
        tag: {
            type: String,
            default: 'div'
        },
        title: {
            type: String,
            default: ''
        },
        titleItemClass: {
            // Sniffed by tabs.vue and added to nav 'li.nav-item'
            type: [String, Array, Object],
            default: null
        },
        titleLinkClass: {
            // Sniffed by tabs.vue and added to nav 'a.nav-link'
            type: [String, Array, Object],
            default: null
        },
    },

    computed: {
        /**
         * Determine if tab should fade.
         *
         * @returns {boolean}
         */
        computedFade () {
            return this.$parent.fade;
        },

        /**
         * Determine if tab is lazy.
         *
         * @returns {boolean}
         */
        computedLazy () {
            return this.$parent.lazy;
        },

        /**
         * Determine the tab controlled by attribute.
         *
         * @returns {string}
         */
        controlledBy () {
            return this.buttonId || this.safeId('__Lara_tab_button__');
        },

        /**
         * Generate the tab CSS classes.
         *
         * @returns {array}
         */
        tabClasses () {
            return [
                'tab-pane',
                this.$parent && this.$parent.card && ! this.noBody ? 'card-body' : '',
                this.show ? 'show' : '',
                this.computedFade ? 'fade' : '',
                this.disabled ? 'disabled' : '',
                this.localActive ? 'active' : ''
            ];
        },

        /**
         * Determine if tab.
         *
         * @returns {boolean}
         * @private
         */
        _isTab () {
            // For parent sniffing of child
            return true;
        }
    },

    data () {
        return {
            localActive: this.active && ! this.disabled,
            show: false
        };
    },

    render (h) {
        let content = h(false);

        if (this.localActive || ! this.computedLazy) {
            content = h(
                this.tag,
                {
                    ref: 'panel',
                    class: this.tabClasses,
                    directives: [{ name: 'show', value: this.localActive }],
                    attrs: {
                        role: 'tabpanel',
                        id: this.safeId(),
                        'aria-hidden': this.localActive ? 'false' : 'true',
                        'aria-expanded': this.localActive ? 'true' : 'false',
                        'aria-lablelledby': this.controlledBy || null
                    }
                },
                [this.$slots.default]
            );
        }

        return h(
            'transition',
            {
                props: { mode: 'out-in' },
                on: {
                    beforeEnter: this.beforeEnter,
                    afterEnter: this.afterEnter,
                    afterLeave: this.afterLeave
                }
            },
            [content]
        );
    },

    mounted () {
        this.show = this.localActive;
    },

    methods: {
        /**
         * Handle after enter.
         */
        afterEnter () {
            this.show = true;
        },

        /**
         * Handle after leave.
         */
        afterLeave () {
            this.show = false;
        },

        /**
         * Handle before enter.
         */
        beforeEnter () {
            this.show = false;
        }
    }
};