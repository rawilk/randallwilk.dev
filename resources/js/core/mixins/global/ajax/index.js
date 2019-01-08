import ajax from './ajax';
import { registerMixins, vueUse } from '../../../utils/plugins';

const mixins = {
    ajax
};

const VuePlugin = {
    install (Vue) {
        registerMixins(Vue, mixins);
    }
};

vueUse(VuePlugin);

export default VuePlugin;