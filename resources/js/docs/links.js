const configureLinks = () => {
    [...document.querySelectorAll('#docs-content a')].forEach(el => {
        const url = new URL(el.href);
        if (url.origin !== location.origin) {
            setLinkTargetToBlank(el);
        }
    });
};

const setLinkTargetToBlank = el => {
    if (el.hasAttribute('target')) {
        return;
    }

    el.setAttribute('target', '_blank');
    el.setAttribute('rel', 'noopener');
};

/**
 * Some browsers (i.e. Chrome) have issues sometimes scrolling
 * to a selected fragment on page load. This is an attempt
 * to fix that.
 */
const scrollToFragment = () => {
    const hash = window.location.hash;
    if (! hash) {
        return;
    }

    const id = hash.slice(1);
    const element = document.getElementById(id);

    if (element) {
        setTimeout(() => {
            element.scrollIntoView({
                block: 'start',
            });
        }, 0);
    }
};

document.addEventListener('DOMContentLoaded', () => {
    configureLinks();
    scrollToFragment();
});
