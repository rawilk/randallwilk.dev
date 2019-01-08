import Access from '../utils/class/access.class';
import { vueUse } from '../utils/plugins';

const VuePlugin = {
    install (Vue) {
        Vue.prototype.$access = new Access();
    }
};

vueUse(VuePlugin);

export default VuePlugin;