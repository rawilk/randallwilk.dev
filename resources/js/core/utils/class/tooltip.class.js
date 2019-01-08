import Popper from 'popper.js';
import VueLaravelEvent from './vue-laravel-event.class';
import { assign } from '../object';
import { arrayFrom } from '../array';
import {
    closest,
    select,
    isVisible,
    isDisabled,
    getCS,
    addClass,
    removeClass,
    hasClass,
    setAttr,
    removeAttr,
    getAttr,
    eventOn,
    eventOff
} from '../dom';

const NAME = 'tooltip';
const CLASS_PREFIX = 'bs-tooltip';
const BSCLS_PREFIX_REGEX = new RegExp(`\\b${CLASS_PREFIX}\\S+`, 'g');

const TRANSITION_DURATION = 150;

// Modal $root hidden event
const MODAL_CLOSE_EVENT = 'lara::modal::hidden';

// Modal container for appending tip/popover
const MODAL_CLASS = '.modal-content';

const AttachmentMap = {
    AUTO: 'auto',
    TOP: 'top',
    RIGHT: 'right',
    BOTTOM: 'bottom',
    LEFT: 'left',
    TOPLEFT: 'top',
    TOPRIGHT: 'top',
    RIGHTTOP: 'right',
    RIGHTBOTTOM: 'right',
    BOTTOMLEFT: 'bottom',
    BOTTOMRIGHT: 'bottom',
    LEFTTOP: 'left',
    LEFTBOTTOM: 'left'
};

const OffsetMap = {
    AUTO: 0,
    TOPLEFT: -1,
    TOP: 0,
    TOPRIGHT: +1,
    RIGHTTOP: -1,
    RIGHT: 0,
    RIGHTBOTTOM: +1,
    BOTTOMLEFT: -1,
    BOTTOM: 0,
    BOTTOMRIGHT: +1,
    LEFTTOP: -1,
    LEFT: 0,
    LEFTBOTTOM: +1
};

const HoverState = {
    SHOW: 'show',
    OUT: 'out'
};

const ClassName = {
    FADE: 'fade',
    SHOW: 'show'
};

const Selector = {
    TOOLTIP: '.tooltip',
    TOOLTIP_INNER: '.tooltip-inner',
    ARROW: '.arrow'
};

const Defaults = {
    animation: true,
    template: '<div class="tooltip" role="tooltip">' +
        '<div class="arrow"></div>' +
        '<div class="tooltip-inner"></div>' +
        '</div>',
    trigger: 'hover focus',
    title: '',
    delay: 0,
    html: false,
    placement: 'top',
    offsetTop: 0,
    arrowPadding: 6,
    container: false,
    fallbackPlacement: 'flip',
    callbacks: {},
    boundary: 'scrollParent'
};

// Transition Event names
const TransitionEndEvents = {
    WebkitTransition: ['webkitTransitionEnd'],
    MozTransition: ['transitionend'],
    OTransition: ['otransitionend', 'oTransitionEnd'],
    transition: ['transitionend']
};

// Client Side Tip ID counter for aria-describedby attribute
// Each tooltip requires a unique client side ID
let NEXTID = 1;

function generateId (name) {
    return `__LARA_${name}_${NEXTID++}__`;
}

/*
 * ToolTip Class definition
 */
class ToolTip {
    /**
     * Initialize class properties.
     *
     * @param element
     * @param config
     * @param $root
     */
    constructor (element, config, $root) {
        // New tooltip object
        this.$isEnabled = true;
        this.$fadeTimeout = null;
        this.$hoverTimeout = null;
        this.$visibleInterval = null;
        this.$hoverState = '';
        this.$activeTrigger = {};
        this.$popper = null;
        this.$element = element;
        this.$tip = null;
        this.$id = generateId(this.constructor.NAME);
        this.$root = $root || null;
        this.$routeWatcher = null;

        // We use a bound version of the following handlers for root/modal listeners to maintain the 'this' context
        this.$forceHide = this.forceHide.bind(this);
        this.$doHide = this.doHide.bind(this);
        this.$doShow = this.doShow.bind(this);
        this.$doDisable = this.doDisable.bind(this);
        this.$doEnable = this.doEnable.bind(this);

        // Set the configuration
        this.updateConfig(config);
    }

