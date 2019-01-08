import form from './form';
import { registerMixins, vueUse } from '../../../utils/plugins';

const mixins = {
    form
};

const VuePlugin = {
    install (Vue) {
        registerMixins(Vue, mixins);
    }
};

vueUse(VuePlugin);

export default VuePlugin;