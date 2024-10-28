import header from './front/header';
import mobileNav from './stores/mobile-nav';

window.Alpine.data('header', header);

document.addEventListener('alpine:init', () => {
    window.Alpine.store('mobileNav', mobileNav());
});
