import bNav from './nav';
import bNavItem from './nav-item';
import bNavText from './nav-text';
import bNavForm from './nav-form';
import bNavItemDropdown from './nav-item-dropdown';
import dropdownPlugin from '../dropdown';
import { registerComponents, vueUse } from '../../utils/plugins';

const components = {
    bNav,
    bNavItem,
    bNavText,
    bNavForm,
    bNavItemDropdown,
};

const VuePlugin = {
    install (Vue) {
        registerComponents(Vue, components);

        Vue.use(dropdownPlugin);
    }
};

vueUse(VuePlugin);

export default VuePlugin;