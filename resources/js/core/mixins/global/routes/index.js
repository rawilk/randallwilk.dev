import routes from './routes';
import { registerMixins, vueUse } from '../../../utils/plugins';

const mixins = {
    routes
};

const VuePlugin = {
    install (Vue) {
        registerMixins(Vue, mixins);
    }
};

vueUse(VuePlugin);

export default VuePlugin;