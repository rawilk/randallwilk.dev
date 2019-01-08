import bFormCheckbox from './form-checkbox';
import bFormCheckboxGroup from './form-checkbox-group';
import bSwitch from './switch';
import { registerComponents, vueUse } from '../../utils/plugins';

const components = {
    bCheckbox: bFormCheckbox,
    bCheckboxGroup: bFormCheckboxGroup,
    bSwitch
};

const VuePlugin = {
    install (Vue) {
        registerComponents(Vue, components);
    }
};

vueUse(VuePlugin);

export default VuePlugin;