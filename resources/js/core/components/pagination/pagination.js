import paginationMixin from '../../mixins/component/pagination';
import { isVisible } from '../../utils/dom';

const props = {
    perPage: {
        type: Number,
        default: 25
    },
    totalRows: {
        type: Number,
        default: 20
    },
    ariaControls: {
        type: String,
        default: null
    }
};

// Our render function is brought in from the pagination mixin
export default {
    mixins: [paginationMixin],

    props,

    computed: {
        /**
         * Determine if the number of pages.
         *
         * @returns {number}
         */
        numberOfPages () {
            const result = Math.ceil(this.totalRows / this.perPage);
            return (result < 1) ? 1 : result;
        }
    },

    methods: {
        /**
         * Determine link props for the given page.
         *
         * @param {number} page
         * @returns {object}
         */
        linkProps (page) {
            return { href: '#' };
        },

        /**
         * Make a page for the given page number.
         *
         * @param {number} page
         * @returns {number}
         */
        makePage (page) {
            return page;
        },

        /**
         * Handle on click events.
         *
         * @param {number} page
         * @param {MouseEvent} event
         */
        onClick (page, event) {
            // Handle edge cases where number of pages has changed (i.e. if perPage changes)
            if (page > this.numberOfPages) {
                page = this.numberOfPages;
            } else if (page < 1) {
                page = 1;
            }

            this.currentPage = page;
            this.$nextTick(() => {
                // Keep the current button focused if possible
                const target = event.target;
                if (isVisible(target) && this.$el.contains(target) && target.focus) {
                    target.focus();
                } else {
                    this.focusCurrent();
                }
            });

            this.$emit('change', this.currentPage);
        }
    }
};