import Noty from 'noty';

const defaults = {
    layout: 'topRight',
    theme: 'mint',
    timeout: 5000,
    killer: true,
    animation: {
        open: 'animated pulse',
        close: 'animated bounceOutRight'
    }
};

export class Notify {
    /**
     * Initialize the class.
     *
     * @param {object} options
     */
    constructor (options = {}) {
        this.showIcon = true;

        if ('showIcon' in options && options.showIcon === false) {
            this.showIcon = false;
        }

        this.setOptions(options);

        // Default layout to top center
        this.topCenter();
    }

    /**
     * Set the given option.
     *
     * @param {string} option
     * @param {*} value
     * @returns {Notify}
     */
    setOption (option, value) {
        this.options[option] = value;

        return this;
    }

    /**
     * Set the notification options.
     *
     * @param {object} options
     * @returns {Notify}
     */
    setOptions (options = {}) {
        this.options = Object.assign({}, defaults, options);

        return this;
    }

    /**
     * Show the notification.
     *
     * @param {string} text
     * @param {string} type
     * @param {object} options
     */
    show (text, type = 'info', options = {}) {
        const params = Object.assign({}, this.options, options, {
            type,
            text
        });

        return new Noty(params).show();
    }

    //////////////////////////
    // Display methods
    /////////////////////////

    /**
     * Show an error notification.
     *
     * @param {string} text
     * @param {object} options
     * @param {null|boolean|string} queue Set to false to use global queue
     * @returns {*}
     */
    error (text, options = {}, queue = null) {
        if (queue !== false) {
            queue = queue === null ? 'error' : queue;
            this.onQueue(queue);
        }

        return this.show(this.normalizeText(text, 'error'), 'error', options);
    }

    /**
     * Show an info notification.
     *
     * @param {string} text
     * @param {object} options
     * @param {null|boolean|string} queue Set to false to use global queue
     * @returns {*}
     */
    info (text, options = {}, queue = null) {
        if (queue !== false) {
            queue = queue === null ? 'info' : queue;
            this.onQueue(queue);
        }

        return this.show(this.normalizeText(text, 'info'), 'info', options);
    }

    /**
     * Show a success notification.
     *
     * @param {string} text
     * @param {object} options
     * @param {null|boolean|string} queue Set to false to use global queue
     * @returns {*}
     */
    success (text, options = {}, queue = null) {
        if (queue !== false) {
            queue = queue === null ? 'success' : queue;
            this.onQueue(queue);
        }

        return this.show(this.normalizeText(text, 'success'), 'success', options);
    }

    /**
     * Show a warning notification.
     *
     * @param {string} text
     * @param {object} options
     * @param {null|boolean|string} queue Set to false to use global queue
     * @returns {*}
     */
    warning (text, options = {}, queue = null) {
        if (queue !== false) {
            queue = queue === null ? 'warning' : queue;
            this.onQueue(queue);
        }

        return this.show(this.normalizeText(text, 'warning'), 'warning', options);
    }

    /**
     * Prepend an icon to the given text if allowed.
     *
     * @param {string} text
     * @param {string} type
     * @returns {*}
     */
    normalizeText (text, type) {
        if (! this.showIcon || text.toString().startsWith('<i')) {
            return text;
        }

        let iconClass = '';

        switch (type) {
            case 'info':
                iconClass = 'mdi mdi-information-variant';
                break;
            case 'success':
                iconClass = 'mdi mdi-check';
                break;
            case 'warning':
                iconClass = 'mdi mdi-alert-outline';
                break;
            case 'error':
            default:
                iconClass = 'mdi mdi-alert-circle-outline';
                break;
        }

        return `<i class="${iconClass}"></i> ${text}`;
    }

    //////////////////////////
    // Positioning methods
    /////////////////////////

    /**
     * Display the notification in the bottom center of the window.
     *
     * @param {boolean} useCustomAnimation
     * @returns {Notify}
     */
    bottomCenter (useCustomAnimation = true) {
        if (useCustomAnimation) {
            this.animateIn('slideInUp')
                .animateOut('slideOutDown');
        }

        return this.setOption('layout', 'bottomCenter');
    }

