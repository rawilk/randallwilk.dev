const icons = {
    note: `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-8 w-8 text-amber-900 dark:text-sky-400">
               <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12zM12 8.25a.75.75 0 01.75.75v3.75a.75.75 0 01-1.5 0V9a.75.75 0 01.75-.75zm0 8.25a.75.75 0 100-1.5.75.75 0 000 1.5z" clip-rule="evenodd" />
           </svg>`,
    tip: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-8 w-8 text-sky-900 dark:text-sky-400">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 18v-5.25m0 0a6.01 6.01 0 001.5-.189m-1.5.189a6.01 6.01 0 01-1.5-.189m3.75 7.478a12.06 12.06 0 01-4.5 0m3.75 2.383a14.406 14.406 0 01-3 0M14.25 18v-.192c0-.983.658-1.823 1.508-2.316a7.5 7.5 0 10-7.517 0c.85.493 1.509 1.333 1.509 2.316V18" />
          </svg>`,
};

const fixBlockquote = el => {
    if (el.innerText === '') {
        el.remove();

        return;
    }

    const wrapper = el.closest('div');

    wrapper.querySelector('.prose').innerHTML = el.innerHTML;

    el.remove();
};

const replaceBlockquotesWithCallouts = () => {
    [...document.querySelectorAll('#docs-content blockquote p')].forEach(el => {
        // Prevent double highlighting when used with wire:navigate.
        if (el.closest('blockquote').getAttribute('data-processed') === '1') {
            // There's a bug right now, and this is the only way can think to fix it for now.
            if (! el.classList.contains('prose')) {
                fixBlockquote(el);
            }

            return;
        }

        el.closest('blockquote').setAttribute('data-processed', '1');

        if (el.parentNode.classList.contains('ignore')) {
            return;
        }

        const str = el.outerHTML;
        const match = str.match(/\{(.*?)\}/);
        let type, svg;
        const styles = {
            container: '',
            body: '',
        };

        if (match) {
            type = match[1] || false;
        }

        if (type) {
            switch (type) {
                case 'tip':
                    styles.container = 'bg-sky-50 dark:bg-slate-800/60 dark:ring-1 dark:ring-slate-300/10';
                    styles.body = 'text-sky-800 [--tw-prose-background:theme(colors.sky.50)] prose-a:text-sky-900 prose-code:text-sky-900 dark:text-slate-300 dark:prose-code:text-slate-300';
                    svg = icons.tip;

                    break;

                case 'note':
                default:
                    styles.container = 'bg-amber-50 dark:bg-slate-800/60 dark:ring-1 dark:ring-slate-300/10';
                    styles.body = 'text-amber-800 [--tw-prose-underline:theme(colors.amber.400)] [--tw-prose-background:theme(colors.amber.50)] prose-a:text-amber-900 prose-code:text-amber-900 prose-code:!bg-amber-200 dark:prose-code:!bg-sky-200 dark:text-slate-300 dark:[--tw-prose-underline:theme(colors.sky.700)] dark:prose-code:text-slate-300';
                    svg = icons.note;

                    break;
            }
        }

        const wrapper = document.createElement('div');
        wrapper.classList = `my-8 flex rounded-3xl p-6 ${styles.container} callout`;

        if (svg) {
            const imageWrapper = document.createElement('div');
            imageWrapper.classList = 'flex-none';
            imageWrapper.innerHTML = svg;
            wrapper.appendChild(imageWrapper);
        }

        const textWrapper = document.createElement('div');
        textWrapper.classList = 'ml-4 flex-auto';
        el.parentNode.insertBefore(wrapper, el);

        el.innerHTML = str.replace(/\{(.*?)\}/, '');
        el.classList = `prose m-0 ${styles.body}`;
        textWrapper.appendChild(el);

        wrapper.parentNode.classList = 'pl-0 border-0';
        wrapper.appendChild(textWrapper);
    });
};

document.addEventListener('DOMContentLoaded', () => {
    replaceBlockquotesWithCallouts();
});
