<template>
    <div :class="boxClasses">
        <div class="box-content">
            <i class="icon-featured far" :class="icon" v-if="!! icon"></i>
            <h4 class="font-weight-normal text-5">
                <slot name="title"></slot>
            </h4>
            <slot></slot>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            effect: {
                type: String,
                default: null
            },
            icon: {
                type: String,
                default: null
            },
            title: {
                type: String,
                default: null
            },
            variant: {
                type: String,
                default: null
            }
        },

        computed: {
            boxClasses () {
                return {
                    'featured-box': true,
                    [`featured-box-${this.variant}`]: !! this.variant,
                    [`featured-box-effect-${this.effect}`]: !! this.effect,
                };
            },
        },
    };
</script>