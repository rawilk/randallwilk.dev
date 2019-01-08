import bFormSelect from './form-select.js';
import { registerComponents, vueUse } from '../../utils/plugins';

const components = {
    bSelect: bFormSelect
};

const VuePlugin = {
    install (Vue) {
        registerComponents(Vue, components);
    }
};

vueUse(VuePlugin);

export default VuePlugin;