    // NOTE: Overridden by PopOver class
    static get Default () {
        return Defaults;
    }

    // NOTE: Overridden by PopOver class
    static get NAME () {
        return NAME;
    }

    /**
     * Update config.
     *
     * @param {object} config
     */
    updateConfig (config) {
        // Merge config into defaults. We use "this" here because PopOver overrides Default
        let updatedConfig = assign({}, this.constructor.Default, config);

        // Sanitize delay
        if (config.delay && typeof config.delay === 'number') {
            updatedConfig.delay = {
                show: config.delay,
                hide: config.delay
            };
        }

        // Title for tooltip and popover
        if (config.title && typeof config.title === 'number') {
            updatedConfig.title = config.title.toString();
        }

        // Content only for popover
        if (config.content && typeof config.content === 'number') {
            updatedConfig.content = config.content.toString();
        }

        // Hide element original title if needed
        this.fixTitle();

        // Update the config
        this.$config = updatedConfig;

        // Stop/Restart listening
        this.unListen();
        this.listen();
    }

    /**
     * Destroy this instance.
     */
    destroy () {
        // Stop listening to trigger events
        this.unListen();

        // Disable while open listeners/watchers
        this.setWhileOpenListeners(false);

        // Clear any timeouts
        clearTimeout(this.$hoverTimeout);
        this.$hoverTimeout = null;

        clearTimeout(this.$fadeTimeout);
        this.$fadeTimeout = null;

        // Remove popper
        if (this.$popper) {
            this.$popper.destroy();
        }

        this.$popper = null;

        // Remove tip from document
        if (this.$tip && this.$tip.parentElement) {
            this.$tip.parentElement.removeChild(this.$tip);
        }

        this.$tip = null;

        // Null out other properties
        this.$id = null;
        this.$isEnabled = null;
        this.$root = null;
        this.$element = null;
        this.$config = null;
        this.$hoverState = null;
        this.$activeTrigger = null;
        this.$forceHide = null;
        this.$doHide = null;
        this.$doShow = null;
        this.$doDisable = null;
        this.$doEnable = null;
    }

    /**
     * Enable this instance.
     */
    enable () {
        // Create a non-cancelable VueLaravelEvent
        const enabledEvent = new VueLaravelEvent('enabled', {
            cancelable: false,
            target: this.$element,
            relatedTarget: null
        });

        this.$isEnabled = true;
        this.emitEvent(enabledEvent);
    }

    /**
     * Disable this instance.
     */
    disable () {
        // Create a non-cancelable BvEvent
        const disabledEvent = new VueLaravelEvent('disabled', {
            cancelable: false,
            target: this.$element,
            relatedTarget: null
        });

        this.$isEnabled = false;
        this.emitEvent(disabledEvent);
    }

    /**
     * Click event toggler.
     *
     * @param {MouseEvent} event
     */
    toggle (event) {
        if (! this.$isEnabled) {
            return;
        }

        if (event) {
            this.$activeTrigger.click = ! this.$activeTrigger.click;

            if (this.isWithActiveTrigger()) {
                this.enter(null);
            } else {
                this.leave(null);
            }
        } else {
            if (hasClass(this.getTipElement(), ClassName.SHOW)) {
                this.leave(null);
            } else {
                this.enter(null);
            }
        }
    }

