/**
 * Register a component plugin as being loaded.
 * Returns true if component plugin is already registered.
 *
 * @param {object} Vue
 * @param {string} name Component name
 * @param {object} def Component definition
 * @returns {boolean}
 */
export function registerComponent (Vue, name, def) {
    Vue._lara_vue_components_ = Vue._lara_vue_components_ || {};
    const loaded = Vue._lara_vue_components_[name];

    if (! loaded && def && name) {
        Vue._lara_vue_components_[name] = true;
        Vue.component(name, def);
    }

    return loaded;
}

/**
 * Register a group of components as being loaded.
 *
 * @param {object} Vue
 * @param {object} components Object of component definitions
 */
export function registerComponents (Vue, components) {
    for (let component in components) {
        registerComponent(Vue, component, components[component]);
    }
}

/**
 * Register a directive as being loaded.
 * Returns true if directive plugin is already registered.
 *
 * @param {object} Vue
 * @param {string} name Directive name
 * @param {object} def Directive definition
 * @returns {boolean}
 */
export function registerDirective (Vue, name, def) {
    Vue._lara_vue_directives_ = Vue._lara_vue_directives_ || {};
    const loaded = Vue._lara_vue_directives_[name];

    if (! loaded && def && name) {
        Vue._lara_vue_directives_[name] = true;
        Vue.directive(name, def);
    }

    return loaded;
}

/**
 * Register a group of directives as being loaded.
 *
 * @param {object} Vue
 * @param {object} directives Object of directive definitions
 */
export function registerDirectives (Vue, directives) {
    for (let directive in directives) {
        registerDirective(Vue, directive, directives[directive]);
    }
}

/**
 * Register a mixin as being loaded.
 * Returns true if the mixin is already registered.
 *
 * @param {object} Vue
 * @param {string} name Name of the mixin
 * @param {object} def Mixin definition
 * @returns {boolean}
 */
export function registerMixin (Vue, name, def) {
    Vue._lara_vue_mixins_ = Vue._lara_vue_mixins_ || {};
    const loaded = Vue._lara_vue_mixins_[name];

    if (! loaded && def && name) {
        Vue._lara_vue_mixins_[name] = true;

        Vue.mixin(def);
    }

    return loaded;
}

/**
 * Register a group of mixins as being loaded.
 *
 * @param {object} Vue
 * @param {object} mixins
 */
export function registerMixins (Vue, mixins) {
    for (let mixin in mixins) {
        registerMixin(Vue, mixin, mixins[mixin]);
    }
}

/**
 * Install plugin if window.Vue is available.
 *
 * @param {object} VuePlugin Plugin definition
 */
export function vueUse (VuePlugin) {
    if (typeof window !== 'undefined' && window.Vue) {
        window.Vue.use(VuePlugin);
    }
}