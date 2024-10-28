import docSearch from '@docsearch/js';

const defaultOptions = {
    hitsPerPage: 5,
    project: '',
    version: '',
};

export default (options = {}) => ({
    ...defaultOptions,
    ...options,

    init() {
        const searchParameters = {
            facetFilters: [
                `project:${this.project}`,
                `version:${this.version}`,
            ],
            hitsPerPage: this.hitsPerPage,
        };

        docSearch({
            container: '#docsearch',
            appId: import.meta.env.VITE_ALGOLIA_APP_ID,
            indexName: import.meta.env.VITE_ALGOLIA_INDEX_NAME,
            apiKey: import.meta.env.VITE_ALGOLIA_API_KEY,
            placeholder: 'Search docs',
            translations: {
                button: {
                    buttonText: 'Search docs',
                    buttonAriaLabel: 'Search docs',
                },
            },
            searchParameters,
        });
    },
});