    /**
     * Show the tooltip.
     */
    show () {
        if (! document.body.contains(this.$element) || ! isVisible(this.$element)) {
            // If trigger element isn't in the DOM or is not visible, do nothing.
            return;
        }

        // Build tooltip element (also sets this.$tip)
        const tip = this.getTipElement();
        this.fixTitle();
        this.setContent(tip);

        if (! this.isWithContent(tip)) {
            // if No content, don't bother showing
            this.$tip = null;
            return;
        }

        // Set ID on tip and aria-describedby on element
        setAttr(tip, 'id', this.$id);
        this.addAriaDescribedby();

        // Set animation on or off
        if (this.$config.animation) {
            addClass(tip, ClassName.FADE);
        } else {
            removeClass(tip, ClassName.FADE);
        }

        const placement = this.getPlacement();
        const attachment = this.constructor.getAttachment(placement);
        this.addAttachmentClass(attachment);

        // Create a cancelable VueLaravelEvent
        const showEvent = new VueLaravelEvent('show', {
            cancelable: true,
            target: this.$element,
            relatedTarget: tip
        });

        this.emitEvent(showEvent);
        if (showEvent.defaultPrevented) {
            // Don't show if event cancelled
            this.$tip = null;
            return;
        }

        // Insert tooltip if needed
        const container = this.getContainer();
        if (! document.body.contains(tip)) {
            container.appendChild(tip);
        }

        // Refresh popper
        this.removePopper();
        this.$popper = new Popper(this.$element, tip, this.getPopperConfig(placement, tip));

        // Transitionend Callback
        const complete = () => {
            if (this.$config.animation) {
                this.fixTransition(tip);
            }

            const prevHoverState = this.$hoverState;
            this.$hoverState = null;

            if (prevHoverState === HoverState.OUT) {
                this.leave(null);
            }

            // Create a non-cancelable VueLaravelEvent
            const shownEvent = new VueLaravelEvent('shown', {
                cancelable: false,
                target: this.$element,
                relatedTarget: tip
            });

            this.emitEvent(shownEvent);
        };

        // Enable while open listeners/watchers
        this.setWhileOpenListeners(true);

        // Show tip
        addClass(tip, ClassName.SHOW);

        // Start the transition/animation
        this.transitionOnce(tip, complete);
    }

    /**
     * Handler for periodic visibility check.
     *
     * @param {boolean} on
     */
    visibleCheck (on) {
        clearInterval(this.$visibleInterval);
        this.$visibleInterval = null;

        if (on) {
            this.$visibleInterval = setInterval(() => {
                const tip = this.getTipElement();

                if (tip && ! isVisible(this.$element) && hasClass(tip, ClassName.SHOW)) {
                    // Element is no longer visible, so force-hide the tooltip
                    this.forceHide();
                }
            }, 100);
        }
    }

    /**
     * Create and set listeners for when tooltip is open.
     *
     * @param {boolean} on
     */
    setWhileOpenListeners (on) {
        // Modal close events
        this.setModalListener(on);

        // Periodic $element visibility check
        // For handling when tip is in <keepalive>, tabs, carousel, etc
        this.visibleCheck(on);

        // Route change events
        this.setRouteWatcher(on);

        // Ontouch start listeners
        this.setOnTouchStartListener(on);

        if (on && /(focus|blur)/.test(this.$config.trigger)) {
            // If focus moves between trigger element and tip container, don't close
            eventOn(this.$tip, 'focusout', this);
        } else {
            eventOff(this.$tip, 'focusout', this);
        }
    }

    /**
     * Force hide the tooltip (internal method)
     */
    forceHide () {
        if (! this.$tip || ! hasClass(this.$tip, ClassName.SHOW)) {
            return;
        }

        // Disable while open listeners/watchers
        this.setWhileOpenListeners(false);

        // Clear any hover enter/leave event
        clearTimeout(this.$hoverTimeout);
        this.$hoverTimeout = null;
        this.$hoverState = '';

        // Hide the tip
        this.hide(null, true);
    }

