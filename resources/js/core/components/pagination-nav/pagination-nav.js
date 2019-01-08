import { assign } from '../../utils/object';
import paginationMixin from '../../mixins/component/pagination';
import { pickLinkProps } from '../link/link';

// Props needed for router links
const routerProps = pickLinkProps('activeClass', 'exactActiveClass', 'append', 'exact', 'replace', 'target', 'rel');

// Props object
const props = assign(
    // pagination-nav specific props
    {
        numberOfPages: {
            type: Number,
            default: 1
        },
        baseUrl: {
            type: String,
            default: '/'
        },
        useRouter: {
            type: Boolean,
            default: false
        },
        linkGen: {
            type: Function,
            default: null
        },
        pageGen: {
            type: Function,
            default: null
        }
    },
    // Router specific props
    routerProps
);

// Our render function is brought in via the pagination mixin
export default {
    mixins: [paginationMixin],

    props,

    computed: {
        // Used by render function to trigger wrapping in '<nav>' element
        isNav () {
            return true;
        }
    },

    methods: {
        /**
         * Determine the link props for the given page.
         *
         * @param {number} page
         * @returns {object}
         */
        linkProps (page) {
            const link = this.makeLink(page);

            let props = {
                href: typeof link === 'string' ? link : void 0,
                target: this.target || null,
                rel: this.rel || null,
                disabled: this.disabled
            };

            if (this.useRouter || typeof link === 'object') {
                props = assign(props, {
                    to: link,
                    exact: this.exact,
                    activeClass: this.activeClass,
                    exactActiveClass: this.exactActiveClass,
                    append: this.append,
                    replace: this.replace
                });
            }

            return props;
        },

        /**
         * Make a link for the given page.
         *
         * @param {number} page
         * @returns {*}
         */
        makeLink (page) {
            if (this.linkGen && typeof this.linkGen === 'function') {
                return this.linkGen(page);
            }

            const link = `${this.baseUrl}${page}`;

            return this.useRouter ? { path: link } : link;
        },

        /**
         * Make a page for the given page.
         *
         * @param {number} page
         * @returns {*}
         */
        makePage (page) {
            if (this.pageGen && typeof this.pageGen === 'function') {
                return this.pageGen(page);
            }

            return page;
        },

        /**
         * Handle click event for the given page.
         * @param {number} page
         */
        onClick (page) {
            this.currentPage = page;
        }
    }
};