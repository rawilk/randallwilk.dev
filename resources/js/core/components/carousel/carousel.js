import observeDom from '../../utils/observeDom';
import KeyCodes from '../../utils/keyCodes';
import { selectAll, reflow, addClass, removeClass, setAttr, eventOn, eventOff } from '../../utils/dom';
import idMixin from '../../mixins/component/id';

// Slide directional classes
const DIRECTION = {
    next: {
        dirClass: 'carousel-item-left',
        overlayClass: 'carousel-item-next'
    },
    prev: {
        dirClass: 'carousel-item-right',
        overlayClass: 'carousel-item-prev'
    }
};

// Fallback Transition duration (with a little buffer) in ms
const TRANS_DURATION = 600 + 50;

// Transition Event names
const TransitionEndEvents = {
    WebkitTransition: 'webkitTransitionEnd',
    MozTransition: 'transitionend',
    OTransition: 'otransitionend oTransitionEnd',
    transition: 'transitionend'
};

// Return the browser specific transitionEnd event name
function getTransitionEndEvent (el) {
    for (const name in TransitionEndEvents) {
        if (el.style[name] !== undefined) {
            return TransitionEndEvents[name];
        }
    }

    // fallback
    return null;
}

export default {
    mixins: [idMixin],

    props: {
        labelPrev: {
            type: String,
            default: 'Previous Slide'
        },
        labelNext: {
            type: String,
            default: 'Next Slide'
        },
        labelGotoSlide: {
            type: String,
            default: 'Goto Slide'
        },
        labelIndicators: {
            type: String,
            default: 'Select a slide to display'
        },
        interval: {
            type: Number,
            default: 5000
        },
        indicators: {
            type: Boolean,
            default: false
        },
        controls: {
            type: Boolean,
            default: false
        },
        imgWidth: {
            // Sniffed by carousel-slide
            type: [Number, String]
        },
        imgHeight: {
            // Sniffed by carousel-slide
            type: [Number, String]
        },
        background: {
            type: String
        },
        value: {
            type: Number,
            default: 0
        }
    },

    computed: {
        /**
         * Determine if cycling.
         *
         * @returns {boolean}
         */
        isCycling () {
            return Boolean(this.intervalId);
        }
    },

    data () {
        return {
            index: this.value || 0,
            isSliding: false,
            intervalId: null,
            transitionEndEvent: null,
            slides: [],
            direction: null
        };
    },

    render (h) {
        // Wrapper for slides
        const inner = h(
            'div',
            {
                ref: 'inner',
                class: ['carousel-inner'],
                attrs: {
                    id: this.safeId('__Lara_inner_'),
                    role: 'list'
                }
            },
            [this.$slots.default]
        );

        // Prev and Next Controls
        let controls = h(false);
        if (this.controls) {
            controls = [
                h(
                    'a',
                    {
                        class: ['carousel-control-prev'],
                        attrs: { href: '#', role: 'button', 'aria-controls': this.safeId('__Lara_inner_') },
                        on: {
                            click: (event) => {
                                event.preventDefault();
                                event.stopPropagation();

                                this.prev();
                            },
                            keydown: (event) => {
                                const keyCode = event.keyCode;
                                if (keyCode === KeyCodes.SPACE || keyCode === KeyCodes.ENTER) {
                                    event.preventDefault();
                                    event.stopPropagation();

                                    this.prev();
                                }
                            }
                        }
                    },
                    [
                        h('span', { class: ['carousel-control-prev-icon'], attrs: { 'aria-hidden': 'true' } }),
                        h('span', { class: ['sr-only'] }, [this.labelPrev])
                    ]
                ),
                h(
                    'a',
                    {
                        class: ['carousel-control-next'],
                        attrs: { href: '#', role: 'button', 'aria-controls': this.safeId('__Lara_inner_') },
                        on: {
                            click: (event) => {
                                event.preventDefault();
                                event.stopPropagation();

                                this.next();
                            },
                            keydown: (event) => {
                                const keyCode = event.keyCode;
                                if (keyCode === KeyCodes.SPACE || keyCode === KeyCodes.ENTER) {
                                    event.preventDefault();
                                    event.stopPropagation();

                                    this.next();
                                }
                            }
                        }
                    },
                    [
                        h('span', { class: ['carousel-control-next-icon'], attrs: { 'aria-hidden': 'true' } }),
                        h('span', { class: ['sr-only'] }, [this.labelNext])
                    ]
                )
            ];
        }

        // Indicators
        const indicators = h(
            'ol',
            {
                class: ['carousel-indicators'],
                directives: [
                    { name: 'show', rawName: 'v-show', value: this.indicators, expression: 'indicators' }
                ],
                attrs: {
                    id: this.safeId('__Lara_indicators_'),
                    'aria-hidden': this.indicators ? 'false' : 'true',
                    'aria-label': this.labelIndicators,
                    'aria-owns': this.safeId('__BV_inner_')
                }
            },
            this.slides.map((slide, n) => {
                return h(
                    'li',
                    {
                        key: `slide_${n}`,
                        class: { active: n === this.index },
                        attrs: {
                            role: 'button',
                            id: this.safeId(`__Lara_indicator_${n + 1}_`),
                            tabindex: this.indicators ? '0' : '-1',
                            'aria-current': n === this.index ? 'true' : 'false',
                            'aria-label': `${this.labelGotoSlide} ${n + 1}`,
                            'aria-describedby': this.slides[n].id || null,
                            'aria-controls': this.safeId('__BV_inner_')
                        },
                        on: {
                            click: (event) => {
                                this.setSlide(n);
                            },
                            keydown: (event) => {
                                const keyCode = event.keyCode;
                                if (keyCode === KeyCodes.SPACE || keyCode === KeyCodes.ENTER) {
                                    event.preventDefault();
                                    event.stopPropagation();

                                    this.setSlide(n);
                                }
                            }
                        }
                    }
                );
            })
        );

        // Return the carousel
        return h(
            'div',
            {
                class: ['carousel', 'slide'],
                style: { background: this.background },
                attrs: {
                    role: 'region',
                    id: this.safeId(),
                    'aria-busy': this.isSliding ? 'true' : 'false'
                },
                on: {
                    mouseenter: this.pause,
                    mouseleave: this.restart,
                    focusin: this.pause,
                    focusout: this.restart,
                    keydown: (event) => {
                        const keyCode = event.keyCode;
                        if (keyCode === KeyCodes.LEFT || keyCode === KeyCodes.RIGHT) {
                            event.preventDefault();
                            event.stopPropagation();

                            this[keyCode === KeyCodes.LEFT ? 'prev' : 'next']();
                        }
                    }
                }
            },
            [inner, controls, indicators]
        );
    },

    created () {
        // Create private non-reactive props
        this._animationTimeout = null;
    },

    mounted () {
        // Cache current browser transitionend event name
        this.transitionEndEvent = getTransitionEndEvent(this.$el) || null;

        // Get all slides
        this.updateSlides();

        // Observe child changes so we can update slide list
        observeDom(this.$refs.inner, this.updateSlides.bind(this), {
            subtree: false,
            childList: true,
            attributes: true,
            attributeFilter: ['id']
        });
    },

    beforeDestroy () {
        clearInterval(this.intervalId);
        clearTimeout(this._animationTimeout);

        this.intervalId = null;
        this._animationTimeout = null;
    },

    methods: {
        /**
         * Calculate slide direction.
         *
         * @param {string|null} direction
         * @param {number} curIndex
         * @param {number} nextIndex
         * @returns {string}
         */
        calculateDirection (direction = null, curIndex = 0, nextIndex = 0) {
            if (! direction) {
                return (nextIndex > curIndex) ? DIRECTION.next : DIRECTION.prev;
            }

            return DIRECTION[direction];
        },

        /**
         * Go to the next slide.
         */
        next () {
            this.direction = 'next';
            this.setSlide(this.index + 1);
        },

        /**
         * Pause auto rotation of slides.
         */
        pause () {
            if (this.isCycling) {
                clearInterval(this.intervalId);
                this.intervalId = null;

                if (this.slides[this.index]) {
                    // Make current slide focusable for screen readers
                    this.slides[this.index].tabIndex = 0;
                }
            }
        },

        /**
         * Go the the previous slide.
         */
        prev () {
            this.direction = 'prev';
            this.setSlide(this.index - 1);
        },

        /**
         * Restart auto rotation when focus/hover leaves the carousel.
         *
         * @param {Event} event
         */
        restart (event) {
            if (! this.$el.contains(document.activeElement)) {
                this.start();
            }
        },

        /**
         * Set the current slide.
         *
         * @param {number} slide
         */
        setSlide (slide) {
            // Don't animate when page is not visible
            if (typeof document !== 'undefined' && document.visibilityState && document.hidden) {
                return;
            }

            const len = this.slides.length;

            // Don't do anything if nothing to slide to
            if (len === 0) {
                return;
            }

            // Don't change slide while transitioning, wait until transition is done
            if (this.isSliding) {
                // Schedule slide after sliding complete
                this.$once('sliding-end', () => this.setSlide(slide));

                return;
            }

            // Make sure we have an integer (you never know!)
            slide = Math.floor(slide);

            // Set new slide index. Wrap around if necessary
            this.index = slide >= len ? 0 : (slide >= 0 ? slide : len - 1);
        },

        /**
         * Start auto rotation of slides.
         */
        start () {
            // Don't start if no interval, or if we are already running
            if (! this.interval || this.isCycling) {
                return;
            }

            this.slides.forEach(slide => {
                slide.tabIndex = -1;
            });

            this.intervalId = setInterval(() => {
                this.next();
            }, Math.max(1000, this.interval));
        },

        /**
         * Update the slide list.
         */
        updateSlides () {
            this.pause();

            // Get all slides as DOM elements
            this.slides = selectAll('.carousel-item', this.$refs.inner);
            const numSlides = this.slides.length;

            // Keep slide number in range
            const index = Math.max(0, Math.min(Math.floor(this.index), numSlides - 1));

            this.slides.forEach((slide, index) => {
                const n = index + 1;
                if (index === index) {
                    addClass(slide, 'active');
                } else {
                    removeClass(slide, 'active');
                }

                setAttr(slide, 'aria-current', index === index ? 'true' : 'false');
                setAttr(slide, 'aria-posinset', String(n));
                setAttr(slide, 'aria-setsize', String(numSlides));

                slide.tabIndex = -1;
            });

            // Set slide as active
            this.setSlide(index);
            this.start();
        },
    },

    watch: {
        value (newVal, oldVal) {
            if (newVal !== oldVal) {
                this.setSlide(newVal);
            }
        },

        interval (newVal, oldVal) {
            if (newVal === oldVal) {
                return;
            }

            if (! newVal) {
                // Pausing slide show
                this.pause();
            } else {
                // Restarting or Changing interval
                this.pause();
                this.start();
            }
        },

        index (val, oldVal) {
            if (val === oldVal || this.isSliding) {
                return;
            }

            // Determine sliding direction
            let direction = this.calculateDirection(this.direction, oldVal, val);

            // Determine current and next slides
            const currentSlide = this.slides[oldVal];
            const nextSlide = this.slides[val];

            // Don't do anything if there aren't any slides to slide to
            if (! currentSlide || ! nextSlide) {
                return;
            }

            // Start animating
            this.isSliding = true;
            this.$emit('sliding-start', val);

            // Update v-model
            this.$emit('input', this.index);
            nextSlide.classList.add(direction.overlayClass);

            // Trigger a reflow of next slide
            reflow(nextSlide);
            addClass(currentSlide, direction.dirClass);
            addClass(nextSlide, direction.dirClass);

            // Transition End handler
            let called = false;

            const onceTransEnd = (event) => {
                if (called) {
                    return;
                }

                called = true;
                if (this.transitionEndEvent) {
                    const events = this.transitionEndEvent.split(/\s+/);

                    events.forEach(event => {
                        eventOff(currentSlide, event, onceTransEnd);
                    });
                }

                this._animationTimeout = null;
                removeClass(nextSlide, direction.dirClass);
                removeClass(nextSlide, direction.overlayClass);
                addClass(nextSlide, 'active');
                removeClass(currentSlide, 'active');
                removeClass(currentSlide, direction.dirClass);
                removeClass(currentSlide, direction.overlayClass);
                setAttr(currentSlide, 'aria-current', 'false');
                setAttr(nextSlide, 'aria-current', 'true');
                setAttr(currentSlide, 'aria-hidden', 'true');
                setAttr(nextSlide, 'aria-hidden', 'false');

                currentSlide.tabIndex = -1;
                nextSlide.tabIndex = -1;

                if (! this.isCycling) {
                    // Focus the next slide for screen readers if not in play mode
                    nextSlide.tabIndex = 0;
                    this.$nextTick(() => {
                        nextSlide.focus();
                    });
                }

                this.isSliding = false;
                this.direction = null;

                // Notify ourselves that we're done sliding (slid)
                this.$nextTick(() => this.$emit('sliding-end', val));
            };

            // Clear transition classes after transition ends
            if (this.transitionEndEvent) {
                const events = this.transitionEndEvent.split(/\s+/);
                events.forEach(event => {
                    eventOn(currentSlide, event, onceTransEnd);
                });
            }

            // Fallback to setTimeout
            this._animationTimeout = setTimeout(onceTransEnd, TRANS_DURATION);
        }
    }
};