<template>
    <div :is="tag" class="v-affix" :class="classes" :style="styles">
        <slot></slot>
    </div>
</template>

<script>
    import { isElement, select } from '../../utils/dom';
    import throttle from 'lodash/throttle';

    export default {
        props: {
            /**
             * Checks if affix should be applied.
             * Good for conditional rendering for mobile behavior.
             *
             * @type {number}
             */
            enabled: {
                type: Boolean,
                default: true
            },

            /**
             * The relative element to fix the element to.
             * The element will be affixed when the window
             * reaches the relative element.
             *
             * @type {number}
             */
            fixTo: {
                default: null,
            },

            /**
             * Minimum offset needed to apply affix.
             * Useful for elements at the very top of the window.
             *
             * @type {number}
             */
            minimumOffset: {
                type: Number,
                default: 0
            },

            /**
             * This is the offset margin between the bottom of the window
             * before the affix is applied.
             *
             * @type {number}
             */
            offsetBottom: {
                type: Number,
                default: 0
            },

            /**
             * This is the offset margin between the top of the window
             * before the affix is applied.
             *
             * @type {number}
             */
            offsetTop: {
                type: Number,
                default: 0
            },

            /**
             * Sets if the affix should be `scrollable` when it is
             * taller than the viewport or if it should always be
             * affixed to the top until it reaches the end of the
             * relative element.
             *
             * @type {boolean}
             */
            scrollable: {
                type: Boolean,
                default: false
            },

            /**
             * The tag of the affix element.
             *
             * @type {String}
             */
            tag: {
                type: String,
                default: 'div'
            }
        },

        computed: {
            /**
             * The relative element to fix to.
             *
             * @returns {Element}
             */
            relativeElement () {
                if (this.fixTo !== null) {
                    try {
                        return isElement(this.fixTo) ? this.fixTo : select(this.fixTo);
                    } catch (e) {}
                }

                return this.$el.parentElement;
            }
        },

        data () {
            return {
                classes: {
                    affix: false,
                    'affix-top': false,
                    'affix-bottom': false,
                },
                styles: {
                    top: null,
                    bottom: null,
                },
                forceAffix: false,
                affixInitialTop: null,
                affixHeight: null,
                affixBottomPosition: null,
                affixRect: null,
                offset: null,
                relativeBottomPosition: null,
                relativeOffsetTop: null,
                screenBottomPosition: null,
                topPadding: null,
                lastState: null,
                currentState: null,
                currentScrollAffix: null,
                distanceFromTop: window.pageYOffset,
                lastDistanceFromTop: window.pageYOffset,
                scrollingUp: null,
                scrollingDown: null,
                lastScrollableState: null
            };
        },

        mounted () {
            this.affixInitialTop = this.getOffsetTop(this.$el);
            this.topPadding = this.affixInitialTop - this.getOffsetTop(this.relativeElement);
            this.setDynamicVariables();

            if (this.scrollable) {
                this.initScrollable();
            }

            window.addEventListener('scroll', this.onScroll);
        },

        beforeDestroy () {
            window.removeEventListener('scroll', this.onScroll);
        },

        methods: {
            /**
             * Emits the events based on the current state of the affix.
             */
            emitEvent () {
                if (this.scrollable && this.lastScrollableState) {
                    this.$emit(this.currentScrollAffix.replace('-', ''));
                }

                if (this.lastState) {
                    this.$emit(this.currentState.replace('-', ''));
                }
            },

            /**
             * Get the top offset position of the given element.
             *
             * @param {Element} element
             * @returns {number}
             */
            getOffsetTop (element) {
                let yPosition = 0;
                let nextElement = element;

                while (nextElement) {
                    yPosition += (nextElement.offsetTop);
                    nextElement = nextElement.offsetParent;
                }

                return yPosition;
            },

            /**
             * Handle the affix event.
             */
            handleAffix () {
                if (this.scrollable && this.affixHeight > window.innerHeight) {
                    this.setScrollingDirection();

                    if (this.currentScrollAffix === 'scrollaffix-top') {
                        if (this.distanceFromTop + this.offsetTop >= this.affixInitialTop) {
                            this.setScrollAffixScrolling();
                        }
                    }

                    if (this.scrollingDown && this.currentScrollAffix === 'scrollaffix-scrolling') {
                        if (this.screenBottomPosition >= this.affixBottomPosition + this.offset.bottom && this.screenBottomPosition < this.relativeBottomPosition) {
                            this.setScrollAffixDown();
                        }
                    }

                    if (this.scrollingUp && this.currentScrollAffix === 'scrollaffix-scrolling') {
                        if (this.distanceFromTop + this.offsetTop + this.topPadding < this.affixRect.top + this.distanceFromTop) {
                            this.setScrollAffixUp();
                        }
                    }

                    if (this.scrollingDown && this.currentScrollAffix === 'scrollaffix-down') {
                        if (this.screenBottomPosition >= this.relativeBottomPosition + this.offset.bottom) {
                            this.setScrollAffixBottom();
                        }
                    }

                    if (this.currentScrollAffix === 'scrollaffix-bottom' && this.screenBottomPosition < this.relativeBottomPosition) {
                        this.setScrollAffixScrolling();
                    }

                    if ((this.scrollingUp && this.currentScrollAffix === 'scrollaffix-down') ||
                        (this.scrollingDown && this.currentScrollAffix === 'scrollaffix-up')) {
                        this.setScrollAffixScrolling();
                    }

                    if (this.scrollingUp &&
                        this.currentScrollAffix === 'scrollaffix-up' &&
                        this.distanceFromTop < this.relativeOffsetTop - this.offset.top) {
                        this.setScrollAffixTop();
                    }

                    this.lastScrollAffixState = this.currentScrollAffix;
                    this.lastDistanceFromTop = this.distanceFromTop;

                    return;
                }

                let offset = this.relativeOffsetTop - this.offsetTop;
                if (this.minimumOffset > offset) {
                    offset = this.minimumOffset;
                }

                if (this.distanceFromTop < offset) {
                    return this.setAffixTop();
                }

                if (this.distanceFromTop >= this.relativeOffsetTop - this.offsetTop &&
                    this.relativeBottomPosition - this.offsetBottom >=
                    this.distanceFromTop + this.topPadding + this.affixHeight + this.offsetTop) {
                    return this.setAffix();
                }

                if (this.relativeBottomPosition - this.offsetBottom <
                    this.distanceFromTop + this.topPadding + this.affixHeight + this.offsetTop) {
                    return this.setAffixBottom();
                }

                // this.lastState = this.currentState;
            },

            /**
             * Sets the initial position of the affixed element
             * when `scrollable` is set to true.
             */
            initScrollable () {
                if (this.distanceFromTop < this.affixInitialTop - this.offsetTop) {
                    this.setScrollAffixTop();
                } else if (this.screenBottomPosition >= this.affixBottomPosition + this.offsetBottom && this.screenBottomPosition < this.relativeBottomPosition) {
                    this.setScrollAffixDown();
                } else if (this.screenBottomPosition >= this.relativeBottomPosition) {
                    this.setScrollAffixBottom();
                }
            },

            /**
             * Handle window on scroll events.
             */
            onScroll: throttle(function () {
                if (! this.enabled) {
                    this.removeClasses();
                    return;
                }

                this.setDynamicVariables();

                if (this.affixHeight - this.offsetTop >= this.relativeElement.offsetHeight) {
                    return;
                }

                this.handleAffix();
            }, 100),

            /**
             * Re-evaluate whether the element needs to be affixed.
             */
            reEvaluate () {
                this.forceAffix = true;
                this.onScroll();
            },

            /**
             * Remove affixed classes.
             */
            removeClasses () {
                for (let clas in this.classes) {
                    this.classes[clas] = false;
                }
            },

            /**
             * Set the dynamic variables.
             */
            setDynamicVariables () {
                this.distanceFromTop = window.pageYOffset;

                try {
                    this.affixRect = this.$el.getBoundingClientRect();
                    this.affixHeight = this.$el.offsetHeight;
                    this.affixBottomPosition = this.distanceFromTop + this.affixRect.bottom;
                    this.screenBottomPosition = this.distanceFromTop + window.innerHeight;
                    this.relativeBottomPosition = this.distanceFromTop + this.relativeElement.getBoundingClientRect().bottom;
                    this.relativeOffsetTop = this.getOffsetTop(this.relativeElement);
                } catch (e) {}
            },

            /**
             * Set the affix class to indicate that the element is
             * fixed to the top of the relative element.
             */
            setAffix () {
                this.currentState = 'affix';
                this.styles.top = `${this.topPadding + this.offsetTop}px`;

                if (this.currentState !== this.lastState || this.forceAffix) {
                    this.emitEvent();
                    this.removeClasses();

                    this.classes.affix = true;
                    this.forceAffix = false;
                }
            },

            /**
             * Set the affix-bottom class to indicate that the element is
             * below the relative element.
             */
            setAffixBottom () {
                this.currentState = 'affix-bottom';
                this.styles.top = `${this.relativeElement.offsetHeight - this.affixHeight - this.offsetBottom - this.topPadding}px`;

                if (this.currentState !== this.lastState) {
                    this.emitEvent();
                    this.removeClasses();
                    this.classes['affix-bottom'] = true;
                }
            },

            /**
             * Set the affix-top class to indicate that the element
             * is above the relative element.
             */
            setAffixTop () {
                this.currentState = 'affix-top';

                if (this.currentState !== this.lastState) {
                    this.emitEvent();
                    this.removeClasses();
                    this.classes['affix-top'] = true;
                    this.styles.top = null;
                }
            },

            /**
             * Set the position of the affixed element to be at
             * the most bottom.
             */
            setScrollAffixBottom () {
                this.currentScrollAffix = 'scrollaffix-bottom';

                this.styles.top = `${this.relativeBottomPosition - this.affixInitialTop - this.affixHeight}px`;
                this.styles.bottom = 'auto';
                this.removeClasses();
                this.emitEvent();
            },

            /**
             * Set the position of the affixed element to be fixed
             * at the bottom of the screen as you are scrolling down.
             */
            setScrollAffixDown () {
                this.currentScrollAffix = 'scrollaffix-down';

                if (this.currentScrollAffix !== this.lastState) {
                    this.styles.bottom = `${this.offsetBottom}px`;
                    this.styles.top = 'auto';
                    this.removeClasses();
                    this.emitEvent();
                    this.classes.affix = true;
                }
            },

            /**
             * Sets the current scroll affix to 'scrolling' to indicate that
             * the window is scrolling inside the affixed element.
             */
            setScrollAffixScrolling () {
                this.currentScrollAffix = 'scrollaffix-scrolling';
                this.styles.top = `${(this.affixRect.top + this.distanceFromTop) - this.affixInitialTop}px`;
                this.styles.bottom = 'auto';
                this.removeClasses();
                this.emitEvent();
            },

            /**
             * Set the position of the affixed element to be at the most top.
             */
            setScrollAffixTop () {
                this.currentScrollAffix = 'scrollaffix-top';
                this.styles.top = 0;
                this.styles.bottom = 'auto';
                this.removeClasses();
                this.emitEvent();
            },

            /**
             * Set the position of the affixed element to be fixed
             * at the top of the screen as you are scrolling up.
             */
            setScrollAffixUp () {
                this.currentScrollAffix = 'scrollaffix-up';

                if (this.currentScrollAffix !== this.lastState) {
                    this.styles.top = `${this.topPadding + this.offsetTop}px`;
                    this.styles.bottom = 'auto';
                    this.removeClasses();
                    this.emitEvent();
                    this.classes.affix = true;
                }
            },

            /**
             * Set the direction the window is being scrolled.
             */
            setScrollingDirection () {
                if (this.distanceFromTop > this.lastDistanceFromTop) {
                    this.scrollingDown = true;
                    this.scrollingUp = false;
                } else {
                    this.scrollingUp = true;
                    this.scrollingDown = false;
                }
            }
        },

        watch: {
            enabled (enabled, oldValue) {
                if (enabled === oldValue) {
                    return;
                }

                if (! enabled) {
                    this.removeClasses();

                    for (let style in this.styles) {
                        this.styles[style] = null;
                    }
                } else {
                    this.reEvaluate();
                }
            }
        }
    };
</script>