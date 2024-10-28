import tippy from 'tippy.js';

const clipboardSvg = `
<svg class="shrink-0 [[data-copied]_&]:hidden h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 7.5V6.108c0-1.135.845-2.098 1.976-2.192.373-.03.748-.057 1.123-.08M15.75 18H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08M15.75 18.75v-1.875a3.375 3.375 0 0 0-3.375-3.375h-1.5a1.125 1.125 0 0 1-1.125-1.125v-1.5A3.375 3.375 0 0 0 6.375 7.5H5.25m11.9-3.664A2.251 2.251 0 0 0 15 2.25h-1.5a2.251 2.251 0 0 0-2.15 1.586m5.8 0c.065.21.1.433.1.664v.75h-6V4.5c0-.231.035-.454.1-.664M6.75 7.5H4.875c-.621 0-1.125.504-1.125 1.125v12c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V16.5a9 9 0 0 0-9-9Z"></path>
</svg>
`;

const copiedSvg = `
<svg class="shrink-0 hidden [[data-copied]_&]:block h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" aria-hidden="true">
    <path fill-rule="evenodd" d="M12.416 3.376a.75.75 0 0 1 .208 1.04l-5 7.5a.75.75 0 0 1-1.154.114l-3-3a.75.75 0 0 1 1.06-1.06l2.353 2.353 4.493-6.74a.75.75 0 0 1 1.04-.207Z" clip-rule="evenodd"></path>
</svg>
`;

document.addEventListener('DOMContentLoaded', () => {
    const codeBlocks = document.querySelectorAll('#docs-content .prism-wrapper');

    codeBlocks.forEach((element, key) => {
        // Copy to clipboard button
        let copyToClipboardBtn = document.createElement('button');

        copyToClipboardBtn.innerHTML = clipboardSvg + copiedSvg;
        copyToClipboardBtn.id = `clipButton-${key}`;
        copyToClipboardBtn.classList = 'copy-btn';

        copyToClipboardBtn.setAttribute('aria-label', 'Copy to clipboard');

        copyToClipboardBtn.onclick = () => {
            navigator.clipboard.writeText(
                copyToClipboardBtn.closest('[data-copy-button-beacon]').querySelector('code').innerText
            );

            copyToClipboardBtn.parentElement.setAttribute('data-copied', '');

            setTimeout(() => {
                copyToClipboardBtn.parentElement.removeAttribute('data-copied');
            }, 2000);
        };

        const wrapper = document.createElement('div');
        wrapper.classList = ['hidden', 'md:block', 'absolute', 'top-0', 'right-0', 'pt-3', 'pr-3', 'z-[1]'].join(' ');

        wrapper.appendChild(copyToClipboardBtn);

        element.querySelector('.code-wrapper').appendChild(wrapper);

        tippy(copyToClipboardBtn, {
            content: 'Copy to clipboard',
            arrow: false,
        });
    });
});