    /**
     * Hide the tooltip.
     *
     * @param {function} callback
     * @param {boolean} force
     */
    hide (callback, force) {
        const tip = this.$tip;

        if (! tip) {
            return;
        }

        // Create a cancelable VueLaravelEvent
        const hideEvent = new VueLaravelEvent('hide', {
            // We disable cancelling if force is true
            cancelable: ! force,
            target: this.$element,
            relatedTarget: tip
        });

        this.emitEvent(hideEvent);

        if (hideEvent.defaultPrevented) {
            // Don't hide if event cancelled
            return;
        }

        // Transitionend Callback
        const complete = () => {
            if (this.$hoverState !== HoverState.SHOW && tip.parentNode) {
                // Remove tip from dom, and force recompile on next show
                tip.parentNode.removeChild(tip);
                this.removeAriaDescribedby();
                this.removePopper();
                this.$tip = null;
            }

            if (callback) {
                callback();
            }

            // Create a non-cancelable VueLaravelEvent
            const hiddenEvent = new VueLaravelEvent('hidden', {
                cancelable: false,
                target: this.$element,
                relatedTarget: null
            });

            this.emitEvent(hiddenEvent);
        };

        // Disable while open listeners/watchers
        this.setWhileOpenListeners(false);

        // If forced close, disable animation
        if (force) {
            removeClass(tip, ClassName.FADE);
        }

        // Hide tip
        removeClass(tip, ClassName.SHOW);

        this.$activeTrigger.click = false;
        this.$activeTrigger.focus = false;
        this.$activeTrigger.hover = false;

        // Start the hide transition
        this.transitionOnce(tip, complete);

        this.$hoverState = '';
    }

    /**
     * Emit the given event.
     *
     * @param {VueLaravelEvent} event
     */
    emitEvent (event) {
        const eventName = event.type;

        if (this.$root && this.$root.$emit) {
            // Emit an event on $root
            this.$root.$emit(`lara::${this.constructor.NAME}::${eventName}`, event);
        }

        const callbacks = this.$config.callbacks || {};
        if (typeof callbacks[eventName] === 'function') {
            callbacks[eventName](event);
        }
    }

    /**
     * Get an appropriate container.
     *
     * @returns {*|HTMLElement}
     */
    getContainer () {
        const container = this.$config.container;
        const body = document.body;

        // If we are in a modal, we append to the modal instead of body, unless a container is specified
        return container === false ? (closest(MODAL_CLASS, this.$element) || body) : (select(container, body) || body);
    }

    // Will be overwritten by popover if needed
    addAriaDescribedby () {
        // Add aria-describedby on trigger element, without removing any other IDs
        let desc = getAttr(this.$element, 'aria-describedby') || '';
        desc = desc.split(/\s+/).concat(this.$id).join(' ').trim();
        setAttr(this.$element, 'aria-describedby', desc);
    }

    // Will be overwritten by popover if needed
    removeAriaDescribedby () {
        let desc = getAttr(this.$element, 'aria-describedby') || '';
        desc = desc.split(/\s+/).filter(d => d !== this.$id).join(' ').trim();

        if (desc) {
            setAttr(this.$element, 'aria-describedby', desc);
        } else {
            removeAttr(this.$element, 'aria-describedby');
        }
    }

    /**
     * Remove popper instance.
     */
    removePopper () {
        if (this.$popper) {
            this.$popper.destroy();
        }

        this.$popper = null;
    }

    /**
     * Do transition once.
     *
     * @param tip
     * @param complete
     */
    transitionOnce (tip, complete) {
        const transEvents = this.getTransitionEndEvents();
        let called = false;

        clearTimeout(this.$fadeTimeout);

        this.$fadeTimeout = null;

        const fnOnce = () => {
            if (called) {
                return;
            }

            called = true;

            clearTimeout(this.$fadeTimeout);
            this.$fadeTimeout = null;

            transEvents.forEach(evtName => {
                eventOff(tip, evtName, fnOnce);
            });

            // Call complete callback
            complete();
        };

        if (hasClass(tip, ClassName.FADE)) {
            transEvents.forEach(evtName => {
                eventOn(tip, evtName, fnOnce);
            });

            // Fallback to setTimeout
            this.$fadeTimeout = setTimeout(fnOnce, TRANSITION_DURATION);
        } else {
            fnOnce();
        }
    }

