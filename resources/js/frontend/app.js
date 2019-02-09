import Vue from 'vue';
import CorePlugin from '~/core/core-plugin';

Vue.use(CorePlugin);

import GithubGist from './components/github-gist';

new Vue({
    components: {
        GithubGist
    },
}).$mount('#app');