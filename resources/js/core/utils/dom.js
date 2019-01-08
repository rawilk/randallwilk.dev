import { arrayFrom, isArray } from './array';

export const EVENTS = {
    MOUSE_ENTER: 'mouseenter',
    MOUSE_LEAVE: 'mouseleave',
    FOCUS: 'focus',
    BLUR: 'blur',
    CLICK: 'click',
    INPUT: 'input',
    KEY_DOWN: 'keydown',
    KEY_UP: 'keyup',
    KEY_PRESS: 'keypress',
    RESIZE: 'resize',
    SCROLL: 'scroll'
};

/**
 * Add a class to the given element.
 *
 * @param {HTMLElement} el
 * @param {string} className
 */
export const addClass = (el, className) => {
    if (className && isElement(el)) {
        el.classList.add(className);
    }
};

/**
 * Find the closest element matching the given selector.
 *
 * @param {string} selector
 * @param {HTMLElement} root
 * @returns {*}
 */
export const closest = (selector, root) => {
    if (! isElement(root)) {
        return null;
    }

    // https://developer.mozilla.org/en-US/docs/Web/API/Element/closest
    // Since we don't support IE < 10, we can use the "Matches" version of the polyfill for speed
    // Prefer native implementation over polyfill function
    const Closest = Element.prototype.closest ||
        function (sel) {
            let element = this;
            if (!document.documentElement.contains(element)) {
                return null;
            }
            do {
                // Use our "patched" matches function
                if (matches(element, sel)) {
                    return element;
                }
                element = element.parentElement;
            } while (element !== null);
            return null;
        };

    const el = Closest.call(root, selector);

    // Emulate jQuery closest and return null if match is the passed in element (root)
    return el === root ? null : el;
};

/**
 * Attach an event listener to the given element.
 *
 * @param {HTMLElement} el
 * @param {string} eventName
 * @param {function} handler
 */
export const eventOn = (el, eventName, handler) => {
    if (el && el.addEventListener) {
        el.addEventListener(eventName, handler);
    }
};

/**
 * Remove an event listener from the given element.
 *
 * @param {HTMLElement} el
 * @param {string} eventName
 * @param {function} handler
 */
export const eventOff = (el, eventName, handler) => {
    if (el && el.removeEventListener) {
        el.removeEventListener(eventName, handler);
    }
};

/**
 * Get an attribute value from the given element.
 *
 * @param {HTMLElement} el
 * @param {string} attr
 * @returns {*}
 */
export const getAttr = (el, attr) => {
    if (attr && isElement(el)) {
        return el.getAttribute(attr);
    }

    return null;
};

/**
 * Get the BoundingClientRect of the given element.
 *
 * @param {HTMLElement} el
 * @returns {object|null}
 */
export const getBCR = el => isElement(el) ? el.getBoundingClientRect() : null;

/**
 * Find an element from the given id.
 *
 * @param {string} id
 * @returns {HTMLElement|null}
 */
