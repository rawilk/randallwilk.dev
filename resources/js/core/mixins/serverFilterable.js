import UrlHelper from '../utils/class/url-helper.class';

let urls = new UrlHelper();

export default {
    data () {
        return {
            blacklistFilters: [],
            defaultSortBy: 'id',
            filters: {},
            sortBy: 'id',
            sortDesc: false
        };
    },

    created () {
        this.initSorting();
        this.initFilters();
    },

    methods: {
        /**
         * Initialize the filters.
         */
        initFilters () {
            // We always want to blacklist sort and page query params from our filters
            const blacklist = this.blacklistFilters.concat(['page', 'perPage', 'sortBy', 'sortDesc']);

            this.filters = { ...urls.getAll(blacklist) };
        },

        /**
         * Initialize the sorting for the records.
         */
        initSorting () {
            const sortBy = urls.getParam('sortBy');
            this.sortBy = !! sortBy ? sortBy : this.defaultSortBy;

            const sortDesc = urls.getParam('sortDesc');
            if (sortDesc === true || sortDesc === 'true') {
                this.sortDesc = true;
            }
        },

        /**
         * Update the url query string.
         *
         * @param {string} newQuery
         * @param {object} replacements
         */
        updateQueryFromRequest (newQuery, replacements = {}) {
            newQuery = newQuery.substring(newQuery.indexOf('?'));

            let helper = new UrlHelper(newQuery);

            try {
                Object.keys(replacements).forEach(key => helper.set(key, replacements[key]));
            } catch (e) {}

            UrlHelper.replaceQueryString(helper.toString());
        }
    }
};