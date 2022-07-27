import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';
import Clipboard from '@ryangjchandler/alpine-clipboard';
import { createPopper } from '@popperjs/core';
import flatpickr from 'flatpickr';

Alpine.plugin(collapse);
Alpine.plugin(Clipboard);

window.Alpine = Alpine;
window.createPopper = createPopper;
window.flatpickr = flatpickr;

window.Alpine.start();
