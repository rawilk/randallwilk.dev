import bAffix from './affix';
import { registerComponents, vueUse } from '../../utils/plugins';

const components = {
    bAffix
};

const VuePlugin = {
    install (Vue) {
        registerComponents(Vue, components);
    }
};

vueUse(VuePlugin);

export default VuePlugin;