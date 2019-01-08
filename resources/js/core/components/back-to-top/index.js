import backToTop from './back-to-top';
import { registerComponents, vueUse } from '../../utils/plugins';

const components = {
    backToTop
};

const VuePlugin = {
    install (Vue) {
        registerComponents(Vue, components);
    }
};

vueUse(VuePlugin);

export default VuePlugin;