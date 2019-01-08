<template>
    <div class="fulfilling-bouncing-circle-spinner" :style="spinnerStyle">
        <div class="circle" :style="circleStyle"></div>
        <div class="orbit" :style="orbitStyle"></div>
    </div>
</template>

<script>
    export default {
        props: {
            animationDuration: {
                type: Number,
                default: 3500
            },
            color: {
                type: String,
                default: '#fff'
            },
            size: {
                type: Number,
                default: 60
            },
        },

        computed: {
            /**
             * Generate the circle CSS styles.
             *
             * @returns {object}
             */
            circleStyle () {
                return {
                    height: `${this.size}px`,
                    width: `${this.size}px`,
                    'border-color': this.color,
                    color: this.color,
                    'border-width': `${this.size * 0.1}px`,
                    'animation-duration': `${this.animationDuration}ms`
                };
            },

            /**
             * Generate the orbit CSS styles.
             *
             * @returns {object}
             */
            orbitStyle () {
                return {
                    height: `${this.size}px`,
                    width: `${this.size}px`,
                    'border-color': this.color,
                    'border-width': `${this.size * 0.03}px`,
                    'animation-duration': `${this.animationDuration}ms`
                };
            },

            /**
             * Generate the spinner CSS styles.
             *
             * @returns {object}
             */
            spinnerStyle () {
                return {
                    height: `${this.size}px`,
                    width: `${this.size}px`,
                    'animation-duration': `${this.animationDuration}ms`
                };
            }
        },
    };
</script>