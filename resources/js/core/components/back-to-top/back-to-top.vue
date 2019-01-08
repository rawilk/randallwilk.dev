<template>
    <transition name="back-to-top-fade">
        <div class="lara-back-to-top" v-show="visible"
             @click="backToTop" :style="positionStyles"
        >
            <slot>
                <div class="default">
                    <span>
                        <i :class="icon"></i>
                    </span>
                </div>
            </slot>
        </div>
    </transition>
</template>

<script>
    import { eventOn, eventOff } from '../../utils/dom';
    import throttle from 'lodash/throttle';

    export default {
        name: 'back-to-top',

        props: {
            bottom: {
                type: String,
                default: '40px'
            },
            icon: {
                type: String,
                default: 'mdi mdi-arrow-up'
            },
            right: {
                type: String,
                default: '30px'
            },
            visibleOffset: {
                type: [Number, String],
                default: 600
            }
        },

        computed: {
            /**
             * Generate the CSS styles to position the button.
             *
             * @returns {object}
             */
            positionStyles () {
                return {
                    bottom: this.bottom,
                    right: this.right
                };
            },
        },

        data () {
            return {
                visible: false
            };
        },

        mounted () {
            window.smoothscroll = () => {
                const currentScroll = this.getCurrentScroll();

                if (currentScroll > 0) {
                    window.requestAnimationFrame(window.smoothscroll);
                    window.scrollTo(0, Math.floor(currentScroll - (currentScroll / 5)));
                }
            };

            eventOn(window, 'scroll', this.handleScroll);
        },

        destroyed () {
            eventOff(window, 'scroll', this.handleScroll);
        },

        methods: {
            /**
             * Scroll the window back to top.
             */
            backToTop () {
                window.smoothscroll();
            },

            /**
             * Get the current scroll position.
             *
             * @returns {number}
             */
            getCurrentScroll () {
                return document.documentElement.scrollTop || document.body.scrollTop;
            },

            /**
             * Handle the scroll event.
             */
            handleScroll: throttle(function () {
                this.visible = window.pageYOffset > parseInt(this.visibleOffset);
            }, 100)
        }
    };
</script>