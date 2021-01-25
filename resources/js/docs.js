import Prism from 'prismjs';
require('./clipboard');
Prism.manual = true;

highlightCode();
replaceBlockquotesWithCalloutsInDocs();

function highlightCode() {
    [...document.querySelectorAll('pre code')].forEach(el => {
        Prism.highlightElement(el);
    });
}

function replaceBlockquotesWithCalloutsInDocs() {
    [...document.querySelectorAll('.docs-main blockquote p')].forEach(el => {
        if (el.parentNode.classList.contains('ignore')) {
            return;
        }

        const str = el.outerHTML;
        const match = str.match(/\{(.*?)\}/);
        let color, type, svg;

        if (match) {
            type = match[1] || false;
        }

        if (type) {
            switch (type) {
                case 'tip':
                    color = 'bg-blue-600 text-white';
                    // heroicons `heroicon-o-light-bulb`
                    svg = `<svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path></svg>`;
                    break;

                case 'note':
                default:
                    color = 'bg-red-600 text-white';
                    // bootstrap icons `bi-exclamation`
                    svg = `<svg class="w-5 h-5" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M7.002 11a1 1 0 1 1 2 0 1 1 0 0 1-2 0zM7.1 4.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 4.995z"></path></svg>`;
                    break;
            }
        }

        const wrapper = document.createElement('div');
        wrapper.classList = 'bg-blue-gray-100 mb-10 max-w-2xl mx-auto px-4 py-8 shadow-lg lg:flex lg:items-center';

        const imageWrapper = document.createElement('div');
        imageWrapper.classList = `w-8 h-8 mb-6 absolute -top-2 -left-1 flex items-center justify-center rounded-full ${color} lg:mb-0`;
        imageWrapper.innerHTML = svg;
        wrapper.appendChild(imageWrapper);

        el.parentNode.insertBefore(wrapper, el);
        el.parentNode.classList.add('mt-10');

        el.innerHTML = str.replace(/\{(.*?)\}/, '');
        el.classList = 'mb-0 lg:ml-6';
        wrapper.classList.add('callout');
        wrapper.appendChild(el);
    });
}

