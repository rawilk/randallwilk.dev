/**
 * Disclaimer: This script is heavily based off the one found
 * in blade-ui-kit.com.
 */

import ClipboardJS from 'clipboard';

// These icons must be inline to avoid rendering bugs.
const clipboardIcon = `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 text-slate-500 hover:text-slate-300"><path fill-rule="evenodd" d="M10.5 3A1.501 1.501 0 009 4.5h6A1.5 1.5 0 0013.5 3h-3zm-2.693.178A3 3 0 0110.5 1.5h3a3 3 0 012.694 1.678c.497.042.992.092 1.486.15 1.497.173 2.57 1.46 2.57 2.929V19.5a3 3 0 01-3 3H6.75a3 3 0 01-3-3V6.257c0-1.47 1.073-2.756 2.57-2.93.493-.057.989-.107 1.487-.15z" clip-rule="evenodd" /></svg>`;
const clipboardCopiedIcon = `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 text-green-400"><path fill-rule="evenodd" d="M7.502 6h7.128A3.375 3.375 0 0118 9.375v9.375a3 3 0 003-3V6.108c0-1.505-1.125-2.811-2.664-2.94a48.972 48.972 0 00-.673-.05A3 3 0 0015 1.5h-1.5a3 3 0 00-2.663 1.618c-.225.015-.45.032-.673.05C8.662 3.295 7.554 4.542 7.502 6zM13.5 3A1.5 1.5 0 0012 4.5h4.5A1.5 1.5 0 0015 3h-1.5z" clip-rule="evenodd" /><path fill-rule="evenodd" d="M3 9.375C3 8.339 3.84 7.5 4.875 7.5h9.75c1.036 0 1.875.84 1.875 1.875v11.25c0 1.035-.84 1.875-1.875 1.875h-9.75A1.875 1.875 0 013 20.625V9.375zm9.586 4.594a.75.75 0 00-1.172-.938l-2.476 3.096-.908-.907a.75.75 0 00-1.06 1.06l1.5 1.5a.75.75 0 001.116-.062l3-3.75z" clip-rule="evenodd" /></svg>`;

// Copy to Clipboard.
let codeBlocks = document.querySelectorAll('#docs-content pre');
codeBlocks.forEach((element, key) => {
    // Copy to clipboard button.
    let copyToClipboardBtn = document.createElement('button');

    copyToClipboardBtn.innerHTML = clipboardIcon;
    copyToClipboardBtn.id = `clipButton-${key}`;
    copyToClipboardBtn.classList = 'hidden lg:block';

    copyToClipboardBtn.setAttribute('aria-label', 'Copy to Clipboard');
    copyToClipboardBtn.setAttribute('title', 'Copy to Clipboard');
    copyToClipboardBtn.classList.add('copyBtn');

    element.appendChild(copyToClipboardBtn);

    let copyToClipboard = new ClipboardJS(`#${copyToClipboardBtn.id}`);

    copyToClipboard.on('success', (element) => {
        copyToClipboardBtn.innerHTML = clipboardCopiedIcon;
        element.clearSelection();
        setTimeout(() => {
            copyToClipboardBtn.innerHTML = clipboardIcon;
        }, 1500);
    });

    // Code Element.
    let codeElement = element.querySelector('code');
    codeElement.id = `clipText-${key}`;
    copyToClipboardBtn.dataset.clipboardTarget = `#${codeElement.id}`;
});
