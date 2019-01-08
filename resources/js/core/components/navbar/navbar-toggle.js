import listenOnRootMixin from '../../mixins/component/listenOnRoot';

export default {
    mixins: [listenOnRootMixin],

    props: {
        label: {
            type: String,
            default: 'Toggle navigation'
        },
        target: {
            type: String,
            required: true
        }
    },

    data () {
        return {
            toggleState: false
        };
    },

    render (h) {
        return h(
            'button',
            {
                class: ['navbar-toggler'],
                attrs: {
                    type: 'button',
                    'aria-label': this.label,
                    'aria-controls': this.target,
                    'aria-expanded': this.toggleState ? 'true' : 'false'
                },
                on: { click: this.onClick }
            },
            [this.$slots.default || h('span', { class: ['navbar-toggler-icon'] })]
        );
    },

    created () {
        this.listenOnRoot('lara::collapse::state', this.handleStateEvent);
    },

    methods: {
        /**
         * Handle state changed event.
         *
         * @param {string} id
         * @param {boolean} state
         */
        handleStateEvent (id, state) {
            if (id === this.target) {
                this.toggleState = state;
            }
        },

        /**
         * Handle toggle click.
         */
        onClick () {
            this.$root.$emit('lara::toggle::collapse', this.target);
        }
    }
};