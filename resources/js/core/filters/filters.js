import filters from './index';

const plugin = {
    install (Vue, options) {
        filters(Vue, options);
    }
};

export default plugin;