    /**
     * Get transitionend events.
     *
     * @returns {*}
     */
    getTransitionEndEvents () {
        for (const name in TransitionEndEvents) {
            if (this.$element.style[name] !== undefined) {
                return TransitionEndEvents[name];
            }
        }

        // fallback
        return [];
    }

    /**
     * Schedule popper update.
     */
    update () {
        if (this.$popper !== null) {
            this.$popper.scheduleUpdate();
        }
    }

    // NOTE: Overridden by PopOver class
    isWithContent (tip) {
        tip = tip || this.$tip;

        if (! tip) {
            return false;
        }

        return Boolean((select(Selector.TOOLTIP_INNER, tip) || {}).innerHTML);
    }

    // NOTE: Overridden by PopOver class
    addAttachmentClass (attachment) {
        addClass(this.getTipElement(), `${CLASS_PREFIX}-${attachment}`);
    }

    /**
     * Get instance tooltip element.
     *
     * @returns {null|*}
     */
    getTipElement () {
        if (! this.$tip) {
            // Try and compile user supplied template, or fallback to default template
            this.$tip = this.compileTemplate(this.$config.template) || this.compileTemplate(this.constructor.Default.template);
        }

        // Add tab index so tip can be focused, and to allow it to be set as relatedTarget in focusin/out events
        this.$tip.tabIndex = -1;

        return this.$tip;
    }

    /**
     * Compile the given html template.
     *
     * @param {string} html
     * @returns {*}
     */
    compileTemplate (html) {
        if (! html || typeof html !== 'string') {
            return null;
        }

        let div = document.createElement('div');
        div.innerHTML = html.trim();

        const node = div.firstElementChild ? div.removeChild(div.firstElementChild) : null;
        div = null;

        return node;
    }

    // NOTE: Overridden by PopOver class
    setContent (tip) {
        this.setElementContent(select(Selector.TOOLTIP_INNER, tip), this.getTitle());
        removeClass(tip, ClassName.FADE);
        removeClass(tip, ClassName.SHOW);
    }

    /**
     * Set element content.
     *
     * @param container
     * @param content
     */
    setElementContent (container, content) {
        if (! container) {
            // If container element doesn't exist, just return
            return;
        }

        const allowHtml = this.$config.html;

        if (typeof content === 'object' && content.nodeType) {
            // content is a DOM node
            if (allowHtml) {
                if (content.parentElement !== container) {
                    container.innerHtml = '';
                    container.appendChild(content);
                }
            } else {
                container.innerText = content.innerText;
            }
        } else {
            // We have a plain HTML string or Text
            container[allowHtml ? 'innerHTML' : 'innerText'] = content;
        }
    }

    /**
     * Get a title for the tooltip.
     * -- Note: Overridden by PopOver class
     *
     * @returns {string}
     */
    getTitle () {
        let title = this.$config.title || '';

        if (typeof title === 'function') {
            // Call the function to get the title value
            title = title(this.$element);
        }

        if (typeof title === 'object' && title.nodeType && ! title.innerHTML.trim()) {
            // We have a DOM node, but without inner content, so just return empty string
            title = '';
        }

        if (typeof title === 'string') {
            title = title.trim();
        }

        if (! title) {
            // If an explicit title is not given, try element's title attributes
            title = getAttr(this.$element, 'title') || getAttr(this.$element, 'data-original-title') || '';
            title = title.trim();
        }

        return title;
    }

