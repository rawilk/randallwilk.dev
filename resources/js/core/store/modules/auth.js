import cloneDeep from 'lodash/cloneDeep';

export const state = {
    user: cloneDeep(window.config.user || {})
};