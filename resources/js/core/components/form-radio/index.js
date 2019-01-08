import bFormRadio from './form-radio';
import bFormRadioGroup from './form-radio-group';
import { registerComponents, vueUse } from '../../utils/plugins';

const components = {
    bRadio: bFormRadio,
    bRadioGroup: bFormRadioGroup
};

const VuePlugin = {
    install (Vue) {
        registerComponents(Vue, components);
    }
};

vueUse(VuePlugin);

export default VuePlugin;