    /**
     * Get attachment area.
     *
     * @param {string} placement
     * @returns {string}
     */
    static getAttachment (placement) {
        return AttachmentMap[placement.toUpperCase()];
    }

    /**
     * Create event listeners for tooltip.
     */
    listen () {
        const triggers = this.$config.trigger.trim().split(/\s+/);
        const el = this.$element;

        // Listen for global show/hide events
        this.setRootListener(true);

        // Using 'this' as the handler will get automatically directed to this.handleEvent
        // And maintain our binding to 'this'
        triggers.forEach(trigger => {
            if (trigger === 'click') {
                eventOn(el, 'click', this);
            } else if (trigger === 'focus') {
                eventOn(el, 'focusin', this);
                eventOn(el, 'focusout', this);
            } else if (trigger === 'blur') {
                // Used to close $tip when element looses focus
                eventOn(el, 'focusout', this);
            } else if (trigger === 'hover') {
                eventOn(el, 'mouseenter', this);
                eventOn(el, 'mouseleave', this);
            }
        }, this);
    }

    /**
     * Remove tooltip event listeners.
     */
    unListen () {
        const events = ['click', 'focusin', 'focusout', 'mouseenter', 'mouseleave'];

        // Using "this" as the handler will get automatically directed to this.handleEvent
        events.forEach(event => {
            eventOff(this.$element, event, this);
        }, this);

        // Stop listening for global show/hide/enable/disable events
        this.setRootListener(false);
    }

    /**
     * Handle the given event.
     *
     * @param {Event} e
     */
    handleEvent (e) {
        // This special method allows us to use "this" as the event handlers
        if (isDisabled(this.$element)) {
            // If disabled, don't do anything. Note: if tip is shown before element gets
            // disabled, then tip not close until no longer disabled or forcefully closed.
            return;
        }

        if (! this.$isEnabled) {
            // If not enable
            return;
        }

        const type = e.type;
        const target = e.target;
        const relatedTarget = e.relatedTarget;
        const $element = this.$element;
        const $tip = this.$tip;

        if (type === 'click') {
            this.toggle(e);
        } else if (type === 'focusin' || type === 'mouseenter') {
            this.enter(e);
        } else if (type === 'focusout') {
            // target is the element which is loosing focus
            // And relatedTarget is the element gaining focus
            if ($tip && $element && $element.contains(target) && $tip.contains(relatedTarget)) {
                // If focus moves from $element to $tip, don't trigger a leave
                return;
            }

            if ($tip && $element && $tip.contains(target) && $element.contains(relatedTarget)) {
                // If focus moves from $tip to $element, don't trigger a leave
                return;
            }

            if ($tip && $tip.contains(target) && $tip.contains(relatedTarget)) {
                // If focus moves within $tip, don't trigger a leave
                return;
            }

            if ($element && $element.contains(target) && $element.contains(relatedTarget)) {
                // If focus moves within $element, don't trigger a leave
                return;
            }

            // Otherwise trigger a leave
            this.leave(e);
        } else if (type === 'mouseleave') {
            this.leave(e);
        }
    }

    setRouteWatcher (on) {
        if (on) {
            this.setRouteWatcher(false);

            if (this.$root && Boolean(this.$root.$route)) {
                this.$routeWatcher = this.$root.$watch('$route', (newVal, oldVal) => {
                    if (newVal === oldVal) {
                        return;
                    }

                    // If route has changed, we force hide the tooltip/popover
                    this.forceHide();
                });
            }
        } else {
            if (this.$routeWatcher) {
                // cancel the route watcher by calling hte stored reference
                this.$routeWatcher();
                this.$routeWatcher = null;
            }
        }
    }

    /**
     * Set modal event listener.
     *
     * @param {boolean} on
     */
    setModalListener (on) {
        const modal = closest(MODAL_CLASS, this.$element);

        if (! modal) {
            // If we are not in a modal, do nothing.
            return;
        }

        // We can listen for modal hidden events on $root
        if (this.$root) {
            this.$root[on ? '$on' : '$off'](MODAL_CLOSE_EVENT, this.$forceHide);
        }
    }

