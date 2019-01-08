import { Notify } from './class/notification.class';

/**
 * Copy the given string to the clipboard.
 *
 * @param {string} string
 */
export const copyToClipboard = string => {
    const el = document.createElement('textarea');
    el.value = string;
    el.setAttribute('readonly', '');
    el.style.position = 'absolute';
    el.style.left = '-9999px';

    document.body.appendChild(el);

    const selected = document.getSelection().rangeCount > 0 ? document.getSelection().getRangeAt(0) : false;

    el.select();

    document.execCommand('copy');
    document.body.removeChild(el);

    if (selected) {
        document.getSelection().removeAllRanges();
        document.getSelection().addRange(selected);
    }

    const notify = new Notify();
    notify.topRight().success('Text was copied to the clipboard!');
};