import inputHelp from './input-help';
import { registerComponents, vueUse } from '../../utils/plugins';

const components = {
    inputHelp
};

const VuePlugin = {
    install (Vue) {
        registerComponents(Vue, components);
    }
};

vueUse(VuePlugin);

export default VuePlugin;