    /**
     * Set root event listeners.
     *
     * @param {boolean} on
     */
    setRootListener (on) {
        // Listen for global 'lara::{hide|show}::{tooltip|popover}' hide request event
        if (this.$root) {
            this.$root[on ? '$on' : '$off'](`lara::hide::${this.constructor.NAME}`, this.$doHide);
            this.$root[on ? '$on' : '$off'](`lara::show::${this.constructor.NAME}`, this.$doShow);
            this.$root[on ? '$on' : '$off'](`lara::disable::${this.constructor.NAME}`, this.$doDisable);
            this.$root[on ? '$on' : '$off'](`lara::enable::${this.constructor.NAME}`, this.$doEnable);
        }
    }

    /**
     * Hide the tooltip.
     *
     * @param id
     */
    doHide (id) {
        // Programmatically hide tooltip or popover
        if (! id) {
            // Close all tooltips or popovers
            this.forceHide();
        } else if (this.$element && this.$element.id && this.$element.id === id) {
            // Close this specific tooltip or popover
            this.hide();
        }
    }

    /**
     * Show tooltip.
     *
     * @param id
     */
    doShow (id) {
        // Programmatically show tooltip or popover
        if (! id) {
            // Open all tooltips or popovers
            this.show();
        } else if (id && this.$element && this.$element.id && this.$element.id === id) {
            // Show this specific tooltip or popover
            this.show();
        }
    }

    /**
     * Disable tooltip.
     *
     * @param id
     */
    doDisable (id) {
        // Programmatically disable tooltip or popover
        if (! id) {
            // Disable all tooltips or popovers
            this.disable();
        } else if (this.$element && this.$element.id && this.$element.id === id) {
            // Disable this specific tooltip or popover
            this.disable();
        }
    }

    /**
     * Enable tooltip.
     *
     * @param id
     */
    doEnable (id) {
        // Programmatically enable tooltip or popover
        if (! id) {
            // Enable all tooltips or popovers
            this.enable();
        } else if (this.$element && this.$element.id && this.$element.id === id) {
            // Enable this specific tooltip or popover
            this.enable();
        }
    }

    /**
     * Event listeners for touch devices.
     *
     * @param {boolean} on
     */
    setOnTouchStartListener (on) {
        // if this is a touch-enabled device we add extra
        // empty mouseover listeners to the body's immediate children;
        // only needed because of broken event delegation on iOS
        // https://www.quirksmode.org/blog/archives/2014/02/mouse_event_bub.html
        if ('ontouchstart' in document.documentElement) {
            arrayFrom(document.body.children).forEach(el => {
                if (on) {
                    eventOn(el, 'mouseover', this._noop);
                } else {
                    eventOff(el, 'mouseover', this._noop);
                }
            });
        }
    }

    /**
     * Empty event handler for ontouchstart devices.
     *
     * @private
     */
    _noop () {
        //
    }

    fixTitle () {
        const el = this.$element;
        const titleType = typeof getAttr(el, 'data-original-title');

        if (getAttr(el, 'title') || titleType !== 'string') {
            setAttr(el, 'data-original-title', getAttr(el, 'title') || '');
            setAttr(el, 'title', '');
        }
    }

    /**
     * Enter event handler.
     *
     * @param e
     */
    enter (e) {
        if (e) {
            this.$activeTrigger[e.type === 'focusin' ? 'focus' : 'hover'] = true;
        }

        if (hasClass(this.getTipElement(), ClassName.SHOW) || this.$hoverState === HoverState.SHOW) {
            this.$hoverState = HoverState.SHOW;

            return;
        }

        clearTimeout(this.$hoverTimeout);
        this.$hoverState = HoverState.SHOW;

        if (!this.$config.delay || !this.$config.delay.show) {
            this.show();

            return;
        }
        this.$hoverTimeout = setTimeout(() => {
            if (this.$hoverState === HoverState.SHOW) {
                this.show();
            }
        }, this.$config.delay.show);
    }

