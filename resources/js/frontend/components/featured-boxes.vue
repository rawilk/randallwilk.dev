<template>
    <div :class="boxClasses">
        <slot></slot>
    </div>
</template>

<script>
    import { selectAll, setAttr } from '../../core/utils/dom';

    export default {
        props: {
            flat: {
                type: Boolean,
                default: false
            },
            boxStyle: {
                type: String,
                default: null
            },
        },

        computed: {
            boxClasses () {
                return {
                    'featured-boxes': true,
                    [`featured-boxes-style-${this.boxStyle}`]: !! this.boxStyle,
                    'featured-boxes-flat': this.flat === true
                };
            },
        },

        mounted () {
            this.makeEqualHeight();
        },

        methods: {
            makeEqualHeight () {
                const boxes = selectAll('.featured-box', this.$el);

                let maxHeight = 0;

                boxes.forEach(box => {
                    if (box.offsetHeight > maxHeight) {
                        maxHeight = box.offsetHeight;
                    }
                });

                // now that we have the max height, set the height on each box
                boxes.forEach(box => box.style.height = `${maxHeight}px`);
            }
        }
    };
</script>