import Alpine from 'alpinejs';
import flatpickr from 'flatpickr';
import Clipboard from '@ryangjchandler/alpine-clipboard';
import { createPopper } from '@popperjs/core';
import components from './components';
import $root from './magics/$root';

Alpine.plugin(Clipboard);

Object.keys(components).forEach(key => {
    Alpine.data(key, components[key]);
});

Alpine.magic('root', $root);

window.Alpine = Alpine;
window.flatpickr = flatpickr;
window.createPopper = createPopper;

window.Alpine.start();
