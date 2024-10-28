const copyableText = ({ text, copiedTooltip = 'Copied!', tooltip = 'Copy' }) => ({
    text,
    copiedTooltip,
    tooltip,
    currentTooltip: undefined,
    copyTimeout: undefined,
    keypressTimeout: undefined,

    init() {
        this.currentTooltip = this.tooltip;
    },

    clearCopyTimeouts() {
        if (this.copyTimeout) {
            clearTimeout(this.copyTimeout);
        }

        if (this.keypressTimeout) {
            clearTimeout(this.keypressTimeout);
        }
    },

    copy() {
        this.clearCopyTimeouts();

        this.currentTooltip = this.copiedTooltip;
        this.$clipboard(this.text);

        this.copyTimeout = setTimeout(() => {
            this.currentTooltip = this.tooltip;
        }, 2500);
    },

    getTooltipConfig() {
        return {
            content: this.currentTooltip,
            theme: this.$store.theme,
            arrow: false,
            duration: 500,
            hideOnClick: false,
            onHide: () => {
                this.$el.blur();
            },
            onHidden: () => {
                this.clearCopyTimeouts();
                this.currentTooltip = this.tooltip;
            },
        };
    },

    onKeypress() {
        this.keypressTimeout = setTimeout(() => {
            this.$el.blur();
        }, 2500);
    },

    events: {
        ['@click']() {
            this.copy();
        },

        ['@keydown.space.prevent.stop']() {
            this.copy();
            this.onKeypress();
        },

        ['@keydown.enter.prevent.stop']() {
            this.copy();
            this.onKeypress();
        },
    },
});

export { copyableText };
