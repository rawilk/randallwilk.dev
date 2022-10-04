import './docs/highlight';
import './docs/callouts';
import './docs/clipboard';
import './docs/permalinks';
import docsHeader from './components/docs-header';
import docSearch from './components/doc-search';
import themeSelector from './components/theme-selector';

document.addEventListener('alpine:init', () => {
    Alpine.data('docsHeader', docsHeader);
    Alpine.data('docSearch', docSearch);
    Alpine.data('themeSelector', themeSelector);

    Alpine.store('visibleSection', {
        current: '',
        headings: [],

        registerHeadings(tableOfContents) {
            this.headings = tableOfContents.flatMap(heading => [heading.id, ...heading.children.map(child => child.id)]);
            this.headings.forEach(id => {
                const el = document.getElementById(id);
                if (! el) {
                    return;
                }

                const observer = new IntersectionObserver(entries => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            this.current = id;
                        }
                    });
                });

                observer.observe(el);
            });
        }
    });
});
