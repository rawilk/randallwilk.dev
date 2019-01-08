import orderBy from 'lodash/orderby';
import UrlHelper from '../utils/class/url-helper.class';

const urls = new UrlHelper();

export default {
    data () {
        return {
            currentPage: 1,
            perPage: 25,
            totalRows: 0,
            perPageOptions: [10, 25, 50, 100]
        };
    },

    created () {
        const perPage = parseInt(urls.getParam('perPage'));

        if (! isNaN(perPage) && perPage > 0) {
            this.setPerPage(perPage);
        }

        const page = parseInt(urls.getParam('page'));

        if (! isNaN(page) && page > 0) {
            this.currentPage = page;
        }
    },

    methods: {
        /**
         * Manually set the per page amount.
         *
         * @param {number} perPage
         */
        setPerPage (perPage) {
            perPage = parseInt(perPage);

            if (isNaN(perPage) || perPage < 0) {
                return;
            }

            this.perPage = perPage;

            if (! this.perPageOptions.includes(perPage)) {
                this.perPageOptions.push(perPage);
                this.perPageOptions = orderBy(this.perPageOptions);
            }
        },

        /**
         * Update the per page options.
         *
         * @param {object} newOption
         */
        updatePerPageOptions (newOption) {
            const value = parseInt(newOption.value);

            if (isNaN(value)) {
                return;
            }

            if (this.perPageOptions.includes(value)) {
                return;
            }

            this.perPageOptions.push(value);
            this.perPageOptions = orderBy(this.perPageOptions);
        }
    }
};