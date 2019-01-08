import { SweetAlert } from '../utils/class/swal.class';
import { vueUse } from '../utils/plugins';

const VuePlugin = {
    install (Vue) {
        Vue.prototype.$swal = new SweetAlert();
    }
};

vueUse(VuePlugin);

export default VuePlugin;