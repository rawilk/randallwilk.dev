import ToolTip from '../../utils/class/tooltip.class';
import { warn } from '../../utils/warn';
import toolpopMixin from '../../mixins/component/toolpop';

export default {
    mixins: [toolpopMixin],

    props: {
        title: {
            type: String,
            default: ''
        },
        triggers: {
            type: [String, Array],
            default: 'hover focus'
        },
        placement: {
            type: String,
            default: 'top'
        }
    },

    render (h) {
        return h(
            'div',
            { class: ['d-none'], style: { display: 'none' }, attrs: { 'aria-hidden': true } },
            [h('div', { ref: 'title' }, this.$slots.default)]
        );
    },

    methods: {
        /**
         * Create the tooltip.
         *
         * @returns {ToolTip|null}
         */
        createToolpop () {
            // getTarget is in toolpop mixin
            const target = this.getTarget();

            if (target) {
                this._toolpop = new ToolTip(target, this.getConfig(), this.$root);
            } else {
                this._toolpop = null;
                warn('b-tooltip: \'target\' element not found!');
            }

            return this._toolpop;
        }
    }
};