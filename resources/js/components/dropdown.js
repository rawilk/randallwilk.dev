/*
 * Dropdown Component Definition.
 */

import findLastIndex from '../../../vendor/rawilk/laravel-form-components/resources/js/util/findLastIndex';

export default function dropdown(options) {
    return {
        open: false,
        posX: '',
        posY: '',
        fixed: false,
        currentIndex: -1,
        activeChildren: [],
        ...options,

        get menuStyle() {
            const style = {};

            if (this.fixed) {
                style.top = `${this.posY}px`;
                style.right = `${this.posX}px`;
            }

            if (! this.open) {
                style.display = 'none';
            }

            let styleString = '';

            Object.keys(style).forEach(key => {
                styleString += `${key}: ${style[key]};`;
            });

            return styleString;
        },

        init() {

        },

        toggleMenu() {
            this.currentIndex = -1;
            this.open = ! this.open;

            if (this.open) {
                this.activeChildren = this.getActiveChildren();
            }

            this.updatePosition();
        },

        updatePosition() {
            if (! this.open || ! this.fixed) {
                return;
            }

            const trigger = this.$refs.trigger.childNodes[1] || this.$refs.trigger;
            const width = window.innerWidth;

            const { bottom, right } = trigger.getBoundingClientRect();

            this.posX = width - right;
            this.posY = bottom;
        },

        getActiveChildren() {
            const findChildren = parent => {
                let children = [];

                for (let child of parent.children) {
                    if (child.hasAttribute('disabled')) {
                        continue;
                    }

                    if (child.hasAttribute('role') && child.getAttribute('role') === 'menuitem') {
                        children.push(child);
                    } else if (child.children && child.children.length) {
                        children = children.concat(findChildren(child));
                    }
                }

                return children;
            };

            return findChildren(this.$refs.menu);
        },

        onArrowUp() {
            if (! this.activeChildren.length) {
                this.currentIndex = -1;

                return;
            }

            let prevIndex = findLastIndex(this.activeChildren, (c, index) => index < this.currentIndex);
            if (prevIndex < 0) {
                prevIndex = this.activeChildren.length - 1;
            }

            this.currentIndex = prevIndex;
            this.focusChild(this.activeChildren[this.currentIndex]);
        },

        onArrowDown() {
            if (! this.activeChildren.length) {
                this.currentIndex = -1;

                return;
            }

            let nextIndex = this.activeChildren.findIndex((c, index) => index > this.currentIndex);
            if (nextIndex === -1 || (nextIndex + 1) > this.activeChildren.length) {
                nextIndex = 0;
            }

            this.currentIndex = nextIndex;
            this.focusChild(this.activeChildren[this.currentIndex]);
        },

        onEnd() {
            if (! this.activeChildren.length) {
                return;
            }

            this.focusChild(this.activeChildren[this.activeChildren.length - 1]);
        },

        onHome() {
            if (! this.activeChildren.length) {
                return;
            }

            this.focusChild(this.activeChildren[0]);
        },

        focusChild(child) {
            if (! child || ! child.focus) {
                return;
            }

            child.focus();
        },

    };
};
