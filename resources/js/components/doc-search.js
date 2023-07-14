import docSearch from '@docsearch/js';

export default (options = {}) => ({
    modifierKey: null,
    isOpen: false,
    project: options.project || '',
    version: options.version || '',
    hitsPerPage: options.hitsPerPage || 5,

    init() {
        this.setModifierKey();

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
            searchParameters,
        });
    },

    isMac() {
        return /(Mac|iPhone|iPod|iPad)/i.test(navigator.platform);
    },

    setModifierKey() {
        this.modifierKey = this.isMac() ? '⌘' : 'Ctrl';
    },

    openSearch() {
        const button = document.getElementById('docsearch').querySelectorAll('.DocSearch-Button')[0];
        button && button.click();
    },
});
