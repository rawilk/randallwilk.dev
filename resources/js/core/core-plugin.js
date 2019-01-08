import * as components from './components';
import * as directives from './directives';
import * as mixins from './mixins/global';
import * as plugins from './plugins';
import filters from './filters/filters';
import VeeValidate from 'vee-validate';
import { vueUse } from './utils/plugins';
import PerfectScrollbar from 'vue2-perfect-scrollbar';

const VuePlugin = {
    install: function (Vue) {
        if (Vue._lara_vue_installed) {
            return;
        }

        Vue._lara_vue_installed = true;

        // Register component plugins
        for (let plugin in components) {
            Vue.use(components[plugin]);
        }

        // Register directive plugins
        for (let plugin in directives) {
            Vue.use(directives[plugin]);
        }

        // Register global mixins
        for (let plugin in mixins) {
            Vue.use(mixins[plugin]);
        }

        // Register global plugins
        for (let plugin in plugins) {
            Vue.use(plugins[plugin]);
        }

        // Register global filters
        Vue.use(filters);

        // Register validation object
        Vue.use(VeeValidate, {
            inject: false,
            errorBagName: 'validationErrors',
            events: 'change|blur'
        });

        // Perfect scrollbar
        Vue.use(PerfectScrollbar);
    }
};

vueUse(VuePlugin);

export default VuePlugin;