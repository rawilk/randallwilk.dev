import bDropdown from './dropdown';
import bDropdownItem from './dropdown-item';
import bDropdownItemButton from './dropdown-item-button';
import bDropdownHeader from './dropdown-header';
import bDropdownDivider from './dropdown-divider';
import { registerComponents, vueUse } from '../../utils/plugins';

const components = {
    bDropdown,
    bDropdownItem,
    bDropdownItemButton,
    bDropdownHeader,
    bDropdownDivider,
};

const VuePlugin = {
    install (Vue) {
        registerComponents(Vue, components);
    }
};

vueUse(VuePlugin);

export default VuePlugin;