    /**
     * Display the notification in the bottom left of the window.
     *
     * @param {boolean} resetAnimations
     * @returns {Notify}
     */
    bottomLeft (resetAnimations = true) {
        if (resetAnimations) {
            this.resetAnimations();
        }

        return this.setOption('layout', 'bottomLeft');
    }

    /**
     * Display the notification in the bottom right of the window.
     *
     * @param {boolean} resetAnimations
     * @returns {Notify}
     */
    bottomRight (resetAnimations = true) {
        if (resetAnimations) {
            this.resetAnimations();
        }

        return this.setOption('layout', 'bottomRight');
    }

    /**
     * Display the notification in the center of the screen.
     *
     * @param {boolean} resetAnimations
     * @returns {Notify}
     */
    center (resetAnimations = true) {
        if (resetAnimations) {
            this.resetAnimations();
        }

        return this.setOption('layout', 'center');
    }

    /**
     * Display the notification in the center left of the window.
     *
     * @param {boolean} resetAnimations
     * @returns {Notify}
     */
    centerLeft (resetAnimations = true) {
        if (resetAnimations) {
            this.resetAnimations();
        }

        return this.setOption('layout', 'centerLeft');
    }

    /**
     * Display the notification in the center right of the window.
     *
     * @param {boolean} resetAnimations
     * @returns {Notify}
     */
    centerRight (resetAnimations = true) {
        if (resetAnimations) {
            this.resetAnimations();
        }

        return this.setOption('layout', 'centerRight');
    }

    /**
     * Display the notification in the top left of the window.
     *
     * @param {boolean} resetAnimations
     * @returns {Notify}
     */
    topLeft (resetAnimations = true) {
        if (resetAnimations) {
            this.resetAnimations();
        }

        return this.setOption('layout', 'topLeft');
    }

    /**
     * Display the notification in the top center of the window.
     *
     * @returns {Notify}
     */
    topCenter (useCustomAnimation = true) {
        if (useCustomAnimation) {
            this.animateIn('slideInDown')
                .animateOut('slideOutUp');
        }

        return this.setOption('layout', 'topCenter');
    }

    /**
     * Display the notification in the top right of the window.
     *
     * @param {boolean} resetAnimations
     * @returns {Notify}
     */
    topRight (resetAnimations = true) {
        if (resetAnimations) {
            this.resetAnimations();
        }

        return this.setOption('layout', 'topRight');
    }

    //////////////////////////
    // Expiration methods
    /////////////////////////

    permanent () {
        return this.setOption('timeout', null);
    }

    showFor (seconds) {
        return this.setOption('timeout', seconds);
    }

    //////////////////////////
    // Misc methods
    /////////////////////////

    /**
     * Set the opening animation.
     *
     * @param {string} animation
     * @returns {Notify}
     */
    animateIn (animation) {
        let animationOption = Object.assign({}, this.options.animation);

        if (! animationOption) {
            animationOption = defaults.animation;
        }

        animationOption.open = `animated ${animation}`;

        return this.setOption('animation', animationOption);
    }

    /**
     * Set the closing animation.
     *
     * @param {string} animation
     * @returns {Notify}
     */
    animateOut (animation) {
        let animationOption = Object.assign({}, this.options.animation);

        if (! animationOption) {
            animationOption = defaults.animation;
        }

        animationOption.close = `animated ${animation}`;

        return this.setOption('animation', animationOption);
    }

    /**
     * Disable the icon in the notification.
     *
     * @returns {Notify}
     */
    noIcon () {
        this.showIcon = false;

        return this;
    }

    /**
     * Eliminate the progress bar.
     *
     * @returns {Notify}
     */
    noProgressBar () {
        return this.setOption('progressBar', false);
    }

    /**
     * Show notification on the given queue.
     *
     * @param {string} queue
     * @param {boolean} killer
     * @returns {Notify}
     */
    onQueue (queue, killer = true) {
        if (killer) {
            this.setOption('killer', queue);
        }

        return this.setOption('queue', queue);
    }

    /**
     * Show a progress bar for auto close notifications.
     *
     * @returns {Notify}
     */
    progressBar () {
        return this.setOption('progressBar', true);
    }

    /**
     * Reset animations to defaults.
     *
     * @returns {Notify}
     */
    resetAnimations() {
        this.animateIn(defaults.animation.open);
        this.animateOut(defaults.animation.close);

        return this;
    }
}