    /**
     * Leave event handler.
     *
     * @param e
     */
    leave (e) {
        if (e) {
            this.$activeTrigger[e.type === 'focusout' ? 'focus' : 'hover'] = false;

            if (e.type === 'focusout' && /blur/.test(this.$config.trigger)) {
                // Special case for `blur`: we clear out the other triggers
                this.$activeTrigger.click = false;
                this.$activeTrigger.hover = false;
            }
        }

        if (this.isWithActiveTrigger()) {
            return;
        }

        clearTimeout(this.$hoverTimeout);
        this.$hoverState = HoverState.OUT;

        if (! this.$config.delay || ! this.$config.delay.hide) {
            this.hide();

            return;
        }

        this.$hoverTimeout = setTimeout(() => {
            if (this.$hoverState === HoverState.OUT) {
                this.hide();
            }
        }, this.$config.delay.hide);
    }

    /**
     * Get popper configuration.
     *
     * @param {string} placement
     * @param {*} tip
     * @returns {object}
     */
    getPopperConfig (placement, tip) {
        return {
            placement: this.constructor.getAttachment(placement),
            modifiers: {
                offsetTop: { offsetTop: this.getOffset(placement, tip) },
                flip: { behavior: this.$config.fallbackPlacement },
                arrow: { element: '.arrow' },
                preventOverflow: { boundariesElement: this.$config.boundary }
            },
            onCreate: data => {
                // Handle flipping arrow classes
                if (data.originalPlacement !== data.placement) {
                    this.handlePopperPlacementChange(data);
                }
            },
            onUpdate: data => {
                // Handle flipping arrow classes
                this.handlePopperPlacementChange(data);
            }
        };
    }

    /**
     * Get offset of tooltip.
     *
     * @param {string} placement
     * @param {*} tip
     * @returns {string|number}
     */
    getOffset (placement, tip) {
        if (! this.$config.offsetTop) {
            const arrow = select(Selector.ARROW, tip);
            const arrowOffset = parseFloat(getCS(arrow).width) + parseFloat(this.$config.arrowPadding);

            switch (OffsetMap[placement.toUpperCase()]) {
                case +1:
                    return `+50%p - ${arrowOffset}px`;
                case -1:
                    return `-50%p + ${arrowOffset}px`;
                default:
                    return 0;
            }
        }

        return this.$config.offsetTop;
    }

    /**
     * Get tooltip placement.
     *
     * @returns {*}
     */
    getPlacement () {
        const placement = this.$config.placement;

        if (typeof placement === 'function') {
            return placement.call(this, this.$tip, this.$element);
        }

        return placement;
    }

    isWithActiveTrigger () {
        for (const trigger in this.$activeTrigger) {
            if (this.$activeTrigger[trigger]) {
                return true;
            }
        }

        return false;
    }

    // NOTE: Overridden by PopOver class
    cleanTipClass () {
        const tip = this.getTipElement();
        const tabClass = tip.className.match(BSCLS_PREFIX_REGEX);

        if (tabClass !== null && tabClass.length > 0) {
            tabClass.forEach(cls => {
                removeClass(tip, cls);
            });
        }
    }

    handlePopperPlacementChange (data) {
        this.cleanTipClass();
        this.addAttachmentClass(this.constructor.getAttachment(data.placement));
    }

    fixTransition (tip) {
        const initConfigAnimation = this.$config.animation || false;

        if (getAttr(tip, 'x-placement') !== null) {
            return;
        }

        removeClass(tip, ClassName.FADE);

        this.$config.animation = false;

        this.hide();
        this.show();

        this.$config.animation = initConfigAnimation;
    }
}

export default ToolTip;