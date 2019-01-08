import bLoader from './b-loader';
import { registerComponents, vueUse } from '../../utils/plugins';

const components = {
    bLoader
};

const VuePlugin = {
    install (Vue) {
        registerComponents(Vue, components);
    }
};

vueUse(VuePlugin);

export default VuePlugin;