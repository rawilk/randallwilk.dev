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

document.addEventListener('DOMContentLoaded', () => {
    configureLinks();
});
