import Prism from 'prismjs';
import 'prismjs/plugins/line-numbers/prism-line-numbers';

Prism.manual = true;

highlightCode();

function highlightCode() {
    [...document.querySelectorAll('pre code')].forEach(el => {
        Prism.highlightElement(el);
    });
}
