import header from './front/header';
import search from './docs/components/search';
import themeSwitcher from './docs/components/theme-switcher.js';
import mobileNav from './stores/mobile-nav';
import tableOfContents from './stores/table-of-contents.js';
import './docs/callouts';
import './docs/highlight';
import './docs/permalinks';
import './docs/clipboard';
import './docs/links';

document.addEventListener('alpine:init', () => {
    window.Alpine.data('header', header);
    window.Alpine.data('search', search);
    window.Alpine.data('themeSwitcher', themeSwitcher);

    window.Alpine.store('mobileNav', mobileNav());
    window.Alpine.store('toc', tableOfContents);
});
