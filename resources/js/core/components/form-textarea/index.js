import bFormTextarea from './form-textarea';
import { registerComponents, vueUse } from '../../utils/plugins';

const components = {
    bTextarea: bFormTextarea
};

const VuePlugin = {
    install (Vue) {
        registerComponents(Vue, components);
    }
};

vueUse(VuePlugin);

export default VuePlugin;