export const getById = id => document.getElementById(/^#/.test(id) ? id.slice(1) : id) || null;

/**
 * Get the computed styles for the given element.
 *
 * @param {HTMLElement} el
 * @returns {object}
 */
export const getCS = el => isElement(el) ? window.getComputedStyle(el) : {};

/**
 * Determine if an attribute exists on the given element.
 *
 * @param {HTMLElement} el
 * @param {string} attr
 * @returns {boolean|null}
 */
export const hasAttr = (el, attr) => {
    if (attr && isElement(attr)) {
        return el.hasAttribute(attr);
    }

    return null;
};

/**
 * Determine if a class exists on the given element.
 *
 * @param {HTMLElement} el
 * @param {string} className
 * @returns {boolean}
 */
export const hasClass = (el, className) => {
    if (className && isElement(el)) {
        className = className.split(' ');
        return className.every(checkClass => el.classList.contains(checkClass));
    }

    return false;
};

/**
 * Hide the given element.
 *
 * @param {HTMLElement} el
 */
export const hide = el => {
    if (! isElement(el)) {
        return;
    }

    el.style.display = 'none';
};

/**
 * Determine if the given element is disabled.
 *
 * @param {HTMLElement} el
 * @returns {boolean}
 */
export const isDisabled = el => {
    return ! isElement(el)
        || el.disabled
        || el.classList.contains('disabled')
        || Boolean(el.getAttribute('disabled'));
};

/**
 * Determine if the given item is an element.
 *
 * @param {HTMLElement|*} el
 * @returns {boolean}
 */
export const isElement = el => el && el.nodeType === Node.ELEMENT_NODE;

/**
 * Determine if the given element is visible.
 *
 * @param {HTMLElement} el
 * @returns {boolean}
 */
export const isVisible = el => {
    return isElement(el)
        && document.body.contains(el)
        && el.getBoundingClientRect().height > 0
        && el.getBoundingClientRect().width > 0;
};

/**
 * Determine if the given element matches the given selector.
 *
 * @param {HTMLElement} el
 * @param {string} selector
 * @returns {boolean}
 */
export const matches = (el, selector) => {
    if (! isElement(el)) {
        return false;
    }

    // https://developer.mozilla.org/en-US/docs/Web/API/Element/matches#Polyfill
    // Prefer native implementations over polyfill function
    const proto = Element.prototype;
    const Matches = proto.matches ||
        proto.matchesSelector ||
        proto.mozMatchesSelector ||
        proto.msMatchesSelector ||
        proto.oMatchesSelector ||
        proto.webkitMatchesSelector ||
        function (sel) {
            const element = this;
            const m = selectAll(sel, element.document || element.ownerDocument);
            let i = m.length;

            while (--i >= 0 && m.item(i) !== element) {}
            return i > -1;
        };

    return Matches.call(el, selector);
};

/**
 * Return an element's offset wrt document element.
 * https://j11y.io/jquery/#v=git&fn=jQuery.fn.offset
 *
 * @param {HTMLElement} el
 * @returns {*}
 */
export const offset = el => {
    if (isElement(el)) {
        if (! el.getClientRects().length) {
            return { top: 0, left: 0 };
        }

        const bcr = getBCR(el);
        const win = el.ownerDocument.defaultView;

        return {
            top: bcr.top + win.pageYOffset,
            left: bcr.left + win.pageXOffset
        };
    }
};

/**
 * Return an element's offset wrt to its offsetParent.
 * https://j11y.io/jquery/#v=git&fn=jQuery.fn.position
 *
 * @param {HTMLElement} el
 * @returns {object}
 */
export const position = el => {
    if (! isElement(el)) {
        return;
    }

    let parentOffset = { top: 0, left: 0 };
    let offsetSelf;
    let offsetParent;

    if (getCS(el).position === 'fixed') {
        offsetSelf = getBCR(el);
    } else {
        offsetSelf = offset(el);
        const doc = el.ownerDocument;
        offsetParent = el.offsetParent || doc.documentElement;

        while (offsetParent
        && (offsetParent === doc.body || offsetParent === doc.documentElement)
        && getCS(offsetParent).position === 'static') {
            offsetParent = offsetParent.parentNode;
        }

        if (offsetParent && offsetParent !== el && offsetParent.nodeType === Node.ELEMENT_NODE) {
            parentOffset = offset(offsetParent);
            parentOffset.top += parseFloat(getCS(offsetParent).borderTopWidth);
            parentOffset.left += parseFloat(getCS(offsetParent).borderLeftWidth);
        }
    }

    return {
        top: offsetSelf.top - parentOffset.top - parseFloat(getCS(el).marginTop),
        left: offsetSelf.left - parentOffset.left - parseFloat(getCS(el).marginLeft)
    };
};

/**
 * Cause/wait for an element to reflow its content (adjusting its height/width).
 * -- Requesting an element's offsetHeight will trigger a reflow of the element content.
 *
 * @param {HTMLElement} el
 * @returns {boolean|number}
 */
export const reflow = el => isElement(el) && el.offsetHeight;

export const getElHeight = el => {
    try {
        return select(el).offsetHeight;
    } catch (e) {
        return 0;
    }
};

/**
 * Remove an attribute from the given element.
 *
 * @param {HTMLElement} el
 * @param {string} attr
 */
export const removeAttr = (el, attr) => {
    if (attr && isElement(el)) {
        el.removeAttribute(attr);
    }
};

/**
 * Remove a class from the given element.
 *
 * @param {HTMLElement} el
 * @param {string} className
 */
export const removeClass = (el, className) => {
    if (className && isElement(el)) {
        el.classList.remove(className);
    }
};

/**
 * Select a single element. Returns null if not found.
 *
 * @param {string} selector
 * @param {HTMLElement} root
 * @returns {*|null}
 */
export const select = (selector, root) => {
    if (! isElement(root)) {
        root = document;
    }

    return root.querySelector(selector) || null;
};

/**
 * Select all elements matching the given selector.
 * Returns [] if none found.
 *
 * @param {string} selector
 * @param {HTMLElement|*} root
 * @returns {array}
 */
export const selectAll = (selector, root) => {
    if (! isElement(root)) {
        root = document;
    }

    return arrayFrom(root.querySelectorAll(selector));
};

/**
 * Set an attribute on the given element.
 *
 * @param {HTMLElement} el
 * @param {string} attr
 * @param {string} value
 */
export const setAttr = (el, attr, value) => {
    if (attr && isElement(el)) {
        el.setAttribute(attr, value);
    }
};

/**
 * Show the given element.
 *
 * @param {HTMLElement} el
 */
export const show = el => {
    if (! isElement(el)) {
        return;
    }

    el.style.display = null;
};

/**
 * Toggle the given class(s) for the given element.
 *
 * @param {HTMLElement} el
 * @param {array|string} classList
 */
export const toggleClass = (el, classList) => {
    if (! isElement(el)) {
        return;
    }

    if (! isArray(classList)) {
        try {
            classList = classList.split(' ');
        } catch (e) {
            return;
        }
    }

    classList.forEach(toggleClass => {
        if (hasClass(el, toggleClass)) {
            removeClass(el, toggleClass);
        } else {
            addClass(el, toggleClass);
        }
    });
};