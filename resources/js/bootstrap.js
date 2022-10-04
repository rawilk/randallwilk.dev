import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';
import intersect from '@alpinejs/intersect';
import Clipboard from '@ryangjchandler/alpine-clipboard';
import { createPopper } from '@popperjs/core';
import flatpickr from 'flatpickr';
import frontHeader from './components/front-header';

Alpine.plugin(intersect);
Alpine.plugin(collapse);
Alpine.plugin(Clipboard);

Alpine.data('frontHeader', frontHeader);

window.Alpine = Alpine;
window.createPopper = createPopper;
window.flatpickr = flatpickr;

window.Alpine.start();
