/**
 * Register an event listener on the given element only once.
 *
 * @param {HTMLElement} el
 * @param {string} eventName
 * @param {function} callback
 */
export default function addEventListenerOnce (el, eventName, callback) {
    function fnOnce () {
        el.removeEventListener(eventName, fnOnce);

        return callback.apply(null, arguments);
    }

    el.addEventListener(event, fnOnce);
}