const configurePermalinks = () => {
    [...document.querySelectorAll('.anchor-link')].forEach(el => {
        el.parentNode.classList.add('hover:cursor-pointer');

        el.parentNode.addEventListener('click', () => {
            el.click();
        });
    });
};

document.addEventListener('DOMContentLoaded', () => {
    configurePermalinks();
});
