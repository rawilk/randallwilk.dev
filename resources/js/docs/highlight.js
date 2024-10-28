import Prism from 'prismjs';
import 'prismjs/plugins/line-numbers/prism-line-numbers';

Prism.manual = true;

document.addEventListener('livewire:navigated', () => {
    highlightCode();
});

function highlightCode() {
    [...document.querySelectorAll('pre code')].forEach(el => {
        // Prevent double highlighting when back button is used with wire:navigate.
        if (el.getAttribute('data-highlighted') === '1') {
            return;
        }

        Prism.highlightElement(el);

        el.setAttribute('data-highlighted', '1');

        // Wrap the pre tag in a wrapper div.
        const wrapper = document.createElement('div');
        wrapper.classList = ['prism-wrapper'].join(' ');

        const parent = el.parentElement;
        parent.classList.add('not-prose');
        parent.classList.add('prism-pre');

        parent.setAttribute('tabindex', '-1');

        const template = `
        <div class="code-wrapper" data-copy-button-beacon>
            <div class="code-container" style="height: 100%">
                <div class="code-editor">
                    <div class="code-scroller">
                        ${parent.outerHTML}
                    </div>
                </div>
            </div>
        </div>
        `;

        // Create a temporary container to hold the template HTML.
        const temp = document.createElement('div');
        temp.innerHTML = template;

        // Add the nested structure to the new wrapper.
        wrapper.appendChild(temp.firstElementChild);

        // Replace the original pre element with the completed wrapped structure.
        parent.replaceWith(wrapper);
    });
}
