import idMixin from '../../mixins/component/id';
import dropdownMixin from '../../mixins/component/dropdown';
import bButton from '../button/button';

export default {
    mixins: [idMixin, dropdownMixin],

    components: { bButton },

    props: {
        animation: {
            type: String,
            default: null
        },
        boundary: {
            // String: `scrollParent`, `window` or `viewport`
            // Object: HTML Element reference
            type: [String, Object],
            default: 'scrollParent'
        },
        menuClass: {
            type: [String, Array],
            default: null
        },
        noCaret: {
            type: Boolean,
            default: false
        },
        role: {
            type: String,
            default: 'menu'
        },
        size: {
            type: String,
            default: null
        },
        split: {
            type: Boolean,
            default: false
        },
        tag: {
            type: String,
            default: 'div'
        },
        toggleClass: {
            type: [String, Array],
            default: null
        },
        toggleText: {
            type: String,
            default: 'Toggle Dropdown'
        },
        variant: {
            type: String,
            default: null
        }
    },

    computed: {
        /**
         * Generate the dropdown CSS classes.
         *
         * @returns {array}
         */
        dropdownClasses () {
            let position = '';
            // Position `static` is needed to allow menu to "breakout" of the scrollParent boundaries
            // when boundary is anything other than `scrollParent`
            // See https://github.com/twbs/bootstrap/issues/24251#issuecomment-341413786
            if (this.boundary !== 'scrollParent' || ! this.boundary) {
                position = 'position-static';
            }

            return [
                'btn-group',
                'b-dropdown',
                'dropdown',
                this.dropup ? 'dropup' : '',
                this.visible ? 'show' : '',
                position
            ];
        },

        /**
         * Generate the menu CSS classes.
         *
         * @returns {array}
         */
        menuClasses () {
            return [
                'dropdown-menu',
                {
                    'dropdown-menu-right': this.right,
                    'show': this.visible
                },
                this.animation,
                this.menuClass
            ];
        },

        /**
         * Generate the toggle CSS classes.
         *
         * @returns {array}
         */
        toggleClasses () {
            return [
                {
                    'dropdown-toggle': ! this.noCaret || this.split,
                    'dropdown-toggle-split': this.split
                },
                this.toggleClass
            ];
        }
    },

    render (h) {
        let split = h(false);

        if (this.split) {
            split = h(
                'b-button',
                {
                    ref: 'button',
                    props: {
                        disabled: this.disabled,
                        variant: this.variant,
                        size: this.size
                    },
                    attrs: {
                        id: this.safeId('_Lara_button_')
                    },
                    on: {
                        click: this.click
                    }
                },
                [this.$slots['button-content'] || this.$slots.text || this.text]
            );
        }

        const toggle = h(
            'b-button',
            {
                ref: 'toggle',
                class: this.toggleClasses,
                props: {
                    variant: this.variant,
                    size: this.size,
                    disabled: this.disabled
                },
                attrs: {
                    id: this.safeId('_Lara_toggle_'),
                    'aria-haspopup': 'true',
                    'aria-expanded': this.visible ? 'true' : 'false'
                },
                on: {
                    click: this.toggle,
                    keydown: this.toggle // enter, space, down
                }
            },
            [
                this.split
                    ? h('span', { class: ['sr-only'] }, [this.toggleText])
                    : this.$slots['button-content'] || this.$slots.text || this.text
            ]
        );

        const menu = h(
            'div',
            {
                ref: 'menu',
                class: this.menuClasses,
                attrs: {
                    role: this.role,
                    'aria-labelledby': this.safeId(this.split ? '_Lara_button_' : '_Lara_toggle_')
                },
                on: {
                    mouseover: this.onMouseOver,
                    keydown: this.onKeydown // tab, up, down, esc
                }
            },
            [this.$slots.default]
        );

        return h(
            this.tag,
            {
                attrs: { id: this.safeId() },
                class: this.dropdownClasses
            },
            [split, toggle, menu]
        );
    }
};