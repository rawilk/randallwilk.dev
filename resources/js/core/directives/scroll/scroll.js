import { eventOn, eventOff, EVENTS } from '../../utils/dom';
import { isFunction } from '../../utils/typeChecks';

const HANDLER = '_lara_scroll_handler';
const events = [EVENTS.RESIZE, EVENTS.SCROLL];

const bind = (el, binding) => {
    let callback = binding.value;

    if (! isFunction(callback)) {
        return;
    }

    unbind(el);

    el[HANDLER] = callback;

    events.forEach(event => eventOn(window, event, el[HANDLER]));
};

const unbind = el => {
    events.forEach(event => eventOff(window, event, el[HANDLER]));

    delete el[HANDLER];
};

const update = (el, binding) => {
    if (binding.value !== binding.oldValue) {
        bind(el, binding);
    }
};

export default { bind, unbind, update };